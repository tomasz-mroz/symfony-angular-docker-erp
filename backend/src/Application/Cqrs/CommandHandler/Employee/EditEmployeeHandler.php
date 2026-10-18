<?php

namespace App\Application\Cqrs\CommandHandler\Employee;

use App\Application\Cqrs\Command\Employee\EditEmployee;
use App\Domain\PersistenceInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class EditEmployeeHandler
{
    public function __construct(
        private PersistenceInterface $persistence,
    ) {
    }

    public function __invoke(EditEmployee $command): void
    {
        $employee = $command->getEmployee();
        $employee->setFirstName($command->getFirstName());
        $employee->setLastName($command->getLastName());
        $employee->setEmail($command->getEmail());
        $employee->setRole($command->getRole());

        $this->persistence->persist($employee);
        $this->persistence->flush();
    }
}
