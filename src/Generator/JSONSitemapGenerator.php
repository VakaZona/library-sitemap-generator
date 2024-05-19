<?php

namespace vakazona\SitemapGenerator\Generator;

use vakazona\SitemapGenerator\DTO\SitemapData;

class JSONSitemapGenerator implements SitemapGeneratorInterface
{
    public function generate(SitemapData $data): void
    {
        $filePath = $data->filePath;

        $jsonContent = json_encode($data->pages, JSON_PRETTY_PRINT);

        file_put_contents($filePath, $jsonContent);
    }
}
