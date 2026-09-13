<?php

namespace App\Application\Response;

use App\Application\Cqrs\UuidResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class UuidResponse extends JsonResponse
{
    public function __construct(UuidResource $uuidResource, int $status = 200, array $headers = [], bool $json = false)
    {
        parent::__construct([
            'uuid' => $uuidResource->getUuid()->toString(),
        ], $status, $headers, $json);
    }
}
