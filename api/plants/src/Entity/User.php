<?php

declare(strict_types=1);

namespace App\Entity;

use App\DataProvider\Security;
use App\Exception\AppException;
use App\Helper\DateTimeHelper;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(name: 'id_user', type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\Column(name: 'email', type: Types::STRING, unique: true)]
    private string $email;

    #[ORM\Column(name: 'name', type: Types::STRING)]
    private string $name;

    #[ORM\Column(name: 'password', type: Types::STRING)]
    private string $password;

    #[ORM\Column(name: 'roles', type: Types::JSON)]
    private array $roles;

    #[ORM\Column(name: 'creation_date', type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $creationDate;

    #[ORM\OneToMany(targetEntity: Location::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $locations;

    #[ORM\OneToMany(targetEntity: Plant::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $plants;

    #[ORM\OneToMany(targetEntity: Reminder::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $reminders;

    /**
     * @param string $email
     * @param string $name
     * @param string $password
     * @param array $roles
     */
    public function __construct(string $email, string $name, string $password, array $roles)
    {
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->roles = $roles;
        $this->locations = new ArrayCollection();
        $this->plants = new ArrayCollection();
        $this->reminders = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
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
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        if (empty($roles)) {
            $roles[] = Security::ROLE_USER;
        }

        return array_unique($roles);
    }

    /**
     * @param \DateTimeImmutable $creationDate
     */
    public function setCreationDate(\DateTimeImmutable $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreationDate(): \DateTimeImmutable
    {
        return $this->creationDate;
    }

    /**
     * @throws AppException
     */
    #[ORM\PrePersist]
    public function onPersist(): void
    {
        $this->creationDate = DateTimeHelper::now();
    }

    /**
     * @return Collection
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    /**
     * @param Location $location
     */
    public function addLocation(Location $location): void
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->setOwner($this);
        }
    }

    /**
     * @return Collection
     */
    public function getPlants(): Collection
    {
        return $this->plants;
    }

    /**
     * @param Plant $plant
     */
    public function addPlant(Plant $plant): void
    {
        if (!$this->plants->contains($plant)) {
            $this->plants->add($plant);
            $plant->setOwner($this);
        }

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
            $reminder->setOwner($this);
        }
    }
}
