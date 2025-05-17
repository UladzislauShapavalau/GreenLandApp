<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/login', name: 'api.security.login', methods: Request::METHOD_POST)]
class LoginAction extends AbstractController
{
    /**
     * @param Request $request
     * @param JWTTokenManagerInterface $jwtManager
     * @return Response
     */
    public function __invoke(Request $request, JWTTokenManagerInterface $jwtManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->json(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json(['token' => $jwtManager->create($user)]);
    }
}
