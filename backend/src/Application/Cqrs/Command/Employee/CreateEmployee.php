<?php

namespace App\Application\Cqrs\Command\Employee;

use App\Application\Cqrs\UuidResource;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CreateEmployee implements UuidResource
{
    private UuidInterface $uuid;

    private ?string $email = null;
    private ?string $firstName = null;
    private ?string $lastName = null;
    private ?string $role = null;

    public function __construct(?UuidInterface $uuid = null)
    {
        $this->uuid = $uuid ?? Uuid::uuid4();
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): void
    {
        $this->role = $role;
    }
}
