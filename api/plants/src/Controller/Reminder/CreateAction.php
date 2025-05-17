<?php

declare(strict_types=1);

namespace App\Controller\Reminder;

use App\DTO\Reminder\CreateReminderApiSchema;
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
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/api/reminders', name: 'api.reminders.create', methods: Request::METHOD_POST)]
class CreateAction extends AbstractController
{
    /**
     * @param Request $request
     * @param ReminderRepository $repository
     * @param CreateReminderApiSchema $schema
     * @return Response
     * @throws ApiException
     */
    public function __invoke(
        Request $request,
        ReminderRepository $repository,
        #[MapRequestPayload] CreateReminderApiSchema $schema
    ): Response {
        try {
            $reminder = $repository->create($schema->plant, $schema->type, $schema->startExecution, $schema->cycle);
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
