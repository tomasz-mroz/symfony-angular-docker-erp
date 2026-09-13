<?php
namespace App\Application\Cqrs\Command\Employee;

use App\Application\Cqrs\UuidResource;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CreateEmployee implements UuidResource
{
    private UuidInterface $uuid;

    public ?string $email = null;
    public ?string $firstName = null;

    public function __construct()
    {
        $this->uuid = Uuid::uuid7();
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }
}
