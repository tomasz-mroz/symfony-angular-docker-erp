<?php


namespace App\Application\Response\Factory;


use App\Application\Cqrs\QueryResult\FileResult;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileResponseFactory
{

    public function create(FileResult $fileResult, bool $cacheImage = false): BinaryFileResponse
    {
        $response = new BinaryFileResponse($fileResult->getFilepath());

        $response->headers->set('Content-disposition', 'attachment; filename="'.$fileResult->getFilename().'"');


        if ($cacheImage) {
            $response->headers->set('Cache-Control', 'max-age=604800');
        }

        return $response;
    }
}
