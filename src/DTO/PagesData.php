<?php

namespace src\DTO;

use vakazona\Dto\Attributes\Required;
use vakazona\Dto\DTO;

class PagesData extends DTO
{
    #[Required]
    public string $loc;

    #[Required]
    public \DateTime $lastMod;

    #[Required]
    public float $priority;

    #[Required]
    public string $changeFreq;
}
