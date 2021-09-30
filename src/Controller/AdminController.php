<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Encoder\NixillaJWTEncoder;

class AdminController extends AbstractController
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
     * @Route("/getUsers", name="getUsers", methods={"GET"})
     */
    public function getUsers(Request $request, UserRepository $userRepository): Response
    {
        $token = $request->headers->get('JWT-Token');
        $decodedToken = $this->jwtDecode($token);

        $roles = $decodedToken['roles'];

        if(!in_array('ROLE_ADMIN', $roles)) {
            return $this->response([
                'status' => Response::HTTP_FORBIDDEN,
                'success' => "Not authorized",
            ], Response::HTTP_FORBIDDEN);
        }

        $userList = $userRepository->findAll();

        foreach ($userList as $user) {
            $array = [
                "id" => $user->getId(),
                "username" => $user->getUsername(),
                "password" => $user->getPassword()
            ];

            $result[] = $array;
        }

        return $this->response($result);
    }
}
