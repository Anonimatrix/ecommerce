<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Xkairo\CacheRepositoryLaravel\Repositories\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function authenticated(): User|null;

    public function login(User $user);

    public function getByEmail(string $email);

    public function setMissingInfo(array $info);
}
