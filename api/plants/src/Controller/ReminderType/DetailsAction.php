<?php

declare(strict_types=1);

namespace App\Controller\ReminderType;

use App\Exception\ApiException;
use App\Exception\AppException;
use App\Normalizer\ReminderTypeNormalizer;
use App\Repository\ReminderTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/api/reminder-types/{id}', name: 'api.reminder_type.details', methods: Request::METHOD_GET)]
class DetailsAction extends AbstractController
{
    /**
     * @param Request $request
     * @param ReminderTypeRepository $repository
     * @param string $id
     * @return Response
     * @throws ApiException
     */
    public function __invoke(
        Request $request,
        ReminderTypeRepository $repository,
        string $id
    ): Response {
        try {
            $reminderType = $repository->get($id);
        } catch (AppException $e) {
            throw new ApiException($e);
        }

        return $this->json($reminderType, Response::HTTP_OK, [], [
            ReminderTypeNormalizer::CONTEXT_TYPE_KEY => ReminderTypeNormalizer::DEFAULT_TYPE,
        ]);
    }
}
