<?php


namespace App\Application\Cqrs\QueryParams;


use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractPaginatedParams
{

    private int $page;

    private int $pageSize;

    public function __construct(Request $request)
    {
        $this->page = (int) $request->query->get('page', 1);
        $this->pageSize = (int) $request->query->get('pageSize', 20);
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getOffset(): int
    {
        return $this->page * $this->pageSize - $this->pageSize;
    }

    public function processSortBy(?string $requestedSortBy, array $validSortFields): ?string
    {
        if (null === $requestedSortBy || $requestedSortBy === '') {
            return null;
        }

        if (in_array($requestedSortBy, $validSortFields, true)) {
            return $requestedSortBy;
        }

        throw new InvalidArgumentException();
    }

    public function processSortOrder(?string $requestedSortOrder): ?string
    {
        if (null === $requestedSortOrder || $requestedSortOrder === '') {
            return null;
        }

        if (in_array(strtoupper($requestedSortOrder), ['DESC', 'ASC'], true)) {
            return $requestedSortOrder;
        }

        throw new InvalidArgumentException();
    }

}
