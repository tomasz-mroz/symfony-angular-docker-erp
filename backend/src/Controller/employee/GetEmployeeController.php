<?php

namespace App\Controller\employee;

use App\Application\Response\Factory\ObjectResponseFactory;
use App\Entity\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/employee', methods: ['GET'])]
class GetEmployeeController extends AbstractController
{
    public function __construct(
        private ObjectResponseFactory $objectResponseFactory,
    ) {}

     public function __invoke(Employee $employee): JsonResponse
     {
//         $this->denyAccessUnlessGranted(Permissions::READ, $employee);

         return $this->objectResponseFactory->create($employee);
     }
}
