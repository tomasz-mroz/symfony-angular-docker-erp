<?php

namespace App\Controller\employee;

use App\Application\Response\Factory\PaginatedCountedObjectListResponseFactory;
use App\Application\Common\ControllerHelper;
use App\Application\Cqrs\Query\Employee\GetEmployeeList;
use App\Application\Cqrs\QueryParams\Employee\GetEmployeeListParams;
use App\Application\Response\Factory\ObjectListResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetAllEmployeeListController extends AbstractController
{
    public function __construct(
        private readonly GetEmployeeList $query,
        private readonly PaginatedCountedObjectListResponseFactory $responseFactory,
        private readonly ObjectListResponseFactory $objectListResponseFactory,
        private readonly ControllerHelper $controllerHelper,
    ) {}

    #[Route('/api/employee-list', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $params = new GetEmployeeListParams($request);

        $data = $this->query->query($params);

        if (is_array($data)) {
            return $this->objectListResponseFactory->create($data);
        }

        return $this->responseFactory->create($params, $data);
    }
}
