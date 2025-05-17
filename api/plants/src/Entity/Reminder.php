<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ReminderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ReminderRepository::class)]
#[ORM\Table(name: 'reminders')]
class Reminder
{
    #[ORM\Id]
    #[ORM\Column(name: 'id_reminder', type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\Column(name: 'start_execution', type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $startExecution;

    #[ORM\Column(name: 'cycle', type: Types::INTEGER)]
    private int $cycle;

    #[ORM\ManyToOne(targetEntity: Plant::class, inversedBy: 'reminders')]
    #[ORM\JoinColumn(name: 'id_plant', referencedColumnName: 'id_plant', nullable: false, onDelete: 'CASCADE')]
    private Plant $plant;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reminders')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id_user', nullable: false, onDelete: 'CASCADE')]
    private User $owner;

    #[ORM\ManyToOne(targetEntity: ReminderType::class, inversedBy: 'reminders')]
    #[ORM\JoinColumn(name: 'id_reminder_type', referencedColumnName: 'id_reminder_type', nullable: false, onDelete: 'CASCADE')]
    private ReminderType $reminderType;

    /**
     * @param Plant $plant
     * @param User $owner
     * @param ReminderType $reminderType
     * @param \DateTimeImmutable $startExecution
     * @param int $cycle
     */
    public function __construct(Plant $plant, User $owner, ReminderType $reminderType, \DateTimeImmutable $startExecution, int $cycle)
    {
        $this->startExecution = $startExecution;
        $this->cycle = $cycle;
        $this->plant = $plant;
        $this->owner = $owner;
        $this->reminderType = $reminderType;
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @param \DateTimeImmutable $startExecution
     */
    public function setStartExecution(\DateTimeImmutable $startExecution): void
    {
        $this->startExecution = $startExecution;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getStartExecution(): \DateTimeImmutable
    {
        return $this->startExecution;
    }

    /**
     * @param int $cycle
     */
    public function setCycle(int $cycle): void
    {
        $this->cycle = $cycle;
    }

    /**
     * @return int
     */
    public function getCycle(): int
    {
        return $this->cycle;
    }

    /**
     * @param Plant $plant
     */
    public function setPlant(Plant $plant): void
    {
        $this->plant = $plant;
    }

    /**
     * @return Plant
     */
    public function getPlant(): Plant
    {
        return $this->plant;
    }

    /**
     * @param User $owner
     */
    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param ReminderType $reminderType
     */
    public function setReminderType(ReminderType $reminderType): void
    {
        $this->reminderType = $reminderType;
    }

    /**
     * @return ReminderType
     */
    public function getReminderType(): ReminderType
    {
        return $this->reminderType;
    }
}
