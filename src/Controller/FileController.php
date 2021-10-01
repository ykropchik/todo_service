<?php

namespace App\Controller;

use App\Entity\File;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Encoder\NixillaJWTEncoder;

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
        $decoder = new NixillaJWTEncoder;
        return $decoder->decode($token);
    }

    /**
     * @Route("/", name="get_file", method={"GET"})
     */
    public function getFiles(Request $request): Response
    {
        return $this->render('file/index.html.twig', [
            'controller_name' => 'FileController',
        ]);
    }

    /**
     * @Route("/{id}", name="get_file", method={"GET"})
     */
    public function getFile(Request $request, $id): Response
    {
        return $this->render('file/index.html.twig', [
            'controller_name' => 'FileController',
        ]);
    }

    /**
     * @Route("/", name="upload_file", method={"POST"})
     */
    public function uploadFile(Request $request, FileUploader $fileuploader, File $file): Response
    {
        return $this->response("test");
        $token = $request->headers->get('JWT-Token');
        $decodedToken = $this->jwtDecode($token);

        $username = $decodedToken['username'];

        $fileData = $request->get('file')->getData();
        if ($fileData) {
            $fileName = $fileUploader->upload($fileData);

            if($fileName === "Error") {
                return $this->response([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'success' => "Invalid data",
                ], Response::HTTP_BAD_REQUEST);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $file = new File;
            $file->setName($fileName);
            $file->setDateCreate(new \Date('now'));
            $file->setAuthor($username);

            $entityManager->persist($file);
            $entityManager->flush();
        }

        return $this->response([
            'status' => Response::HTTP_OK,
            'success' => "File upload successfully",
        ]);
    }
}
