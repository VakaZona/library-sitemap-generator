<?php

namespace vakazona\SitemapGenerator\Validator;

use vakazona\SitemapGenerator\DTO\SitemapData;

interface SitemapValidatorInterface
{
    public function validate(SitemapData $data): void;

}