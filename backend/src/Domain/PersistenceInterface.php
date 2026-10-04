<?php


namespace App\Domain;


interface PersistenceInterface
{
    public function persist($object): void;

    public function flush(): void;

    public function remove($object): void;

    public function clear();

    public function refresh($object): void;

    public function resetManager(): void;

    public function beginTransaction(): void;

    public function commit(): void;

    public function rollback(): void;

    public function contains(mixed $object): bool;

    public function detach(...$objects): void;

    public function getReference(string $class, mixed $id): object;

    public function isOpen(): bool;
}
