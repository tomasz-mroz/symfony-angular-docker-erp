<?php

namespace App\Application\Cqrs\CommandHandler\Employee;

use App\Application\Cqrs\Command\Employee\EditEmployee;
use App\Domain\PersistenceInterface;
use App\Repository\EmployeeRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class EditEmployeeHandler
{
    public function __construct(
        private PersistenceInterface $persistence,
        private EmployeeRepository $employeeRepository,
    ) {}

    public function __invoke(EditEmployee $command): void
    {
        $employee = $this->employeeRepository->findOneBy(['uuid' => $command->getUuid()]);

        if (!$employee) {
            throw new NotFoundHttpException('Pracownik o podanym UUID nie istnieje.');
        }

        $employee->setFirstName($command->getFirstName());
        $employee->setLastName($command->getLastName());
        $employee->setEmail($command->getEmail());
        $employee->setRole($command->getRole());
        $this->persistence->flush();
    }
}
