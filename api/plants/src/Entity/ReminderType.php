<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ReminderTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ReminderTypeRepository::class)]
#[ORM\Table(name: 'reminder_types')]
class ReminderType
{
    #[ORM\Id]
    #[ORM\Column(name: 'id_reminder_type', type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\Column(name: 'reminder_type_name', type: Types::STRING)]
    private string $name;

    #[ORM\OneToMany(targetEntity: Reminder::class, mappedBy: 'reminderType')]
    private Collection $reminders;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->reminders = new ArrayCollection();
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Collection
     */
    public function getReminders(): Collection
    {
        return $this->reminders;
    }

    /**
     * @param Reminder $reminder
     */
    public function addReminder(Reminder $reminder): void
    {
        if (!$this->reminders->contains($reminder)) {
            $this->reminders->add($reminder);
            $reminder->setReminderType($this);
        }
    }
}
