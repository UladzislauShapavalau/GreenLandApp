<?php

declare(strict_types=1);

namespace App\Controller\Plant;

use App\Exception\ApiException;
use App\Exception\AppException;
use App\Repository\PlantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/plants/{id}', name: 'api.plants.delete', methods: Request::METHOD_DELETE)]
class DeleteAction extends AbstractController
{
    /**
     * @param Request $request
     * @param PlantRepository $repository
     * @param string $id
     * @return Response
     * @throws ApiException
     */
    public function __invoke(Request $request, PlantRepository $repository, string $id): Response
    {
        try {
            $repository->delete($id,);
        } catch (AppException $e) {
            throw new ApiException($e);
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
