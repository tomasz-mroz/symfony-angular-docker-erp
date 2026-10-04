<?php

namespace App\Application\Response;

use Symfony\Component\HttpFoundation\Response;

class AcceptedResponse extends Response
{
    public function __construct($content = '', int $status = 204, array $headers = [])
    {
        parent::__construct($content, $status, $headers);
    }
}
