<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ReminderType;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReminderType>
 */
class ReminderTypeRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReminderType::class);
    }

    /**
     * @param mixed $id
     * @return ReminderType
     * @throws NotFoundException
     */
    public function get(mixed $id): ReminderType
    {
        $reminderType = $this->find($id);
        if (!$reminderType instanceof ReminderType) {
            throw new NotFoundException('Reminder type not found');
        }

        return $reminderType;
    }

    /**
     * @param string $name
     * @return ReminderType
     */
    public function create(string $name): ReminderType
    {
        $reminderType = new ReminderType($name);
        $this->getEntityManager()->persist($reminderType);
        $this->getEntityManager()->flush();

        return $reminderType;
    }

    /**
     * @param mixed $id
     * @param string $name
     * @return ReminderType
     * @throws NotFoundException
     */
    public function update(mixed $id, string $name): ReminderType
    {
        $reminderType = $this->get($id);
        $reminderType->setName($name);
        $this->getEntityManager()->flush();

        return $reminderType;
    }

    /**
     * @param mixed $id
     * @throws NotFoundException
     */
    public function delete(mixed $id): void
    {
        $reminderType = $this->get($id);
        if (!$reminderType->getReminders()->isEmpty()) {
            throw new ForbiddenException('Is not allowed to delete reminder type having one or more reminders');
        }

        $this->getEntityManager()->remove($reminderType);
        $this->getEntityManager()->flush();
    }
}
