<?php

namespace App\Application\Cqrs\QueryParams\Employee;

use App\Application\Cqrs\QueryParams\AbstractPaginatedParams;
use App\Infrastructure\Doctrine\Filter\Filters;
use Symfony\Component\HttpFoundation\Request;

class GetEmployeeListParams extends AbstractPaginatedParams
{
    private Filters $filters;
    protected bool $disableCount = false;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $queryParameters = $request->query->all();
        $rawFilters = $queryParameters['filter'] ?? [];

        $this->filters = new Filters(
            is_array($rawFilters) ? $rawFilters : [],
            [
                'email' => 'string',
                'firstName' => 'string',
                'lastName' => 'string',
                'role' => 'string',
            ]
        );

        $this->disableCount = $request->query->getBoolean('disableCount', false);
    }

    public function getFilters(): Filters
    {
        return $this->filters;
    }

    public function isDisableCount(): bool
    {
        return $this->disableCount;
    }
}
