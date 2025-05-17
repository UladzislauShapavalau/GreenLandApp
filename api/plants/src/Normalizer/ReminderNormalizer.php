<?php

declare(strict_types=1);

namespace App\Normalizer;

use App\Entity\Reminder;

final class ReminderNormalizer extends AbstractApiDataNormalizer
{
    public const CONTEXT_TYPE_KEY = '_reminder';
    public const DEFAULT_TYPE = '_reminder.default';
    public const ID_ONLY_TYPE = '_reminder.id';
    public const IN_PLANT_TYPE = '_reminder.in_plant';

    /**
     * @param mixed $data
     * @param null $format
     * @param array $context
     * @return bool
     */
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof Reminder;
    }

    /**
     * @param string|null $format
     * @return array
     */
    public function getSupportedTypes(?string $format): array
    {
        return [Reminder::class => true];
    }

    /**
     * @param Reminder $object
     * @param string|null $format
     * @param array $context
     * @return float|int|bool|\ArrayObject|array|string|null
     */
    public function normalize($object, $format = null, array $context = []): float|int|bool|\ArrayObject|array|string|null
    {
        return match ($this->getType($context)) {
            self::ID_ONLY_TYPE => $object->getId()->toRfc4122(),
            self::IN_PLANT_TYPE => [
                'id' => $object->getId()->toRfc4122(),
                'cycle' => $object->getCycle(),
                'startExecution' => $object->getStartExecution()->format('Y-m-d H:i:s'),
                'type' => $this->normalizer->normalize($object->getReminderType(), $format, $context),
            ],
            default => [
                'id' => $object->getId()->toRfc4122(),
                'cycle' => $object->getCycle(),
                'startExecution' => $object->getStartExecution()->format('Y-m-d H:i:s'),
                'user' => $this->normalizer->normalize($object->getOwner(), $format, $context),
                'type' => $this->normalizer->normalize($object->getReminderType(), $format, $context),
                'plant' => $this->normalizer->normalize($object->getPlant(), $format, $context),
            ],
        };
    }
}
