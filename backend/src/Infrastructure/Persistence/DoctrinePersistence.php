<?php

namespace App\Infrastructure\Persistence;

use App\Domain\PersistenceInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrinePersistence implements PersistenceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function persist($object): void
    {
        $this->entityManager->persist($object);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function remove($object): void
    {
        $this->entityManager->remove($object);
    }

    public function clear(): void
    {
        $this->entityManager->clear();
    }

    public function refresh($object): void
    {
        $this->entityManager->refresh($object);
    }

    public function resetManager(): void
    {
        $this->entityManager->clear();
    }

    public function beginTransaction(): void
    {
        $this->entityManager->beginTransaction();
    }

    public function commit(): void
    {
        $this->entityManager->commit();
    }

    public function rollback(): void
    {
        $this->entityManager->rollback();
    }

    public function contains(mixed $object): bool
    {
        return $this->entityManager->contains($object);
    }

    public function detach(...$objects): void
    {
        foreach ($objects as $object) {
            $this->entityManager->detach($object);
        }
    }

    public function getReference(string $class, mixed $id): object
    {
        return $this->entityManager->getReference($class, $id);
    }

    public function isOpen(): bool
    {
        return $this->entityManager->isOpen();
    }
}
