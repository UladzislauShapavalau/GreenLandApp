<?php

declare(strict_types=1);

namespace App\Normalizer;

use App\Helper\ArrayHelper;
use Doctrine\Common\Collections\Collection;

final class CollectionNormalizer extends AbstractApiDataNormalizer
{
    /**
     * @param mixed $data
     * @param null $format
     * @param array $context
     * @return bool
     */
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof Collection;
    }

    /**
     * @param string|null $format
     * @return array
     */
    public function getSupportedTypes(?string $format): array
    {
        return [Collection::class => true];
    }

    /**
     * @param Collection $object
     * @param string|null $format
     * @param array $context
     * @return float|int|bool|\ArrayObject|array|string|null
     */
    public function normalize($object, $format = null, array $context = []): float|int|bool|\ArrayObject|array|string|null
    {
        return ArrayHelper::map($object, fn($item) => $this->normalizer->normalize($item, $format, $context));
    }
}
