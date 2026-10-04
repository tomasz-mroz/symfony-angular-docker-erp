<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

/**
 * @extends ServiceEntityRepository<Employee>
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function save(Employee $employee, bool $flush = false): void
    {
        $this->getEntityManager()->persist($employee);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Employee $employee, bool $flush = false): void
    {
        $this->getEntityManager()->remove($employee);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByUuid(UuidInterface $uuid): ?Employee
    {
        return $this->find($uuid);
    }

    public function findByEmail(string $email): ?Employee
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * @return Employee[]
     */
    public function findAllOrderedByLastName(): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.lastName', 'ASC')
            ->addOrderBy('e.firstName', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
