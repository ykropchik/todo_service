<?php

namespace App\Controller;

use App\Service\HealthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HealthController extends AbstractController
{
    public function response($data, $status = Response::HTTP_OK, $headers = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }

    /**
     * @Route("/health", name="health")
     */
    public function index(HealthService $healthService): Response
    {
        return $this->response([
            'status' => Response::HTTP_OK,
            'APP_ENV' => $healthService->getEnvName(),
        ]);
    }
}
