<?php

declare(strict_types=1);

namespace App\Controller\Reminder;

use App\Exception\ApiException;
use App\Exception\AppException;
use App\Repository\ReminderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/api/reminders/{id}', name: 'api.reminders.delete', methods: Request::METHOD_DELETE)]
class DeleteAction extends AbstractController
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
            $repository->delete($id);
        } catch (AppException $e) {
            throw new ApiException($e);
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
