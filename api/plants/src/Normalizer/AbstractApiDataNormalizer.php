<?php

declare(strict_types=1);

namespace App\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

abstract class AbstractApiDataNormalizer implements NormalizerAwareInterface, NormalizerInterface
{
    use NormalizerAwareTrait;

    public const CONTEXT_TYPE_KEY = null;
    public const DEFAULT_TYPE = null;

    /**
     * @param array $context
     * @return string|null
     */
    final public function getType(array $context): ?string
    {
        if (!static::CONTEXT_TYPE_KEY) {
            return null;
        }

        return $context[static::CONTEXT_TYPE_KEY] ?? static::DEFAULT_TYPE;
    }
}
