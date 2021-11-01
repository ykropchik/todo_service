<?php

namespace App\Controller;

use App\Encoder\NixillaJWTEncoder;
use App\Repository\FileRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
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
     * @Route("/getUsers", name="getUsers", methods={"GET"})
     */
    public function getUsers(Request $request, UserRepository $userRepository): Response
    {
        $token = $request->headers->get('JWT-Token');
        $decodedToken = $this->jwtDecode($token);

        $roles = $decodedToken['roles'];

        if (!in_array('ROLE_ADMIN', $roles)) {
            return $this->response([
                'status' => Response::HTTP_FORBIDDEN,
                'success' => 'Not authorized',
            ], Response::HTTP_FORBIDDEN);
        }

        $userList = $userRepository->findAll();

        foreach ($userList as $user) {
            $array = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'password' => $user->getPassword(),
            ];

            $result[] = $array;
        }

        return $this->response($result);
    }

    /**
     * @Route("/getFilesInfo", name="getFilesInfo", methods={"GET"})
     */
    public function getFilesInfo(Request $request, FileRepository $fileRepository): Response
    {
        $token = $request->headers->get('JWT-Token');
        $decodedToken = $this->jwtDecode($token);

        $roles = $decodedToken['roles'];

        if (!in_array('ROLE_ADMIN', $roles)) {
            return $this->response([
                'status' => Response::HTTP_FORBIDDEN,
                'success' => 'Not authorized',
            ], Response::HTTP_FORBIDDEN);
        }

        $filesList = $fileRepository->findAll();
        $result = [];

        foreach ($filesList as $file) {
            $array = [
                'id' => $file->getId(),
                'safeName' => $file->getSafeName(),
                'displayName' => $file->getDisplayName(),
                'author' => $file->getAuthor(),
                'date' => $file->getDateCreate(),
            ];

            $result[] = $array;
        }

        return $this->response($result);
    }
}
