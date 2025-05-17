<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Plant;
use App\Entity\Reminder;
use App\Entity\ReminderType;
use App\Entity\User;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Repository\Trait\SecurityTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Reminder>
 */
class ReminderRepository extends ServiceEntityRepository
{
    use SecurityTrait;

    /**
     * @param ManagerRegistry $registry
     * @param Security $security
     */
    public function __construct(ManagerRegistry $registry, private readonly Security $security)
    {
        parent::__construct($registry, Reminder::class);
    }

    /**
     * @param mixed $id
     * @return Reminder
     * @throws NotFoundException
     */
    public function get(mixed $id): Reminder
    {
        $reminder = $this->find($id);
        if (!$reminder instanceof Reminder) {
            throw new NotFoundException('Reminder not found');
        }
        $this->checkIsUserOwner($reminder->getOwner());

        return $reminder;
    }

    /**
     * @return Reminder[]
     * @throws ForbiddenException
     */
    public function getUserReminders(): array
    {
        return $this->findBy(['owner' => $this->getUser()]);
    }

    /**
     * @param mixed $plantId
     * @param mixed $typeId
     * @param \DateTimeImmutable $startExecution
     * @param int $cycle
     * @return Reminder
     * @throws ForbiddenException
     */
    public function create(mixed $plantId, mixed $typeId, \DateTimeImmutable $startExecution, int $cycle): Reminder
    {
        $plantRepository = $this->getEntityManager()->getRepository(Plant::class);
        $plant = $plantRepository->get($plantId);

        $typeRepository = $this->getEntityManager()->getRepository(ReminderType::class);
        $type = $typeRepository->get($typeId);

        $reminder = new Reminder($plant, $this->getUser(), $type, $startExecution, $cycle);
        $this->getEntityManager()->persist($reminder);
        $this->getEntityManager()->flush();

        return $reminder;
    }

    /**
     * @param mixed $id
     * @param mixed $plantId
     * @param mixed $typeId
     * @param \DateTimeImmutable $startExecution
     * @param int $cycle
     * @return Reminder
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function update(mixed $id, mixed $plantId, mixed $typeId, \DateTimeImmutable $startExecution, int $cycle): Reminder
    {
        $plantRepository = $this->getEntityManager()->getRepository(Plant::class);
        $plant = $plantRepository->get($plantId);

        $typeRepository = $this->getEntityManager()->getRepository(ReminderType::class);
        $type = $typeRepository->get($typeId);

        $reminder = $this->get($id);
        $this->checkIsUserOwner($reminder->getOwner());
        $reminder->setReminderType($type);
        $reminder->setPlant($plant);
        $reminder->setStartExecution($startExecution);
        $reminder->setCycle($cycle);
        $this->getEntityManager()->flush();

        return $reminder;
    }

    /**
     * @param mixed $id
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function delete(mixed $id): void
    {
        $reminder = $this->get($id);
        $this->getEntityManager()->remove($reminder);
        $this->getEntityManager()->flush();
    }
}