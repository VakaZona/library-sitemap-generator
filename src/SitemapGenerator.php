<?php

namespace src;

use src\DTO\SitemapData;

class SitemapGenerator
{
    private $data;

    public function __construct(SitemapData $data)
    {
        $this->validateData($data);
        $this->data = $data;

    }

    private function validateData(SitemapData $data)
    {

    }
}