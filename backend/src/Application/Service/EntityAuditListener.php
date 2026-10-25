<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\Employee;
use App\Entity\Log;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsDoctrineListener(event: Events::preUpdate)]
class EntityAuditListener
{
    public function __construct(
        private readonly Security $security
    ) {}

    public function preUpdate(PreUpdateEventArgs $event): void
    {
        $entity = $event->getObject();

        $entityType = match (true) {
            $entity instanceof Employee => Log::ENTITY_EMPLOYEE,
            default => null,
        };

        if ($entityType === null) {
            return;
        }

        $changeSet = $event->getEntityChangeSet();
        if (empty($changeSet)) {
            return;
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

        $em = $event->getObjectManager();
        $em->persist($log);
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
