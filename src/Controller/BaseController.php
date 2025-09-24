<?php

namespace App\Controller;

use App\Config\Resource\JsonResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    public function successDataResponse(JsonResource $data, int $status = Response::HTTP_OK): JsonResponse
    {
        return $this->json(
            data: array_merge(['status' => 'success'], $data->jsonSerialize()),
            status: $status
        );
    }
}
