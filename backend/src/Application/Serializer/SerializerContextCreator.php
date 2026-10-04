<?php


namespace App\Application\Serializer;


use JMS\Serializer\SerializationContext;

class SerializerContextCreator
{
    public function create(array $groups = ['Default']): SerializationContext
    {
        $context = new SerializationContext();

        $context->setSerializeNull(true);
        $context->enableMaxDepthChecks();
        $context->setGroups($groups);

        return $context;
    }
}
