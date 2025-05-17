<?php

declare(strict_types=1);

namespace App\DTO\Location;

use Symfony\Component\Validator\Constraints as Assert;

class CreateLocationApiSchema
{
    /**
     * @param string $name
     */
    public function __construct(
        #[Assert\NotBlank]
        public string $name = ''
    ) {}
}
