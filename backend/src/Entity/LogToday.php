<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: 'log_today')]
#[ORM\Index(name: 'idx_log_today_entity', columns: ['entity', 'entity_id'])]
class LogToday
{
    public const ENTITY_PROJECT = 'project';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: true)]
    private ?User $user = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'string')]
    #[Serializer\Exclude]
    private string $entity;

    #[ORM\Column(type: 'string')]
    private string $entityId;

    #[ORM\Column(type: 'json')]
    #[Serializer\Exclude]
    private array $previous = [];

    #[ORM\Column(type: 'json')]
    #[Serializer\Exclude]
    private array $changes = [];

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $note = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getEntity(): string
    {
        return $this->entity;
    }

    public function setEntity(string $entity): void
    {
        $this->entity = $entity;
    }

    public function getEntityId(): string
    {
        return $this->entityId;
    }

    public function setEntityId(string $entityId): void
    {
        $this->entityId = $entityId;
    }

    public function getPrevious(): array
    {
        return $this->previous;
    }

    public function setPrevious(array $previous): void
    {
        $this->previous = $previous;
    }

    public function getChanges(): array
    {
        return $this->changes;
    }

    public function setChanges(array $changes): void
    {
        $this->changes = $changes;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): void
    {
        $this->note = $note;
    }
}
