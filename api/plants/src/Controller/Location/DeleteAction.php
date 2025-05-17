<?php

declare(strict_types=1);

namespace App\Controller\Location;

use App\Exception\ApiException;
use App\Exception\AppException;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/api/locations/{id}', name: 'api.locations.delete', methods: Request::METHOD_DELETE)]
class DeleteAction extends AbstractController
{
    /**
     * @param Request $request
     * @param LocationRepository $repository
     * @param string $id
     * @return Response
     * @throws ApiException
     */
    public function __invoke(Request $request, LocationRepository $repository, string $id): Response
    {
        try {
            $repository->delete($id);
        } catch (AppException $e) {
            throw new ApiException($e);
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
