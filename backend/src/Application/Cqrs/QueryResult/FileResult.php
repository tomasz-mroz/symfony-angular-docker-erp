<?php


namespace App\Application\Cqrs\QueryResult;


class FileResult
{
    public function __construct(
        private string $filepath,
        private string $mime,
        private string $filename,
    )
    {
    }

    /**
     * @return string
     */
    public function getFilepath(): string
    {
        return $this->filepath;
    }

    /**
     * @return string
     */
    public function getMime(): string
    {
        return $this->mime;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return str_replace([' ', '/', ','], '_', $this->filename);
    }

    /**
     * @param string $filename
     */
    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

}
