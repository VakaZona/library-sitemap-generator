<?php

namespace vakazona\SitemapGenerator\Generator;

use vakazona\SitemapGenerator\DTO\SitemapData;

class XMLSitemapGenerator implements SitemapGeneratorInterface
{
    public function generate(SitemapData $data): void
    {
        $filePath = $data->filePath;

        $xmlDoc = new \DOMDocument('1.0', 'UTF-8');

        $urlset = $xmlDoc->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $urlset->setAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

        foreach ($data->pages as $page) {
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
