<?php

declare(strict_types=1);

namespace App\Normalizer;

use App\Entity\Location;

final class LocationNormalizer extends AbstractApiDataNormalizer
{
    public const CONTEXT_TYPE_KEY = '_location';
    public const DEFAULT_TYPE = '_location.default';
    public const ID_ONLY_TYPE = '_location.id';

    /**
     * @param mixed $data
     * @param null $format
     * @param array $context
     * @return bool
     */
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof Location;
    }

    /**
     * @param string|null $format
     * @return array
     */
    public function getSupportedTypes(?string $format): array
    {
        return [Location::class => true];
    }

    /**
     * @param Location $object
     * @param string|null $format
     * @param array $context
     * @return float|int|bool|\ArrayObject|array|string|null
     */
    public function normalize($object, $format = null, array $context = []): float|int|bool|\ArrayObject|array|string|null
    {
        return match ($this->getType($context)) {
            self::ID_ONLY_TYPE => $object->getId()->toRfc4122(),
            default => [
                'id' => $object->getId()->toRfc4122(),
                'name' => $object->getName(),
                'user' => $this->normalizer->normalize($object->getOwner(), $format, $context),
            ],
        };
    }
}
