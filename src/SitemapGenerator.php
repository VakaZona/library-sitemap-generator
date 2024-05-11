<?php

namespace vakazona\SitemapGenerator;

use vakazona\SitemapGenerator\DTO\SitemapData;
use vakazona\SitemapGenerator\Exceptions\InvalidSitemapDataException;

class SitemapGenerator
{
    private $data;

    public function __construct(SitemapData $data)
    {
        $this->validateData($data);
        $this->data = $data;

    }

    public function generateSitemap()
    {
        switch ($this->data->fileType) {
            //            case 'xml':
            //                $this->generateXMLSitemap();
            //                break;
            case 'csv':
                $this->generateCSVSitemap();
                break;
                //            case 'json':
                //                $this->generateJSONSitemap();
                //                break;
        }
    }

    private function validateData(SitemapData $data)
    {
        $this->validateFileType($data->fileType);
    }

    private function validateFileType(string $fileType)
    {
        if (!(in_array($fileType, ['xml', 'csv', 'json']))) {
            throw new InvalidSitemapDataException("Invalid file type '{$fileType}'");
        }
    }

    private function validateFile()
    {

    }

    private function generateCSVSitemap()
    {
        $filePath = $this->data->filePath;
        $this->checkFile($filePath);
        $fp = fopen($filePath, 'w');

        fputcsv($fp, ['loc', 'lastmod', 'priority', 'changefreq'], ';');

        foreach ($this->data->pages as $row) {
            fputcsv($fp, $row, ';');
        }

        fclose($fp);

    }

    private function checkFile(string $filePath): void
    {
        $parts = explode(DIRECTORY_SEPARATOR, $filePath);

        array_pop($parts);

        $directory = implode(DIRECTORY_SEPARATOR, $parts);

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        if (!file_exists($filePath)) {
            touch($filePath);
        }
    }
}
