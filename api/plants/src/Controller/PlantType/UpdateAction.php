<?php

declare(strict_types=1);

namespace App\Controller\PlantType;

use App\DTO\PlantType\UpdatePlantTypeApiSchema;
use App\Exception\ApiException;
use App\Exception\AppException;
use App\Normalizer\PlantTypeNormalizer;
use App\Repository\PlantTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/api/plant-types/{id}', name: 'api.plant_type.update', methods: Request::METHOD_PUT)]
class UpdateAction extends AbstractController
{
    /**
     * @param Request $request
     * @param PlantTypeRepository $repository
     * @param UpdatePlantTypeApiSchema $schema
     * @param string $id
     * @return Response
     * @throws ApiException
     */
    public function __invoke(
        Request $request,
        PlantTypeRepository $repository,
        #[MapRequestPayload] UpdatePlantTypeApiSchema $schema,
        string $id
    ): Response {
        try {
            $plantType = $repository->update($id, $schema->name);
        } catch (AppException $e) {
            throw new ApiException($e);
        }

        return $this->json($plantType, Response::HTTP_OK, [], [
            PlantTypeNormalizer::CONTEXT_TYPE_KEY => PlantTypeNormalizer::DEFAULT_TYPE,
        ]);
    }
}
