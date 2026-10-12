<?php

namespace App\Controller\log;

use App\Application\Common\ControllerHelper;
use App\Application\Cqrs\Query\Log\GetLog;
use App\Application\Cqrs\QueryParams\Log\GetLogParams;
use App\Application\Response\Factory\PaginatedCountedObjectListResponseFactory;
use App\Entity\Employee;
use App\Entity\Log;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/employee-log', methods: ['GET'])]
class GetEmployeeLogController extends AbstractController
{
    public function __construct(
        private GetLog $query,
        private ControllerHelper $controllerHelper,
        private PaginatedCountedObjectListResponseFactory $paginatedCountedObjectListResponseFactory
    ){}

    public function __invoke(Employee $employee)
    {
        $params = new GetLogParams(
            $this->controllerHelper->getRequest(),
            Log::ENTITY_EMPLOYEE,
            $employee->getUuid()
        );

        return $this->paginatedCountedObjectListResponseFactory->create($params, $this->query->query($params));
    }
}
