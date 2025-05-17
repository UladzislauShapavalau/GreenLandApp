<?php

declare(strict_types=1);

namespace App\Controller\Plant;

use App\Exception\ApiException;
use App\Exception\AppException;
use App\Normalizer\LocationNormalizer;
use App\Normalizer\PlantNormalizer;
use App\Normalizer\PlantTypeNormalizer;
use App\Normalizer\ReminderNormalizer;
use App\Normalizer\ReminderTypeNormalizer;
use App\Normalizer\UserNormalizer;
use App\Repository\PlantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/plants/{id}', name: 'api.plants.details', methods: Request::METHOD_GET)]
class DetailsAction extends AbstractController
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
            $plant = $repository->get($id);
        } catch (AppException $e) {
            throw new ApiException($e);
        }

        return $this->json($plant, Response::HTTP_OK, [], [
            PlantNormalizer::CONTEXT_TYPE_KEY => PlantNormalizer::DEFAULT_TYPE,
            LocationNormalizer::CONTEXT_TYPE_KEY => LocationNormalizer::ID_ONLY_TYPE,
            UserNormalizer::CONTEXT_TYPE_KEY => UserNormalizer::ID_ONLY_TYPE,
            PlantTypeNormalizer::CONTEXT_TYPE_KEY => PlantTypeNormalizer::ID_ONLY_TYPE,
            ReminderNormalizer::CONTEXT_TYPE_KEY => ReminderNormalizer::IN_PLANT_TYPE,
            ReminderTypeNormalizer::CONTEXT_TYPE_KEY => ReminderTypeNormalizer::ID_ONLY_TYPE,
        ]);
    }
}
