<?php


namespace App\Application\Cqrs\Query\Log;


use App\Application\Cqrs\QueryParams\Log\GetLogParams;
use App\Application\Cqrs\QueryResult\CountedResult;
use App\Application\Service\LogDiffCalculator;
use App\Entity\Log;
use App\Domain\Repository\LogRepositoryInterface;

class GetLog
{
    public function __construct(
        private LogRepositoryInterface $logRepository,
        private LogDiffCalculator $logDiffCalculator,
    )
    {
    }

    public function query(GetLogParams $params): CountedResult
    {
        $count = $this->logRepository->count(
            $params->getEntity(),
            $params->getEntityId()
        );

        $items = $this->logRepository->getList(
            $params->getOffset(),
            $params->getPageSize(),
            $params->getEntity(),
            $params->getEntityId(),
        );

        $calculatedItems = $this->logDiffCalculator->calculate(
            $items,
            $params->getEntity(),
            $params->isWithSecured()
        );

        return new CountedResult(
            $count,
            $calculatedItems
        );
    }
}
