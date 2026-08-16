<?php

namespace App\Domain\Auth\Repositories;
use App\Domain\Auth\Contracts\UserRepositoryInterface;
use App\Domain\Auth\DTOs\RegisterData;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function create(RegisterData $data): User 
    {
        return User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => $data->password,
        ]);
    }


    public function findById(int $id): ?User 
    {
        return User::query()->find($id);
    }

    public function findByEmail(string $email): ?User 
    {
        return User::query()->where('email', $email)->first();
    }

    public function update(User $user): void 
    {
        $user->save();
    }

    public function existsByEmail(string $email): bool 
    {
        return User::query()->where('email', $email)->exists();
    }
}
