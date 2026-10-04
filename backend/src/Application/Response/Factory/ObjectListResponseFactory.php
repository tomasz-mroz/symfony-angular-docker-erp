<?php


namespace App\Application\Response\Factory;


 use App\Application\Serializer\SerializerContextCreator;
 use JMS\Serializer\SerializerInterface;
 use Symfony\Component\HttpFoundation\JsonResponse;

class ObjectListResponseFactory
{

     public function __construct(
         private SerializerInterface $serializer,
         private SerializerContextCreator $serializerContextCreator
     )
     {
     }

     public function create(array $objects, array $groups = ['Default'], $data = null): JsonResponse
     {
         $response = new JsonResponse();

         $response->setContent($this->serializer->serialize(
             [
                 'items' => $objects,
                 'total' => count($objects),
                 'data' => $data
             ],
             'json',
             $this->serializerContextCreator->create($groups),
         ));

         return $response;
     }
}
