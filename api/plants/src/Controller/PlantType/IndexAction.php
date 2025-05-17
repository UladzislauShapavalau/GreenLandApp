<?php

declare(strict_types=1);

namespace App\Controller\PlantType;

use App\Exception\ApiException;
use App\Exception\AppException;
use App\Normalizer\PlantTypeNormalizer;
use App\Repository\PlantTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/api/plant-types', name: 'api.plant_type.index', methods: Request::METHOD_GET)]
class IndexAction extends AbstractController
{
    /**
     * @param Request $request
     * @param PlantTypeRepository $repository
     * @return Response
     * @throws ApiException
     */
    public function __invoke(Request $request, PlantTypeRepository $repository): Response
    {
        try {
            $plantTypes = $repository->findAll();
        } catch (AppException $e) {
            throw new ApiException($e);
        }

        return $this->json($plantTypes, Response::HTTP_OK, [], [
            PlantTypeNormalizer::CONTEXT_TYPE_KEY => PlantTypeNormalizer::DEFAULT_TYPE,
        ]);
    }
}
