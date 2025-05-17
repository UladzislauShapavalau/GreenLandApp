<?php

declare(strict_types=1);

namespace App\Controller\ReminderType;

use App\DTO\ReminderType\CreateReminderTypeApiSchema;
use App\Exception\ApiException;
use App\Exception\AppException;
use App\Normalizer\ReminderTypeNormalizer;
use App\Repository\ReminderTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/api/reminder-types', name: 'api.reminder_type.create', methods: Request::METHOD_POST)]
class CreateAction extends AbstractController
{
    /**
     * @param Request $request
     * @param ReminderTypeRepository $repository
     * @param CreateReminderTypeApiSchema $schema
     * @return Response
     * @throws ApiException
     */
    public function __invoke(
        Request $request,
        ReminderTypeRepository $repository,
        #[MapRequestPayload] CreateReminderTypeApiSchema $schema
    ): Response {
        try {
            $reminderType = $repository->create($schema->name);
        } catch (AppException $e) {
            throw new ApiException($e);
        }

        return $this->json($reminderType, Response::HTTP_OK, [], [
            ReminderTypeNormalizer::CONTEXT_TYPE_KEY => ReminderTypeNormalizer::DEFAULT_TYPE,
        ]);
    }
}
