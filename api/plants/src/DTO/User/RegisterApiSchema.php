<?php

declare(strict_types=1);

namespace App\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterApiSchema
{
    /**
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function __construct(
        #[Assert\NotBlank(message: 'User name should not be blank')]
        public string $name = '',
        #[Assert\NotBlank(message: 'Email should not be blank')]
        #[Assert\Email(message: 'Email is not valid')]
        public string $email = '',
        #[Assert\NotBlank(message: 'Password should not be blank')]
        public string $password = ''
    ) {}
}
