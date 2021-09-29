<?php

namespace App\Controller;

use App\Entity\TodoItem;
use App\Form\TodoItem1Type;
use App\Repository\TodoItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Encoder\NixillaJWTEncoder;

/**
 * @Route("/todo")
 */
class TodoItemController extends AbstractController
{
    public function response($data, $status = Response::HTTP_OK, $headers = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }

    protected function transformJsonBody(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            return $request;
        }
        $request->request->replace($data);
        return $request;
    }

    protected function jwtDecode(string $token): array
    {
        $decoder = new NixillaJWTEncoder;
        return $decoder->decode($token);
    }

    /**
     * @Route("/", name="todo_items_list", methods={"GET"})
     */
    public function index(Request $request, TodoItemRepository $todoItemRepository): Response
    {
        $token = $request->headers->get('JWT-Token');
        $decodedToken = $this->jwtDecode($token);

        $username = $decodedToken['username'];
        $roles = $decodedToken['roles'];

        $todoList = $todoItemRepository->findAll();

        foreach ($todoList as $todoItem) {
            $array = [
                "id" => $todoItem->getId(),
                "name" => $todoItem->getName(),
                "description" => $todoItem->getDescription(),
                "dateCreate" => $todoItem->getDateCreate(),
                "isDone" => $todoItem->getIsDone()
            ];

            $result[] = $array;
        }

        return $this->response($result);
    }

    /**
     * @Route("/", name="todo_item_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $token = $request->headers->get('JWT-Token');
        $decodedToken = $this->jwtDecode($token);
        $author = $decodedToken['username'];

        $todoItem = new TodoItem();
        $requestBody = $this->transformJsonBody($request);
        $entityManager = $this->getDoctrine()->getManager();

        $todoItem->setAuthor($author);
        $todoItem->setName($requestBody->get('name'));
        $todoItem->setDescription($requestBody->get('description'));
        $todoItem->setDateCreate(new \DateTime('now'));
        $todoItem->setIsDone(false);

        $entityManager->persist($todoItem);
        $entityManager->flush();

        return $this->response([
            'status' => Response::HTTP_OK,
            'success' => "Item added successfully",
        ]);
    }

    /**
     * @Route("/{id}", name="todo_item_edit", methods={"PUT"})
     */
    public function edit(Request $request, TodoItemRepository $todoItemRepository, $id): Response
    {
        $token = $request->headers->get('JWT-Token');
        $decodedToken = $this->jwtDecode($token);
        $username = $decodedToken['username'];
        $todoItem = $todoItemRepository->find($id);

        if(!$todoItem) {
            return $this->response([
                'status' => Response::HTTP_NOT_FOUND,
                'success' => "Item not found",
            ], Response::HTTP_NOT_FOUND);
        }

        if($todoItem->getAuthor() !== $username) {
            return $this->response([
                'status' => Response::HTTP_FORBIDDEN,
                'success' => "Not authorized",
            ], Response::HTTP_FORBIDDEN);
        }

        $requestBody = $this->transformJsonBody($request);
        $name = $requestBody->get('name');
        $description = $requestBody->get('description');
        $isDone = $requestBody->get('isDone');

        if(!$name && !$description && $isDone) {
            return $this->response([
                'status' => Response::HTTP_BAD_REQUEST,
                'success' => "Invalid data",
            ], Response::HTTP_BAD_REQUEST);
        }

        $todoItem->setName($name);
        $todoItem->setDescription($description);
        $todoItem->setIsDone($isDone);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->merge($todoItem);
        $entityManager->flush();

        return $this->response([
            'status' => Response::HTTP_OK,
            'success' => "Item updated successfully",
        ]);
    }

    /**
     * @Route("/{id}", name="todo_item_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TodoItemRepository $todoItemRepository, $id): Response
    {
        $token = $request->headers->get('JWT-Token');
        $decodedToken = $this->jwtDecode($token);
        $username = $decodedToken['username'];
        $todoItem = $todoItemRepository->find($id);

        if(!$todoItem) {
            return $this->response([
                'status' => Response::HTTP_NOT_FOUND,
                'success' => "Item not found",
            ], Response::HTTP_NOT_FOUND);
        }

        if($todoItem->getAuthor() !== $username) {
            return $this->response([
                'status' => Response::HTTP_FORBIDDEN,
                'success' => "Not authorized",
            ], Response::HTTP_FORBIDDEN);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($todoItem);
        $entityManager->flush();

        return $this->response([
            'status' => Response::HTTP_OK,
            'success' => "Item removed successfully",
        ]);
    }
}
