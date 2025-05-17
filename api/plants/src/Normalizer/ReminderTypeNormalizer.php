<?php

declare(strict_types=1);

namespace App\Normalizer;

use App\Entity\ReminderType;

final class ReminderTypeNormalizer extends AbstractApiDataNormalizer
{
    public const CONTEXT_TYPE_KEY = '_reminder_type';
    public const DEFAULT_TYPE = '_reminder_type.default';
    public const ID_ONLY_TYPE = '_reminder_type.id';

    /**
     * @param mixed $data
     * @param null $format
     * @param array $context
     * @return bool
     */
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof ReminderType;
    }

    /**
     * @param string|null $format
     * @return array
     */
    public function getSupportedTypes(?string $format): array
    {
        return [ReminderType::class => true];
    }

    /**
     * @param ReminderType $object
     * @param string|null $format
     * @param array $context
     * @return array
     * @return float|int|bool|\ArrayObject|array|string|null
     */
    public function normalize($object, $format = null, array $context = []): float|int|bool|\ArrayObject|array|string|null
    {
        return match ($this->getType($context)) {
            self::ID_ONLY_TYPE => $object->getId()->toRfc4122(),
            default => [
                'id' => $object->getId()->toRfc4122(),
                'name' => $object->getName(),
            ],
        };
    }
}
