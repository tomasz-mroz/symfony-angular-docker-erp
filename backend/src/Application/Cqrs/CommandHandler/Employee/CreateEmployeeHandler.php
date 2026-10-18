<?php

namespace App\Application\Cqrs\CommandHandler\Employee;

use App\Application\Cqrs\Command\Employee\CreateEmployee;
use App\Domain\PersistenceInterface;
use App\Entity\Employee;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateEmployeeHandler
{
    public function __construct(
        private PersistenceInterface $persistence,
    ) {
    }

    public function __invoke(CreateEmployee$command): void
    {
        $employee = new Employee();
        $employee->setUuid($command->getUuid());
        $employee->setFirstName($command->getFirstName());
        $employee->setLastName($command->getLastName());
        $employee->setEmail($command->getEmail());
        $employee->setRole($command->getRole());

        $this->persistence->persist($employee);
        $this->persistence->flush();
    }
}
