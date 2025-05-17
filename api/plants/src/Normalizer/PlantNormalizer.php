<?php

declare(strict_types=1);

namespace App\Normalizer;

use App\Entity\Plant;

final class PlantNormalizer extends AbstractApiDataNormalizer
{
    public const CONTEXT_TYPE_KEY = '_plant';
    public const DEFAULT_TYPE = '_plant.default';
    public const ID_ONLY_TYPE = '_plant.id';
    public const IN_LIST_TYPE = '_plant.in_list';

    /**
     * @param mixed $data
     * @param null $format
     * @param array $context
     * @return bool
     */
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof Plant;
    }

    /**
     * @param string|null $format
     * @return array
     */
    public function getSupportedTypes(?string $format): array
    {
        return [Plant::class => true];
    }

    /**
     * @param Plant $object
     * @param string|null $format
     * @param array $context
     * @return float|int|bool|\ArrayObject|array|string|null
     */
    public function normalize($object, $format = null, array $context = []): float|int|bool|\ArrayObject|array|string|null
    {
        return match ($this->getType($context)) {
            self::ID_ONLY_TYPE => $object->getId()->toRfc4122(),
            self::IN_LIST_TYPE => [
                'id' => $object->getId()->toRfc4122(),
                'name' => $object->getName(),
                'adoption_date' => $object->getAdoptionDate()->format('Y-m-d'),
                'description' => $object->getDescription(),
                'image' => is_resource($object->getImage()) ? stream_get_contents($object->getImage()) : $object->getImage(),
                'nickname' => $object->getNickname(),
                'user' => $this->normalizer->normalize($object->getOwner(), $format, $context),
                'location' => $this->normalizer->normalize($object->getLocation(), $format, $context),
                'type' => $this->normalizer->normalize($object->getPlantType(), $format, $context),
            ],
            default => [
                'id' => $object->getId()->toRfc4122(),
                'name' => $object->getName(),
                'adoption_date' => $object->getAdoptionDate()->format('Y-m-d'),
                'description' => $object->getDescription(),
                'image' => is_resource($object->getImage()) ? stream_get_contents($object->getImage()) : $object->getImage(),
                'nickname' => $object->getNickname(),
                'user' => $this->normalizer->normalize($object->getOwner(), $format, $context),
                'location' => $this->normalizer->normalize($object->getLocation(), $format, $context),
                'type' => $this->normalizer->normalize($object->getPlantType(), $format, $context),
                'reminders' => $this->normalizer->normalize($object->getReminders(), $format, $context),
            ],
        };
    }
}
