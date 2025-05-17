<?php

declare(strict_types=1);

namespace App\DTO\ReminderType;

use Symfony\Component\Validator\Constraints as Assert;

class CreateReminderTypeApiSchema
{
    /**
     * @param string $name
     */
    public function __construct(
        #[Assert\NotBlank(message: 'Reminder type name should not be blank')]
        public string $name = ''
    ) {}
}
