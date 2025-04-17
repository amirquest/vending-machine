<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Repositories\Concerns\Filterable;
use App\Repositories\Contracts\AbstractRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class AdminRepository extends AbstractRepository
{
    use Filterable;

    public function findByMobile(string $mobile): Model|Admin|null
    {
        return $this->getQuery()
            ->where('mobile', $mobile)
            ->first();
    }

    public function getWithPermissionsByGuardName(string $guard = 'admin'): Collection
    {
        return $this->getQuery()
            ->with(['permissions' => function ($query) use ($guard) {
                $query->where('guard_name', $guard);
            }])
            ->orderBy('name')
            ->get(['id', 'name', 'family'])
            ->mapWithKeys(fn(Admin $admin) => [
                $admin->name => [
                    'admin_id' => $admin->id,
                    'permissions' => $admin->permissions->pluck('id'),
                ],
            ]);
    }

    protected function instance(array $attributes = []): Admin
    {
        return new Admin($attributes);
    }
}
