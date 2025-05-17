<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Exception\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct($registry, User::class);
    }

    /**
     * @param mixed $id
     * @return User
     * @throws NotFoundException
     */
    public function get(mixed $id): User
    {
        $user = $this->find($id);
        if (!$user instanceof User) {
            throw new NotFoundException('User not found');
        }

        return $user;
    }

    /**
     * @param string $email
     * @param string $name
     * @param string $plainPassword
     * @param array $roles
     * @return User
     */
    public function create(string $email, string $name, string $plainPassword, array $roles): User
    {
        $user = new User($email, $name, '', $roles);
        $password = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($password);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $user;
    }

    /**
     * @param mixed $id
     * @param string $email
     * @param string $name
     * @param string $plainPassword
     * @param array $roles
     * @return User
     * @throws NotFoundException
     */
    public function update(mixed $id, string $email, string $name, string $plainPassword, array $roles): User
    {
        $user = $this->get($id);
        $user->setEmail($email);
        $user->setName($name);
        $user->setRoles($roles);
        $password = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($password);
        $this->getEntityManager()->flush();

        return $user;
    }

    /**
     * @param mixed $id
     * @throws NotFoundException
     */
    public function delete(mixed $id): void
    {
        $user = $this->get($id);
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }
}
