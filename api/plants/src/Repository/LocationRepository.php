<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Location;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Repository\Trait\SecurityTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Location>
 */
class LocationRepository extends ServiceEntityRepository
{
    use SecurityTrait;

    /**
     * @param ManagerRegistry $registry
     * @param Security $security
     */
    public function __construct(ManagerRegistry $registry, private readonly Security $security)
    {
        parent::__construct($registry, Location::class);
    }

    /**
     * @param mixed $id
     * @return Location
     * @throws NotFoundException
     */
    public function get(mixed $id): Location
    {
        $location = $this->find($id);
        if (!$location instanceof Location) {
            throw new NotFoundException('Location not found');
        }
        $this->checkIsUserOwner($location->getOwner());

        return $location;
    }

    /**
     * @return Location[]
     * @throws ForbiddenException
     */
    public function getUserLocations(): array
    {
        return $this->findBy(['owner' => $this->getUser()]);
    }

    /**
     * @param string $name
     * @return Location
     * @throws ForbiddenException
     */
    public function create(string $name): Location
    {
        $location = new Location($this->getUser(), $name);
        $this->getEntityManager()->persist($location);
        $this->getEntityManager()->flush();

        return $location;
    }

    /**
     * @param mixed $id
     * @param string $name
     * @return Location
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function update(mixed $id, string $name): Location
    {
        $location = $this->get($id);
        $this->checkIsUserOwner($location->getOwner());
        $location->setName($name);
        $this->getEntityManager()->flush();

        return $location;
    }

    /**
     * @param mixed $id
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function delete(mixed $id): void
    {
        $location = $this->get($id);
        if (!$location->getPlants()->isEmpty()) {
            throw new ForbiddenException('You cannot delete locations that have a plant');
        }

        $this->getEntityManager()->remove($location);
        $this->getEntityManager()->flush();
    }
}