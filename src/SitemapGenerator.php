<?php

namespace vakazona\SitemapGenerator;

use vakazona\SitemapGenerator\DTO\SitemapData;
use vakazona\SitemapGenerator\Validator\SitemapValidatorInterface;

class SitemapGenerator
{
    private $data;
    private $validator;

    public function __construct(SitemapValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function generateSitemap(SitemapData $data)
    {
        $this->validator->validate($data);
        $this->data = $data;
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

}
