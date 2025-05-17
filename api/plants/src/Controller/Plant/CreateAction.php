<?php

declare(strict_types=1);

namespace App\Controller\Plant;

use App\DTO\Plant\CreatePlantApiSchema;
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
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/plants', name: 'api.plants.create', methods: Request::METHOD_POST)]
class CreateAction extends AbstractController
{
    /**
     * @param Request $request
     * @param PlantRepository $repository
     * @param CreatePlantApiSchema $schema
     * @return Response
     * @throws ApiException
     */
    public function __invoke(
        Request $request,
        PlantRepository $repository,
        #[MapRequestPayload] CreatePlantApiSchema $schema
    ): Response {
        try {
            $plant = $repository->create(
                $schema->type,
                $schema->location,
                $schema->image,
                $schema->nickname,
                $schema->name,
                $schema->adoptionDate,
                $schema->description
            );
        } catch (AppException $e) {
            throw new ApiException($e);
        }

        return $this->json($plant, Response::HTTP_OK, [], [
            PlantNormalizer::CONTEXT_TYPE_KEY => PlantNormalizer::IN_LIST_TYPE,
            LocationNormalizer::CONTEXT_TYPE_KEY => LocationNormalizer::ID_ONLY_TYPE,
            UserNormalizer::CONTEXT_TYPE_KEY => UserNormalizer::ID_ONLY_TYPE,
            PlantTypeNormalizer::CONTEXT_TYPE_KEY => PlantTypeNormalizer::ID_ONLY_TYPE,
        ]);
    }
}
