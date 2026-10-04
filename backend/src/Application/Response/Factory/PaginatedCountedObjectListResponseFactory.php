<?php


namespace App\Application\Response\Factory;


use App\Application\Cqrs\QueryParams\AbstractPaginatedParams;
use App\Application\Cqrs\QueryResult\CountedResult;
use App\Application\Serializer\SerializerContextCreator;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class PaginatedCountedObjectListResponseFactory
{

    public function __construct(
        private SerializerInterface $serializer,
        private SerializerContextCreator $serializerContextCreator
    )
    {
    }

    public function create(AbstractPaginatedParams $params, CountedResult $countedResult, array $groups = ['Default'], $data = null): JsonResponse
    {
        $response = new JsonResponse();

        $response->setContent($this->serializer->serialize(
            [
                'items' => $countedResult->getItems(),
                'pagination' => [
                    'currentPage' => $params->getPage(),
                    'pageSize' => $params->getPageSize(),
                    'thisPageSize' => count($countedResult->getItems()),
                    'total' => $countedResult->getTotal(),
                ],
                'data' => $data,
            ],
            'json',
            $this->serializerContextCreator->create($groups)
        ));

        return $response;
    }
}
