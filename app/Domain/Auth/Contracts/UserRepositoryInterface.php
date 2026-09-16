<?php

namespace App\Domain\Auth\Contracts;

use App\Domain\Auth\DTOs\RegisterData;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(RegisterData $data): User; 

    public function findByEmail(string $email): ?User; 

    public function update(User $user): void; 

    public function findById(int $id): ?User; 

    public function existsByEmail(string $email): bool; 
}
