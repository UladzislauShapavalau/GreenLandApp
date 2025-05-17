<?php

declare(strict_types=1);

namespace App\DTO\Reminder;

use Symfony\Component\Validator\Constraints as Assert;

class CreateReminderApiSchema
{
    /**
     * @param string $plant
     * @param string $type
     * @param \DateTimeImmutable|null $startExecution
     * @param int $cycle
     */
    public function __construct(
        #[Assert\NotBlank(message: 'Reminder plant should not be blank')]
        public string $plant = '',
        #[Assert\NotBlank(message: 'Reminder type should not be blank')]
        public string $type = '',
        #[Assert\NotBlank(message: 'Reminder start execution should not be blank')]
        public ?\DateTimeImmutable $startExecution = null,
        #[Assert\Positive(message: 'Reminder cycle should be positive')]
        public int $cycle = 0
    ) {}
}
