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
            case 'xml':
                $this->generateXMLSitemap();
                break;
            case 'csv':
                $this->generateCSVSitemap();
                break;
            case 'json':
                $this->generateJSONSitemap();
                break;
        }
    }

    private function validateData(SitemapData $data)
    {
        $this->validateFileType($data->fileType);
        $this->validateFilePath($data->filePath);
    }

    private function validateFileType(string $fileType)
    {
        if (!(in_array($fileType, ['xml', 'csv', 'json']))) {
            throw new InvalidSitemapDataException("Invalid file type '{$fileType}'");
        }
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

    private function generateJSONSitemap()
    {
        $filePath = $this->data->filePath;
        $this->checkFile($filePath);

        $jsonContent = json_encode($this->data->pages, JSON_PRETTY_PRINT);

        file_put_contents($filePath, $jsonContent);
    }

    private function generateXMLSitemap()
    {
        $filePath = $this->data->filePath;
        $this->checkFile($filePath);

        $xmlDoc = new \DOMDocument('1.0', 'UTF-8');

        $urlset = $xmlDoc->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $urlset->setAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

        foreach ($this->data->pages as $page) {
            $url = $xmlDoc->createElement('url');

            $url->appendChild($xmlDoc->createElement('loc', $page['loc']));
            $url->appendChild($xmlDoc->createElement('lastmod', $page['lastmod']));
            $url->appendChild($xmlDoc->createElement('priority', $page['priority']));
            $url->appendChild($xmlDoc->createElement('changefreq', $page['changefreq']));

            $urlset->appendChild($url);
        }

        $xmlDoc->appendChild($urlset);

        $xmlDoc->formatOutput = true;
        $xmlDoc->save($filePath);
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

    private function validateFilePath(string $filePath)
    {
        if (!realpath($filePath)) {
            throw new InvalidSitemapDataException("Invalid file path '{$filePath}'. Please provide absolute or relative path.");
        }

        $realPath = realpath($filePath);

        if (!is_writable(dirname($realPath))) {
            throw new InvalidSitemapDataException("Directory '{$realPath}' is not writable.");
        }
    }
}
