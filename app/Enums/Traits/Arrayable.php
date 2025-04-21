<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use BackedEnum;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait Arrayable
{
    public static function toArray(): array
    {
        $cases = static::cases();

        return isset($cases[0]) && $cases[0] instanceof BackedEnum
            ? array_column($cases, 'value', 'name')
            : array_column($cases, 'name');
    }

    public static function except(array|string $values): array
    {
        $values = Arr::wrap($values);
        $array = static::toArray();

        foreach ($values as $value) {
            unset($array[array_search($value, $array)]);
        }

        return array_values($array);
    }

    public static function count(): ?int
    {
        return count(static::toArray());
    }

    public static function toCollect(): Collection
    {
        return collect(static::toArray());
    }
}
