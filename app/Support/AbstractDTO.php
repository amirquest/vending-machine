<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use JsonSerializable;
use RuntimeException;

abstract class AbstractDTO implements JsonSerializable
{
    protected const int DEFAULT_MODE = 0;
    protected const int SNAKE_MODE = 1;
    protected const int STUDLY_MODE = 2;
    protected const int PASCAL_MODE = 3;

    public static function make(...$parameters): static
    {
        return new static(...$parameters);
    }

    public static function fromArray(array $array): static
    {
        return new static(...$array);
    }

    public static function fromJson(string $string): static
    {
        return new static(...json_decode($string, true));
    }

    public static function fromObject(object|array $obj): static
    {
        $obj = is_object($obj) ? $obj : (object)$obj;
        $params = [];

        foreach (get_class_vars(get_called_class()) as $var => $value) {
            $var = match (get_called_class()::objectVarMode()) {
                self::SNAKE_MODE => Str::snake($var),
                self::STUDLY_MODE => Str::studly($var),
                self::PASCAL_MODE => Str::ucfirst($var),
                default => $var
            };

            $params[] = $obj->{$var} ?? null;
        }

        return new static(...$params);
    }

    public static function fromRequest(Request $request): static
    {
        return self::fromObject($request);
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function toJson(int $options = 0): false|string
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Json Is not valid for encoding.');
        }

        return $json;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function dump(): static
    {
        dump($this->toArray());

        return $this;
    }

    public function dd(): never
    {
        $this->dump();

        exit(1);
    }

    public static function objectVarMode(): int
    {
        return self::DEFAULT_MODE;
    }
}
