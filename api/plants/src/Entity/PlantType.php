<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PlantTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PlantTypeRepository::class)]
#[ORM\Table(name: 'plant_types')]
class PlantType
{
    #[ORM\Id]
    #[ORM\Column(name: 'id_plant_type', type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\Column(name: 'plant_type_name', type: Types::STRING)]
    private string $name;

    #[ORM\OneToMany(targetEntity: Plant::class, mappedBy: 'plantType')]
    private Collection $plants;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
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
            $plant->setPlantType($this);
        }
    }
}
