<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PlantType;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlantType>
 */
class PlantTypeRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlantType::class);
    }

    /**
     * @param mixed $id
     * @return PlantType
     * @throws NotFoundException
     */
    public function get(mixed $id): PlantType
    {
        $plantType = $this->find($id);
        if (!$plantType instanceof PlantType) {
            throw new NotFoundException('Plant type not found');
        }

        return $plantType;
    }

    /**
     * @param string $name
     * @return PlantType
     */
    public function create(string $name): PlantType
    {
        $plantType = new PlantType($name);
        $this->getEntityManager()->persist($plantType);
        $this->getEntityManager()->flush();

        return $plantType;
    }

    /**
     * @param mixed $id
     * @param string $name
     * @return PlantType
     * @throws NotFoundException
     */
    public function update(mixed $id, string $name): PlantType
    {
        $plantType = $this->get($id);
        $plantType->setName($name);
        $this->getEntityManager()->flush();

        return $plantType;
    }

    /**
     * @param mixed $id
     * @throws NotFoundException
     */
    public function delete(mixed $id): void
    {
        $plantType = $this->get($id);
        if (!$plantType->getPlants()->isEmpty()) {
            throw new ForbiddenException('Is not allowed to delete plant type having one or more plants');
        }

        $this->getEntityManager()->remove($plantType);
        $this->getEntityManager()->flush();
    }
}
