<?php


namespace App\Application\Cqrs\QueryResult;


class CountedResult
{

    public function __construct(
        private int $total,
        private array $items
    )
    {
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

}
