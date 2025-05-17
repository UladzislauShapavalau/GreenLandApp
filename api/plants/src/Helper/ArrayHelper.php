<?php

declare(strict_types=1);

namespace App\Helper;

use Doctrine\Common\Collections\Collection;

final class ArrayHelper
{
    /**
     * @param Collection|array $data
     * @param callable $callback
     * @return array
     */
    public static function map(mixed $data, callable $callback): array
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }

        return array_map($callback, $data);
    }

    /**
     * @param Collection|array $data
     * @param callable $callback
     * @return array
     */
    public static function filter(mixed $data, callable $callback): array
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }
        return array_values(array_filter($data, $callback));
    }
}
