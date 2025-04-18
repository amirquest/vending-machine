<?php

namespace App\Services\CauserResolver;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Factory as AuthManager;
use Throwable;

class CauserResolverService
{
    public function __construct(protected AuthManager $authManager)
    {
    }

    /**
     * @throws Throwable
     */
    public function resolve(Authenticatable|int|string|null $subject = null): ?Authenticatable
    {
        if ($subject instanceof Authenticatable) {
            return $subject;
        }

        if (is_null($subject)) {
            return $this->getDefaultCauser();
        }

        return $this->resolveUsingId($subject);
    }

    /**
     * @throws Throwable
     */
    protected function resolveUsingId(int|string $subject): Authenticatable
    {
        $guard = $this->authManager->guard();
        $provider = $guard->getProvider();
        $model = $provider->retrieveById($subject);

        throw_unless(
            $model instanceof Authenticatable,
            Exception::class,
            "Could not determine a user with identifier `{$subject}`."
        );

        return $model;
    }

    protected function getDefaultCauser(): ?Authenticatable
    {
        return $this->authManager->guard()->user();
    }
}
