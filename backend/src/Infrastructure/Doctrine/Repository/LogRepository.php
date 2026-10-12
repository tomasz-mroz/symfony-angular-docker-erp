<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Repository\LogRepositoryInterface;
use App\Entity\Log;
use App\Entity\LogToday;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

final class LogRepository implements LogRepositoryInterface
{
    /** @var EntityRepository<Log> */
    private readonly EntityRepository $repository;

    /** @var EntityRepository<LogToday> */
    private readonly EntityRepository $repositoryToday;

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EntityManagerInterface $emToday,
    ) {
        $this->repository      = $em->getRepository(Log::class);
        $this->repositoryToday = $emToday->getRepository(LogToday::class);
    }

    /**
     * @param array<string, mixed> $previous
     * @param array<string, mixed> $changes
     */
    public function create(
        string $entity,
        string $entityId,
        array $previous,
        array $changes,
        ?User $user = null,
        ?string $note = null,
    ): void {
        $log = new LogToday();
        $log->setEntity($entity);
        $log->setEntityId($entityId);
        $log->setPrevious($previous);
        $log->setChanges($changes);
        $log->setUser($user);
        $log->setNote($note);

        $this->emToday->persist($log);
        $this->emToday->flush();
    }

    /**
     * @return list<Log|LogToday>
     */
    public function getList(
        int $offset,
        int $pageSize,
        string $entity,
        string $entityId,
    ): array {
        $logs = $this->fetchLogs($this->repository, $entity, $entityId, $offset, $pageSize);
        $logsToday = $this->fetchLogs($this->repositoryToday, $entity, $entityId, $offset, $pageSize);

        $combined = [...$logs, ...$logsToday];

        usort(
            $combined,
            static fn (Log|LogToday $a, Log|LogToday $b): int
            => $b->getCreatedAt() <=> $a->getCreatedAt(),
        );

        return $combined;
    }

    public function count(string $entity, string $entityId): int
    {
        return $this->countFor($this->repository, $entity, $entityId)
            + $this->countFor($this->repositoryToday, $entity, $entityId);
    }

    public function sendLogTodayToLog(): void
    {
        $this->em->getConnection()->executeStatement(
            <<<'SQL'
                INSERT INTO log (user_id, entity, entity_id, created_at, changes, previous, note)
                SELECT user_id, entity, entity_id, created_at, changes, previous, note
                FROM log_today
            SQL,
        );
    }

    public function deleteLogToday(): void
    {
        $this->emToday->getConnection()->executeStatement('DELETE FROM log_today');
    }

    /**
     * @param EntityRepository<Log|LogToday> $repo
     * @return list<Log|LogToday>
     */
    private function fetchLogs(
        EntityRepository $repo,
        string $entity,
        string $entityId,
        int $offset,
        int $pageSize,
    ): array {
        return $repo->createQueryBuilder('l')
            ->where('l.entity = :entity')
            ->andWhere('l.entityId = :entityId')
            ->setParameter('entity', $entity)
            ->setParameter('entityId', $entityId)
            ->orderBy('l.createdAt', 'DESC')
            ->setMaxResults($pageSize)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param EntityRepository<Log|LogToday> $repo
     */
    private function countFor(
        EntityRepository $repo,
        string $entity,
        string $entityId,
    ): int {
        return (int) $repo->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->where('l.entity = :entity')
            ->andWhere('l.entityId = :entityId')
            ->setParameter('entity', $entity)
            ->setParameter('entityId', $entityId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
