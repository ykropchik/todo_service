<?php

namespace App\Controller;

use App\Encoder\NixillaJWTEncoder;
use App\Entity\File;
use App\Repository\FileRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/file")
 */
class FileController extends AbstractController
{
    public function response($data, $status = Response::HTTP_OK, $headers = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }

    protected function jwtDecode(string $token): array
    {
        $decoder = new NixillaJWTEncoder();

        return $decoder->decode($token);
    }

    /**
     * @Route("/", name="get_files_list", methods={"GET"})
     */
    public function getFilesList(Request $request, FileRepository $fileRepository): Response
    {
        $token = $request->headers->get('JWT-Token');
        $decodedToken = $this->jwtDecode($token);

        $username = $decodedToken['username'];

        $filesList = $fileRepository->findBy(['author' => $username]);

        foreach ($filesList as $file) {
            $array = [
                'id' => $file->getId(),
                'displayName' => $file->getDisplayName(),
                'dateCreate' => $file->getDateCreate(),
            ];

            $result[] = $array;
        }

        return $this->response($result);
    }

    /**
     * @Route("/{id}", name="get_file", methods={"GET"})
     */
    public function getFile(Request $request, FileRepository $fileRepository, $id): Response
    {
        $token = $request->headers->get('JWT-Token');
        $decodedToken = $this->jwtDecode($token);

        $username = $decodedToken['username'];

        $file = $fileRepository->findOneBy(['id' => $id]);

        if (!$file) {
            return $this->response([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid file id',
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($file->getAuthor() !== $username) {
            return $this->response([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Not authorized',
            ], Response::HTTP_FORBIDDEN);
        }

        $responsedFile = $this->getParameter('files_directory') . '/' . $file->getSafeName();

        return new BinaryFileResponse($responsedFile);
    }

    /**
     * @Route("/{id}", name="remove_file", methods={"DELETE"})
     */
    public function removeFile(Request $request, FileRepository $fileRepository, $id): Response
    {
        $token = $request->headers->get('JWT-Token');
        $decodedToken = $this->jwtDecode($token);

        $username = $decodedToken['username'];

        $file = $fileRepository->findOneBy(['id' => $id]);

        if (!$file) {
            return $this->response([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid file id',
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($file->getAuthor() !== $username) {
            return $this->response([
                'status' => Response::HTTP_FORBIDDEN,
                'message' => 'Not authorized',
            ], Response::HTTP_FORBIDDEN);
        }

        $filesystem = new Filesystem();
        try {
            $filesystem->remove([$this->getParameter('files_directory') . '/' . $file->getSafeName()]);
        } catch (IOExceptionInterface $exception) {
            return $this->response([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($file);
        $entityManager->flush();

        return $this->response([
            'status' => Response::HTTP_OK,
            'message' => 'Item removed successfully',
        ]);
    }

    /**
     * @Route("/{displayFileName}", name="upload_file", methods={"POST"})
     */
    public function uploadFile($displayFileName, Request $request, FileUploader $fileUploader): Response
    {
        $token = $request->headers->get('JWT-Token');
        $decodedToken = $this->jwtDecode($token);

        $username = $decodedToken['username'];

        $fileData = $request->files->get('file');
        // return  $this->response($fileData);
        if ($fileData) {
            try {
                $fileName = $fileUploader->upload($fileData);
            } catch (Exception $error) {
                return $this->response([
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'Something went wrong',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if ('Error' === $fileName) {
                return $this->response([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => 'Invalid data',
                ], Response::HTTP_BAD_REQUEST);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $file = new File();
            $file->setSafeName($fileName);
            $file->setDisplayName($displayFileName);
            $file->setDateCreate(new \DateTime('now'));
            $file->setAuthor($username);

            $entityManager->persist($file);
            $entityManager->flush();

            return $this->response([
                'status' => Response::HTTP_OK,
                'message' => 'File upload successfully',
            ]);
        }

        return $this->response([
            'status' => Response::HTTP_BAD_REQUEST,
            'message' => 'Invalid data',
        ], Response::HTTP_BAD_REQUEST);
    }
}
