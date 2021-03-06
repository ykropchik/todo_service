<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function response($data, $status = Response::HTTP_OK, $headers = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }

    protected function transformJsonBody(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);
        if (null === $data) {
            return $request;
        }
        $request->request->replace($data);

        return $request;
    }

    protected function isValid(string $string): bool
    {
        if (strlen($string) < 6 || strlen($string) > 24) {
            return false;
        }

        return true;
    }

    /**
     * @Route("/registration", name="user_registration", methods={"POST"})
     */
    public function registration(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordEncoder
    ): Response {
        $username = $request->get('username');
        $password = $request->get('password');

        if ($username == null || $password == null) {
            return $this->response([
                'status' => Response::HTTP_BAD_REQUEST,
                'username' => $username,
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!$this->isValid($username)) {
            return $this->response([
                'status' => Response::HTTP_BAD_REQUEST,
                'success' => 'Invalid username',
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!$this->isValid($password)) {
            return $this->response([
                'status' => Response::HTTP_BAD_REQUEST,
                'success' => 'Invalid password',
            ], Response::HTTP_BAD_REQUEST);
        }

        if (0 !== count($userRepository->findBy(['username' => $username]))) {
            return $this->response([
                'status' => Response::HTTP_BAD_REQUEST,
                'success' => 'User with this username alredy exist',
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user
            ->setUsername($username)
            ->setRoles(['ROLE_USER'])
            ->setPassword($passwordEncoder->hashPassword(
                $user,
                $request->get('password')
            ));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->response([
            'status' => Response::HTTP_OK,
            'success' => 'User registered successfully',
        ]);
    }
}
