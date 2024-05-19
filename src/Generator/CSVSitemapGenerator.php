<?php

namespace vakazona\SitemapGenerator\Generator;

use vakazona\SitemapGenerator\DTO\SitemapData;

class CSVSitemapGenerator implements SitemapGeneratorInterface
{
    public function generate(SitemapData $data): void
    {
        $filePath = $data->filePath;
        $fp = fopen($filePath, 'w');

        fputcsv($fp, ['loc', 'lastmod', 'priority', 'changefreq'], ';');

        foreach ($data->pages as $row) {
            fputcsv($fp, $row, ';');
        }

        fclose($fp);
    }
}
