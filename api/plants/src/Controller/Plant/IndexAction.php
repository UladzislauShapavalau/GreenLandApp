<?php

declare(strict_types=1);

namespace App\Controller\Plant;

use App\Exception\ApiException;
use App\Exception\AppException;
use App\Normalizer\LocationNormalizer;
use App\Normalizer\PlantNormalizer;
use App\Normalizer\PlantTypeNormalizer;
use App\Normalizer\UserNormalizer;
use App\Repository\PlantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/plants', name: 'api.plants.index', methods: Request::METHOD_GET)]
class IndexAction extends AbstractController
{
    /**
     * @param Request $request
     * @param PlantRepository $repository
     * @return Response
     * @throws ApiException
     */
    public function __invoke(Request $request, PlantRepository $repository): Response
    {
        try {
            $plants = $repository->getUserPlants();
        } catch (AppException $e) {
            throw new ApiException($e);
        }

        return $this->json($plants, Response::HTTP_OK, [], [
            PlantNormalizer::CONTEXT_TYPE_KEY => PlantNormalizer::IN_LIST_TYPE,
            LocationNormalizer::CONTEXT_TYPE_KEY => LocationNormalizer::ID_ONLY_TYPE,
            UserNormalizer::CONTEXT_TYPE_KEY => UserNormalizer::ID_ONLY_TYPE,
            PlantTypeNormalizer::CONTEXT_TYPE_KEY => PlantTypeNormalizer::ID_ONLY_TYPE,
        ]);
    }
}
