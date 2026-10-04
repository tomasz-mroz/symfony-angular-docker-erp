<?php


namespace App\Application\Cqrs\QueryResult;


use App\Entity\Log;
use App\Entity\LogToday;

class CalculatedLog
{

    public function __construct(
        private Log|LogToday $log,
        private array $diff,
    )
    {
    }

    /**
     * @return Log|LogToday
     */
    public function getLog():  Log|LogToday
    {
        return $this->log;
    }

    /**
     * @return array
     */
    public function getDiff(): array
    {
        return $this->diff;
    }

}
