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
        $this->checkExtensions($data->fileType, $data->filePath);
        $this->validateFileType($data->fileType);

        $this->validatePages($data->pages);
        $this->validateFilePath($data->filePath);
    }

    private function checkExtensions(string $fileType, string $filePath)
    {
        $parts = explode(".", $filePath);
        $extension = end($parts);
        if ($fileType !== $extension) {
            throw new InvalidSitemapDataException("Invalid file type and file path extension '{$filePath}, {$fileType}'");
        }
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

        $jsonContent = json_encode($this->data->pages, JSON_PRETTY_PRINT);

        file_put_contents($filePath, $jsonContent);
    }

    private function generateXMLSitemap()
    {
        $filePath = $this->data->filePath;

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


    private function validateFilePath(string $filePath)
    {
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        if (!file_exists($filePath)) {
            touch($filePath);
        }

        if (!is_writable(dirname($filePath))) {
            throw new InvalidSitemapDataException("Directory '{$filePath}' is not writable.");
        }
    }

    private function validatePages(array $pages)
    {
        if (empty($pages)) {
            throw new InvalidSitemapDataException("Empty page array. Please provide at least one page.");
        }

        if (count($pages) > 10) {
            throw new InvalidSitemapDataException("The number of pages is too large, please specify no more than 10 lines");
        }

        foreach ($pages as $page) {
            if (!isset($page['loc'], $page['lastmod'], $page['priority'], $page['changefreq'])) {
                throw new InvalidSitemapDataException("Invalid page format. Each page must contain 'loc', 'lastmod', 'priority', and 'changefreq' keys.");
            }

            if (!filter_var($page['loc'], FILTER_VALIDATE_URL)) {
                throw new InvalidSitemapDataException("Invalid 'loc' format. 'loc' must be a valid URL.");
            }

            $this->validateDateString($page['lastmod']);

            if (!is_numeric($page['priority']) || $page['priority'] <= 0 || $page['priority'] > 1) {
                throw new InvalidSitemapDataException("Invalid 'priority' value. 'priority' must be a float greater than 0 and less than or equal to 1.");
            }

            if (!in_array($page['changefreq'], ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'])) {
                throw new InvalidSitemapDataException("Invalid 'changefreq' value. 'changefreq' must be one of the following: 'always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'.");
            }
        }
    }

    private function validateDateString(string $dateString): bool
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
            throw new InvalidSitemapDataException("Invalid 'lastmod' format. 'lastmod' must be in format 'YYYY-MM-DD'.");
        }

        [$year, $month, $day] = explode('-', $dateString);

        $currentYear = date('Y');
        if ($year<2000 || $year > $currentYear) {
            throw new InvalidSitemapDataException("Invalid 'lastmod' year '{$year}'. (2000<YEAR<{$currentYear})");
        }

        if ($month<1 || $month>12) {
            throw new InvalidSitemapDataException("Invalid 'lastmod' month '{$month}'");
        }

        if ($day<1||$day>31){
            throw new InvalidSitemapDataException("Invalid 'lastmod' day '{$day}'");
        }

        if (!checkdate((int)$month, (int)$day, (int)$year)) {
            throw new InvalidSitemapDataException("Invalid 'lastmod' date '{$dateString}'");
        }

        return true;
    }

}
