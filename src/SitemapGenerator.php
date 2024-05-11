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
        $this->validatePages($data->pages);
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

    private function checkFile(string $filePath, string $fileType): void
    {
        if (preg_match('/[<>:"\/\\|?*\x00-\x1F]/', $filePath)) {
            throw new InvalidSitemapDataException("Invalid characters in file path '{$filePath}'. Please provide a valid file path.");
        }


        $realPath = realpath($filePath);

        $extension = match ($fileType) {
            'xml' => 'xml',
            'csv' => 'csv',
            'json' => 'json',
            default => throw new InvalidSitemapDataException("Invalid file type '{$fileType}'"),
        };

        $realPath = preg_replace('/\.[^.]+$/', '', $realPath);

        $realPath .= '.' . $extension;

        if (!is_writable(dirname($realPath))) {
            throw new InvalidSitemapDataException("Directory '{$realPath}' is not writable.");
        }

        if (!file_exists(dirname($realPath))) {
            mkdir(dirname($realPath), 0777, true);
        }

        if (!file_exists($realPath)) {
            touch($realPath);
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

    private function validatePages(array $pages)
    {
        if (empty($pages)) {
            throw new InvalidSitemapDataException("Empty page array. Please provide at least one page.");
        }

        foreach ($pages as $page) {
            if (!isset($page['loc'], $page['lastmod'], $page['priority'], $page['changefreq'])) {
                throw new InvalidSitemapDataException("Invalid page format. Each page must contain 'loc', 'lastmod', 'priority', and 'changefreq' keys.");
            }

            if (!filter_var($page['loc'], FILTER_VALIDATE_URL)) {
                throw new InvalidSitemapDataException("Invalid 'loc' format. 'loc' must be a valid URL.");
            }

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $page['lastmod'])) {
                throw new InvalidSitemapDataException("Invalid 'lastmod' format. 'lastmod' must be in format 'YYYY-MM-DD'.");
            }

            if (!is_numeric($page['priority']) || $page['priority'] <= 0 || $page['priority'] > 1) {
                throw new InvalidSitemapDataException("Invalid 'priority' value. 'priority' must be a float greater than 0 and less than or equal to 1.");
            }

            if (!in_array($page['changefreq'], ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'])) {
                throw new InvalidSitemapDataException("Invalid 'changefreq' value. 'changefreq' must be one of the following: 'always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'.");
            }
        }
    }

}
