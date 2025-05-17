<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
#[ORM\Table(name: 'locations')]
class Location
{
    #[ORM\Id]
    #[ORM\Column(name: 'id_location', type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\Column(name: 'name_location', type: Types::STRING)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'locations')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id_user', nullable: false, onDelete: 'CASCADE')]
    private User $owner;

    #[ORM\OneToMany(targetEntity: Plant::class, mappedBy: 'location', orphanRemoval: true)]
    private Collection $plants;

    /**
     * @param User $owner
     * @param string $name
     */
    public function __construct(User $owner, string $name)
    {
        $this->owner = $owner;
        $this->name = $name;
        $this->plants = new ArrayCollection();
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
            $plant->setLocation($this);
        }
    }
}
