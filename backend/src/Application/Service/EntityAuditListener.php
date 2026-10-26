<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\Employee;
use App\Entity\Log;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsDoctrineListener(event: Events::onFlush)]
class EntityAuditListener
{
    public function __construct(
        private readonly Security $security
    ) {}

    public function onFlush(OnFlushEventArgs $event): void
    {
        $em = $event->getObjectManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $entityType = match (true) {
                $entity instanceof Employee => Log::ENTITY_EMPLOYEE,
                default => null,
            };

            if ($entityType === null) {
                continue;
            }

            $changeSet = $uow->getEntityChangeSet($entity);
            if (empty($changeSet)) {
                continue;
            }

            $previous = [];
            $changes = [];

            foreach ($changeSet as $field => [$oldValue, $newValue]) {
                $previous[$field] = $this->normalizeValue($oldValue);
                $changes[$field] = $this->normalizeValue($newValue);
            }

            $user = $this->security->getUser();

            $log = new Log();
            $log->setEntity($entityType);
            $log->setEntityId((string) $entity->getUuid());
            $log->setPrevious($previous);
            $log->setChanges($changes);
            $log->setUser($user instanceof User ? $user : null);
            $log->setNote(null);

            $em->persist($log);

            $classMetadata = $em->getClassMetadata(Log::class);
            $uow->computeChangeSet($classMetadata, $log);
        }
    }

    private function normalizeValue(mixed $value): mixed
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_object($value)) {
            return method_exists($value, 'getId') ? $value->getId() : (string) $value;
        }

        return $value;
    }
}
