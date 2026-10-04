<?php


namespace App\Application\Response;


use Symfony\Component\HttpFoundation\JsonResponse;

class ErrorResponse extends JsonResponse
{
    public function __construct(string $errorText, array $errors = [], int $status = 500)
    {
        parent::__construct([
            'error' => [
                'text' => $errorText,
                'list' => $errors
            ]
        ], $status);
    }
}
