<?php


namespace App\Application\Response\Factory;


use App\Application\Cqrs\QueryParams\AbstractPaginatedParams;
use App\Application\Serializer\SerializerContextCreator;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class PaginatedObjectListResponseFactory
{

    public function __construct(
        private SerializerInterface $serializer,
        private SerializerContextCreator $serializerContextCreator
    )
    {
    }

    public function create(AbstractPaginatedParams $params, array $objects): JsonResponse
    {
        $response = new JsonResponse();

        $response->setContent($this->serializer->serialize(
            [
                'items' => $objects,
                'pagination' => [
                    'currentPage' => $params->getPage(),
                    'pageSize' => $params->getPageSize(),
                    'thisPageSize' => count($objects),
                ]
            ],
            'json',
            $this->serializerContextCreator->create()
        ));

        return $response;
    }
}
