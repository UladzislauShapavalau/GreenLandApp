<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Location;
use App\Entity\Plant;
use App\Entity\PlantType;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Repository\Trait\SecurityTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Plant>
 */
class PlantRepository extends ServiceEntityRepository
{
    use SecurityTrait;

    /**
     * @param ManagerRegistry $registry
     * @param Security $security
     */
    public function __construct(ManagerRegistry $registry, private readonly Security $security)
    {
        parent::__construct($registry, Plant::class);
    }

    /**
     * @param mixed $id
     * @return Plant
     * @throws NotFoundException
     */
    public function get(mixed $id): Plant
    {
        $plant = $this->find($id);
        if (!$plant instanceof Plant) {
            throw new NotFoundException('Plant not found');
        }
        $this->checkIsUserOwner($plant->getOwner());

        return $plant;
    }

    /**
     * @return Plant[]
     * @throws ForbiddenException
     */
    public function getUserPlants(): array
    {
        return $this->findBy(['owner' => $this->getUser()]);
    }

    /**
     * @param mixed $typeId
     * @param mixed $locationId
     * @param mixed $image
     * @param string $nickname
     * @param string $name
     * @param \DateTimeImmutable $adoptionDate
     * @param string|null $description
     * @return Plant
     * @throws ForbiddenException
     */
    public function create(
        mixed $typeId,
        mixed $locationId,
        mixed $image,
        string $nickname,
        string $name,
        \DateTimeImmutable $adoptionDate,
        ?string $description
    ): Plant {
        $locationRepository = $this->getEntityManager()->getRepository(Location::class);
        $location = $locationRepository->get($locationId);

        $typeRepository = $this->getEntityManager()->getRepository(PlantType::class);
        $type = $typeRepository->get($typeId);

        $plant = new Plant($this->getUser(), $location, $type, $image, $nickname, $name, $adoptionDate, $description);
        $this->getEntityManager()->persist($plant);
        $this->getEntityManager()->flush();

        return $plant;
    }

    /**
     * @param mixed $id
     * @param mixed $typeId
     * @param mixed $locationId
     * @param mixed $image
     * @param string $nickname
     * @param string $name
     * @param \DateTimeImmutable $adoptionDate
     * @param string|null $description
     * @return Plant
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function update(
        mixed $id,
        mixed $typeId,
        mixed $locationId,
        mixed $image,
        string $nickname,
        string $name,
        \DateTimeImmutable $adoptionDate,
        ?string $description
    ): Plant {
        $locationRepository = $this->getEntityManager()->getRepository(Location::class);
        $location = $locationRepository->get($locationId);

        $typeRepository = $this->getEntityManager()->getRepository(PlantType::class);
        $type = $typeRepository->get($typeId);

        $plant = $this->get($id);
        $this->checkIsUserOwner($plant->getOwner());
        $plant->setLocation($location);
        $plant->setPlantType($type);
        $plant->setImage($image);
        $plant->setNickname($nickname);
        $plant->setName($name);
        $plant->setAdoptionDate($adoptionDate);
        $plant->setDescription($description);
        $this->getEntityManager()->flush();

        return $plant;
    }

    /**
     * @param mixed $id
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function delete(mixed $id): void
    {
        $plant = $this->get($id);
        if (!$plant->getReminders()->isEmpty()) {
            throw new ForbiddenException('Plant can not be deleted while reminders exist');
        }

        $this->getEntityManager()->remove($plant);
        $this->getEntityManager()->flush();
    }
}