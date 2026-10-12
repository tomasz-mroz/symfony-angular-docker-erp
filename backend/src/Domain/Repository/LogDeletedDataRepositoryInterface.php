<?php


namespace App\Domain\Repository;


use App\Entity\User;

interface LogDeletedDataRepositoryInterface
{

    public function create(
        string $entity,
        string $entityId,
        User $user = null,
        string $note = null
    ): void;

    public function delete(string $entityId) : void;
}
