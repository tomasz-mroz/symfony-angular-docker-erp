<?php


namespace App\Application\Cqrs\Command\Employee;

use AllowDynamicProperties;
use App\Entity\Employee;
use App\Entity\User;

#[AllowDynamicProperties]
class EditEmployee extends CreateEmployee
{

    public function __construct(private Employee $employee, private ?User $user)
    {
        parent::__construct($this->user);

        $this->uuid = (string) $employee->getUuid();
    }
    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @return Employee
     */
    public function getEmployee(): Employee
    {
        return $this->employee;
    }
}
