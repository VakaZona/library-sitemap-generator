<?php

namespace vakazona\SitemapGenerator\Generator;

use vakazona\SitemapGenerator\DTO\SitemapData;

interface SitemapGeneratorInterface
{
    public function generate(SitemapData $data): void;
}
