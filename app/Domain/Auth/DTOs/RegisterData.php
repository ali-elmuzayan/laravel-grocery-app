<?php

namespace App\Domain\Auth\DTOs;

class RegisterData
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $name, 
        public string $email, 
        public string $password
    ){}    

    /** 
     * Get the data from an array  
     */
    public static function fromArray(array $data): self 
    {
        return new self($data['name'], $data['email'], $data['password']);
    }
}
