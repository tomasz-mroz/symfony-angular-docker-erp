<?php


namespace App\Infrastructure\Doctrine\Repository;


 use App\Entity\Employee;
 use App\Domain\Exception\EntityNotFoundException;
 use App\Domain\Repository\EmployeeRepositoryInterface;
 use App\Infrastructure\Doctrine\Filter\Filters;
 use Doctrine\ORM\EntityManagerInterface;
 use Doctrine\ORM\QueryBuilder;
 use Doctrine\Persistence\ObjectRepository;

class EmployeeRepository implements EmployeeRepositoryInterface
{
     private ObjectRepository $repository;

     public function __construct(
         private EntityManagerInterface $em,
     )
     {
         $this->repository = $em->getRepository(Employee::class);
     }

     public function find(string $uuid): Employee
     {
         $project = $this->repository->findOneBy([
             'uuid' => $uuid,
         ]);

         if (!$project) {
             throw new EntityNotFoundException();
         }

         return $project;
     }

     public function getList(
         Filters $filters,
         int     $offset,
         int     $pageSize,
     ): array
     {
         $qb = $this->em->createQueryBuilder();

         $qb
             ->select(['DISTINCT e'])
             ->from(Employee::class, 'e')
             ->setMaxResults($pageSize)
             ->setFirstResult($offset);
//             ->addOrderBy('p.createdAt', 'DESC');


         $filters->query($qb, 'e');

         return $qb->getQuery()->getResult();
     }

     public function count(
         Filters $filters,
     ): int
     {
         $qb = $this->em->createQueryBuilder();

         $qb
             ->select(['count(DISTINCT e)'])
             ->from(Employee::class, 'e');

         $filters->query($qb, 'e');

         return $qb->getQuery()->getSingleScalarResult();
     }

}
