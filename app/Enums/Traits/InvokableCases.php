<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use BackedEnum;
use Exception;

trait InvokableCases
{
    public function __invoke(): int|string
    {
        return $this instanceof BackedEnum ? $this->value : $this->name;
    }

    /**
     * @throws Exception
     */
    public static function __callStatic($name, $args): int|string
    {
        $cases = static::cases();

        foreach ($cases as $case) {
            if ($case->name === $name) {
                return $case instanceof BackedEnum ? $case->value : $case->name;
            }
        }

        throw new Exception(static::class, $name);
    }
}
