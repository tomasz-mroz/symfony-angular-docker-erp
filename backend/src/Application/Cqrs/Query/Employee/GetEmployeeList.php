<?php

namespace App\Application\Cqrs\Query\Employee;

 use App\Application\Cqrs\QueryParams\Employee\GetEmployeeListParams;
 use App\Application\Cqrs\QueryResult\CountedResult;
 use App\Domain\Repository\EmployeeRepositoryInterface;
 use App\Infrastructure\Doctrine\Filter\Filters;

class GetEmployeeList
{
     public function __construct(
         private EmployeeRepositoryInterface $employeeRepository,
     ) {
     }

     public function query(GetEmployeeListParams $params): CountedResult|array
     {
//         $userPermissions = $params->getUserPermissions();

         if ($params->isDisableCount()) {
             return $this->employeeRepository->getList(
                 $params->getFilters(),
                 $params->getOffset(),
                 $params->getPageSize(),
             );
         }

         return new CountedResult(
             $this->employeeRepository->count(
                 $params->getFilters(),
             ),
             $this->employeeRepository->getList(
                 $params->getFilters(),
                 $params->getOffset(),
                 $params->getPageSize(),
             )
         );
     }
}
