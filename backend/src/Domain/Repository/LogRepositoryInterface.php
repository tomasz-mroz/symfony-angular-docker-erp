<?php


namespace App\Domain\Repository;


use App\Entity\User;
use App\Infrastructure\Doctrine\Filter\Filters;

interface LogRepositoryInterface
{

    public function create(
        string $entity,
        string $entityId,
        array $previous,
        array $changes,
        ?User $user = null,
        ?string $note = null
    ): void;

    public function getList(
        int $offset,
        int $pageSize,
        string $entity,
        string $entityId
    ): array;

    public function count(
        string $entity,
        string $entityId
    ): int;

    public function sendLogTodayToLog(): void;

    public function deleteLogToday(): void;
}
