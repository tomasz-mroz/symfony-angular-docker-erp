<?php


namespace App\Application\Service;


use App\Application\Common\ArrayUtils;
use App\Application\Serializer\SerializerContextCreator;
use App\Domain\PersistenceInterface;
use App\Domain\Repository\LogDeletedDataRepositoryInterface;
use App\Domain\Repository\LogRepositoryInterface;
use App\Entity\Employee;
use App\Entity\Log;
use App\Entity\LogDeletedData;
use App\Entity\User;
use JMS\Serializer\SerializerInterface;

class ChangeDetector
{

    private array $registered = [];

    public function __construct(
        private SerializerInterface $serializer,
        private SerializerContextCreator $serializerContextCreator,
        private LogRepositoryInterface $logRepository,
        private LogDeletedDataRepositoryInterface $logDeletedDataRepository,
        private ArrayUtils $arrayUtils,
        private PersistenceInterface $persistence,
    )
    {
    }

    public function registerBeforeChanges(object $object, mixed $id): void
    {
        $this->registered[get_class($object) . '_' . $id] = json_decode(
            $this->serializer->serialize(
                $object,
                'json',
                $this->serializerContextCreator->create(['Default', 'ChangeDetector'])
            ),
            true
        );
    }

    public function calculateDiff(object $object, mixed $id, ?User $user = null, ?string $note = null): void
    {
        $this->persistence->flush();
        $postUpdate = json_decode(
            $this->serializer->serialize(
                $object,
                'json',
                $this->serializerContextCreator->create(['Default', 'ChangeDetector']),
            ),
            true
        );

        $previous = $this->arrayUtils->arrayRecursiveDiff($this->registered[get_class($object) . '_' . $id], $postUpdate);
        $changes = $this->arrayUtils->arrayRecursiveDiff($postUpdate, $this->registered[get_class($object) . '_' . $id]);

        if (empty($previous) and empty($changes)) {
            return;
        }

        $this->logRepository->create(
            $this->solveObjectClass($object),
            $id,
            $previous,
            $changes,
            $user,
            $note
        );

        unset($this->registered[get_class($object) . '_' . $id]);
    }

    public function createLogNote(object $object, mixed $id, ?User $user = null, ?string $note = null): void
    {
        $this->logRepository->create(
            $this->solveObjectClass($object),
            $id,
            [],
            [],
            $user,
            $note
        );
    }

    private function solveObjectClass(object $object): string
    {
        return match (true) {
            $object instanceof Employee => Log::ENTITY_EMPLOYEE,


        };
    }

    public function createDeletedLogNote(object $object, mixed $id, ?User $user = null, ?string $note = null): void
    {
        $this->logDeletedDataRepository->create(
            $this->solveObjectDeletedLogClass($object),
            $id,
            $user,
            $note
        );
    }
    public function deletedDeletedLogNote(mixed $id): void
    {
        $this->logDeletedDataRepository->delete(
            $id
        );
    }
    private function solveObjectDeletedLogClass(object $object): string
    {
        return match (true) {
            $object instanceof Employee => LogDeletedData::ENTITY_EMPLOYEE,

        };
    }
}
