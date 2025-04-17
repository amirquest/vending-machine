<?php

namespace Tests\Concerns;

use App\Enums\Permission\RoleEnum;
use App\Models\Admin;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Sanctum;
use Throwable;

trait ActingAsTrait
{
    private ?UserContract $loggedIn = null;

    protected array $availableActs = [Admin::class, User::class];

    /**
     * @throws Exception|Throwable
     */

    public function actingAsAdmin(
        bool $create = true,
        array|Model|Factory $attributes = [],
        array $abilities = ['*'],
    ): UserContract|HasApiTokens {
        /** @var Admin $admin */
        $admin = $this->loginWith(Admin::class, 'admin', $create, $attributes, $abilities);

        $admin->assignRole(RoleEnum::SUPER_ADMIN());

        return $admin;
    }

    /**
     * @throws Exception|Throwable
     */
    public function actingAsUser(
        bool $create = false,
        array|Model|Factory $attributes = [],
        array $abilities = ['*'],
    ): UserContract|HasApiTokens {
        return $this->loginWith(User::class, 'user', $create, $attributes, $abilities);
    }

    public function getLoggedIn(): ?UserContract
    {
        return $this->loggedIn;
    }

    public function getLoggedInId(): int|string
    {
        return $this->loggedIn->getAuthIdentifier();
    }

    /**
     * @throws Exception|Throwable
     */
    public function loginWith(
        string $userClass,
        ?string $guard = null,
        bool $create = false,
        array|Model|Factory $attributes = [],
        array $abilities = ['*']
    ): UserContract|HasApiTokens {
        throw_if(
            !in_array($userClass, $this->availableActs),
            Exception::class,
            'There is no valid user.'
        );

        if ($attributes instanceof Model) {
            $user = $attributes;
        } elseif ($attributes instanceof Factory) {
            $user = $create ? $attributes->create() : $attributes->make();
        } else {
            $user = $create ?
                $userClass::factory()->create($attributes) :
                $userClass::factory()->make($attributes);
        }

        $this->loggedIn = $user;

        return Sanctum::actingAs($user, $abilities, $guard);
    }
}
