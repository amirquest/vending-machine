<?php

use App\Services\Identifier\IdentifierService;

if (!function_exists('make_url')) {
    /**
     * @throws Exception
     */
    function make_url(string $baseUrl, string $path = '', array $queryParams = []): string
    {
        if (filter_var($baseUrl, FILTER_VALIDATE_URL) === false) {
            throw new Exception('BaseUrl is not valid!');
        }

        $fullUrl = trim($baseUrl, '/') . DIRECTORY_SEPARATOR . trim($path, '/');
        $httpQueryParams = http_build_query($queryParams);

        return sprintf('%s?%s', $fullUrl, $httpQueryParams);
    }
}

if (!function_exists('is_not_null')) {
    function is_not_null(mixed $value): bool
    {
        return !is_null($value);
    }
}

if (!function_exists('identifier')) {
    function identifier(?int $id = null): IdentifierService|int
    {
        if ($id === null) {
            return app(IdentifierService::class);
        }

        return app(IdentifierService::class)->generateIdentifier($id);
    }
}
