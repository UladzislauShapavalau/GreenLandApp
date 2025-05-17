<?php

declare(strict_types=1);

namespace App\Controller\Reminder;

use App\Exception\ApiException;
use App\Exception\AppException;
use App\Normalizer\PlantNormalizer;
use App\Normalizer\ReminderNormalizer;
use App\Normalizer\ReminderTypeNormalizer;
use App\Normalizer\UserNormalizer;
use App\Repository\ReminderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/api/reminders/{id}', name: 'api.reminders.details', methods: Request::METHOD_GET)]
class DetailsAction extends AbstractController
{
    /**
     * @param Request $request
     * @param ReminderRepository $repository
     * @param string $id
     * @return Response
     * @throws ApiException
     */
    public function __invoke(Request $request, ReminderRepository $repository, string $id): Response
    {
        try {
            $reminder = $repository->get($id);
        } catch (AppException $e) {
            throw new ApiException($e);
        }

        return $this->json($reminder, Response::HTTP_OK, [], [
            ReminderNormalizer::CONTEXT_TYPE_KEY => ReminderNormalizer::DEFAULT_TYPE,
            PlantNormalizer::CONTEXT_TYPE_KEY => PlantNormalizer::ID_ONLY_TYPE,
            UserNormalizer::CONTEXT_TYPE_KEY => UserNormalizer::ID_ONLY_TYPE,
            ReminderTypeNormalizer::CONTEXT_TYPE_KEY => ReminderTypeNormalizer::ID_ONLY_TYPE,
        ]);
    }
}
