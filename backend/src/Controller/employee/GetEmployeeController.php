<?php

declare(strict_types=1);

namespace App\Controller\employee;

use App\Application\Response\Factory\ObjectResponseFactory;
use App\Entity\Employee;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/employee/{employeeId}', methods: ['GET'])]
class GetEmployeeController extends AbstractController
{
    public function __construct(
        private readonly ObjectResponseFactory $objectResponseFactory,
    ) {}

    public function __invoke(
        #[MapEntity(mapping: ['employeeId' => 'uuid'])]
        Employee $employee,
    ): JsonResponse {
        // $this->denyAccessUnlessGranted(Permissions::READ, $employee);

        return $this->objectResponseFactory->create($employee);
    }
}
