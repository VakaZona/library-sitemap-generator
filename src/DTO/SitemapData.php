<?php

namespace vakazona\DTO;

use vakazona\Dto\Attributes\Required;
use vakazona\Dto\DTO;

class SitemapData extends DTO
{
    #[Required]
    public array $pages;
    #[Required]
    public string $fileType;

    #[Required]
    public string $filePath;


}
