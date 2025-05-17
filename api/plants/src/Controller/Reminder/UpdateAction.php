<?php

declare(strict_types=1);

namespace App\Controller\Reminder;

use App\DTO\Reminder\UpdateReminderApiSchema;
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
#[Route(path: '/api/reminders/{id}', name: 'api.reminders.update', methods: Request::METHOD_PUT)]
class UpdateAction extends AbstractController
{
    /**
     * @param Request $request
     * @param ReminderRepository $repository
     * @param UpdateReminderApiSchema $schema
     * @param string $id
     * @return Response
     * @throws ApiException
     */
    public function __invoke(
        Request $request,
        ReminderRepository $repository,
        #[MapRequestPayload] UpdateReminderApiSchema $schema,
        string $id
    ): Response {
        try {
            $reminder = $repository->update($id, $schema->plant, $schema->type, $schema->startExecution, $schema->cycle);
        } catch (AppException $e) {
            throw new ApiException($e);
        }

        return $this->json($reminder, Response::HTTP_OK, [], [
            ReminderNormalizer::CONTEXT_TYPE_KEY => ReminderNormalizer::DEFAULT_TYPE,
            PlantNormalizer::CONTEXT_TYPE_KEY => PlantNormalizer::ID_ONLY_TYPE,
            ReminderTypeNormalizer::CONTEXT_TYPE_KEY => ReminderTypeNormalizer::ID_ONLY_TYPE,
            UserNormalizer::CONTEXT_TYPE_KEY => UserNormalizer::ID_ONLY_TYPE,
        ]);
    }
}
