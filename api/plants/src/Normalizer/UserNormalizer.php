<?php

declare(strict_types=1);

namespace App\Normalizer;

use App\Entity\User;

final class UserNormalizer extends AbstractApiDataNormalizer
{
    public const CONTEXT_TYPE_KEY = '_user';
    public const DEFAULT_TYPE = '_user.default';
    public const ID_ONLY_TYPE = '_user.id';

    /**
     * @param mixed $data
     * @param null $format
     * @param array $context
     * @return bool
     */
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof User;
    }

    /**
     * @param string|null $format
     * @return array
     */
    public function getSupportedTypes(?string $format): array
    {
        return [User::class => true];
    }

    /**
     * @param User $object
     * @param ?string $format
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
                'email' => $object->getEmail(),
                'roles' => $object->getRoles(),
                'creationDate' => $object->getCreationDate()->format('Y-m-d H:i:s'),
            ],
        };
    }
}
