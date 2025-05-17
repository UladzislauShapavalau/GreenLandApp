<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PlantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PlantRepository::class)]
#[ORM\Table(name: 'plants')]
class Plant
{
    #[ORM\Id]
    #[ORM\Column(name: 'id_plant', type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\Column(name: 'image', type: Types::BLOB)]
    private $image;

    #[ORM\Column(name: 'nickname', type: Types::STRING)]
    private string $nickname;

    #[ORM\Column(name: 'name', type: Types::STRING)]
    private string $name;

    #[ORM\Column(name: 'adoption_date', type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $adoptionDate;

    #[ORM\Column(name: 'description', type: Types::TEXT, nullable: true)]
    private ?string $description;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'plants')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id_user', nullable: false, onDelete: 'CASCADE')]
    private User $owner;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'plants')]
    #[ORM\JoinColumn(name: 'id_location', referencedColumnName: 'id_location', nullable: false, onDelete: 'CASCADE')]
    private Location $location;

    #[ORM\ManyToOne(targetEntity: PlantType::class, inversedBy: 'plants')]
    #[ORM\JoinColumn(name: 'id_plant_type', referencedColumnName: 'id_plant_type', nullable: false, onDelete: 'CASCADE')]
    private PlantType $plantType;

    #[ORM\OneToMany(targetEntity: Reminder::class, mappedBy: 'plant')]
    private Collection $reminders;

    /**
     * @param User $owner
     * @param Location $location
     * @param PlantType $plantType
     * @param mixed $image
     * @param string $nickname
     * @param string $name
     * @param \DateTimeImmutable $adoptionDate
     * @param string|null $description
     */
    public function __construct(
        User $owner,
        Location $location,
        PlantType $plantType,
        mixed $image,
        string $nickname,
        string $name,
        \DateTimeImmutable $adoptionDate,
        ?string $description
    ) {
        $this->image = $image;
        $this->nickname = $nickname;
        $this->name = $name;
        $this->adoptionDate = $adoptionDate;
        $this->description = $description;
        $this->owner = $owner;
        $this->location = $location;
        $this->plantType = $plantType;
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
     * @param mixed $image
     */
    public function setImage(mixed $image): void
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getImage(): mixed
    {
        return $this->image;
    }

    /**
     * @param string $nickname
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
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
     * @param \DateTimeImmutable $adoptionDate
     */
    public function setAdoptionDate(\DateTimeImmutable $adoptionDate): void
    {
        $this->adoptionDate = $adoptionDate;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getAdoptionDate(): \DateTimeImmutable
    {
        return $this->adoptionDate;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
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
     * @param Location $location
     */
    public function setLocation(Location $location): void
    {
        $this->location = $location;
    }

    /**
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * @param PlantType $plantType
     */
    public function setPlantType(PlantType $plantType): void
    {
        $this->plantType = $plantType;
    }

    /**
     * @return PlantType
     */
    public function getPlantType(): PlantType
    {
        return $this->plantType;
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
            $reminder->setPlant($this);
        }
    }
}
