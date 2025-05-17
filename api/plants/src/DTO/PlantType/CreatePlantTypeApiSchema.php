<?php

declare(strict_types=1);

namespace App\DTO\PlantType;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePlantTypeApiSchema
{
    /**
     * @param string $name
     */
    public function __construct(
        #[Assert\NotBlank(message: 'Plant type name should not be blank')]
        public string $name = ''
    ) {}
}
