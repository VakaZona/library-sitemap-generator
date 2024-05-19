<?php

namespace vakazona\SitemapGenerator\Validator;

use vakazona\SitemapGenerator\DTO\SitemapData;

interface ValidatorInterface
{
    public function validate(SitemapData $data): void;
}