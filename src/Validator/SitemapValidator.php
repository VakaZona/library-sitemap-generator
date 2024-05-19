<?php

namespace vakazona\SitemapGenerator\Validator;

use vakazona\SitemapGenerator\DTO\SitemapData;

class SitemapValidator implements SitemapValidatorInterface
{

    private $validators = [];

    public function __construct()
    {
        $this->validators[] = new FileTypeValidator();
        $this->validators[] = new PagesValidator();
        $this->validators[] = new FilePathValidator();
    }

    public function validate(SitemapData $data): void
    {
        foreach ($this->validators as $validator) {
            $validator->validate($data);
        }
    }
}