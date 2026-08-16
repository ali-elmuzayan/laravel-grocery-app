<?php

namespace App\Domain\Auth\DTOs;

class LoginData
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $email, 
        public string $password
    ){}    

    /** 
     * Get the data from an array  
     */
    public static function fromArray(array $data): self 
    {
        return new self($data['email'], $data['password']);
    }
}
