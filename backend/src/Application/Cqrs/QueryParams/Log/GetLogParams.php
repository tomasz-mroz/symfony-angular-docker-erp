<?php


namespace App\Application\Cqrs\QueryParams\Log;


use App\Application\Cqrs\QueryParams\AbstractPaginatedParams;
use Symfony\Component\HttpFoundation\Request;

class GetLogParams extends AbstractPaginatedParams
{

    public function __construct(
        private Request $request,
        private string $entity,
        private string $entityId,
        private bool $withSecured = false,
    )
    {
        parent::__construct($this->request);
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    public function getEntityId(): string
    {
        return $this->entityId;
    }

    /**
     * @return bool
     */
    public function isWithSecured(): bool
    {
        return $this->withSecured;
    }

}
