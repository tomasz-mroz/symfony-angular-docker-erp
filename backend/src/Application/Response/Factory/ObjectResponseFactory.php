<?php


namespace App\Application\Response\Factory;


 use App\Application\Serializer\SerializerContextCreator;
 use JMS\Serializer\SerializerInterface;
 use Symfony\Component\HttpFoundation\JsonResponse;

class ObjectResponseFactory
{

     public function __construct(
         private SerializerInterface $serializer,
         private SerializerContextCreator $serializerContextCreator
     )
     {
     }

     public function create($object, array $serializationGroups = ['Default']): JsonResponse
     {
         $response = new JsonResponse();

         $response->setContent($this->serializer->serialize(
             $object,
             'json',
             $this->serializerContextCreator->create($serializationGroups)
         ));

         return $response;
     }
}
