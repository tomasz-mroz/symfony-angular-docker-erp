<?php

namespace App\Application\Cqrs;

use Ramsey\Uuid\UuidInterface;

interface UuidResource
{
    public function getUuid(): UuidInterface;
}
