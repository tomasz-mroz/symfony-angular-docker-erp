<?php


namespace App\Domain\Repository;


 use App\Entity\Employee;
 use App\Domain\Exception\EntityNotFoundException;
 use App\Infrastructure\Doctrine\Filter\Filters;

interface EmployeeRepositoryInterface
{

     /**
      * @param string $uuid
      * @return Employee
      * @throws EntityNotFoundException
      */
     public function find(string $uuid): Employee;


     public function getList(
         Filters $filters,
         int $offset,
         int $pageSize,
     ): array;

     public function count(
         Filters $filters,
     ): int;
}
