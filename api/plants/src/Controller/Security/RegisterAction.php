<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\DataProvider\Security;
use App\DTO\User\RegisterApiSchema;
use App\Exception\ApiException;
use App\Exception\AppException;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/api/register', name: 'api.security.register', methods: Request::METHOD_POST)]
class RegisterAction extends AbstractController
{
    /**
     * @param Request $request
     * @param UserRepository $repository
     * @param RegisterApiSchema $schema
     * @return Response
     * @throws ApiException
     */
    public function __invoke(
        Request $request,
        UserRepository $repository,
        #[MapRequestPayload] RegisterApiSchema $schema
    ): Response {
        try {
            $repository->create($schema->email, $schema->name, $schema->password, [Security::ROLE_USER]);
        } catch (AppException $e) {
            throw new ApiException($e);
        }

        return $this->json(null, Response::HTTP_OK);
    }
}
