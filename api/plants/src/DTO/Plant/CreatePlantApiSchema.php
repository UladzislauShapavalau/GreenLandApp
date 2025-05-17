<?php

declare(strict_types=1);

namespace App\DTO\Plant;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePlantApiSchema
{
    /**
     * @param \DateTimeImmutable|null $adoptionDate
     * @param string|null $image
     * @param string $nickname
     * @param string $name
     * @param string|null $description
     * @param string $location
     * @param string $type
     */
    public function __construct(
        #[Assert\NotBlank(message: 'Plant adoption date should not be blank')]
        public ?\DateTimeImmutable $adoptionDate = null,
        public ?string $image = null,
        #[Assert\NotBlank(message: 'Plant nickname should not be blank')]
        public string $nickname = '',
        #[Assert\NotBlank(message: 'Plant name should not be blank')]
        public string $name = '',
        public ?string $description = null,
        #[Assert\NotBlank(message: 'Plant location should not be blank')]
        public string $location = '',
        #[Assert\NotBlank(message: 'Plant type should not be blank')]
        public string $type = ''
    ) {}
}
