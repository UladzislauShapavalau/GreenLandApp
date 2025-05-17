<?php

declare(strict_types=1);

namespace App\Repository\Trait;

use App\Entity\User;
use App\Exception\ForbiddenException;

trait SecurityTrait
{
    /**
     * @return User
     * @throws ForbiddenException
     */
    protected function getUser(): User
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new ForbiddenException('User not authorized');
        }

        return $user;
    }

    /**
     * @param User $owner
     * @throws ForbiddenException
     */
    protected function checkIsUserOwner(User $owner): void
    {
        if ($this->getUser()->getId() !== $owner->getId()) {
            throw new ForbiddenException('User is not allowed to access this resource');
        }
    }
}