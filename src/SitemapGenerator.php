<?php

namespace vakazona\SitemapGenerator;

use vakazona\SitemapGenerator\DTO\SitemapData;
use vakazona\SitemapGenerator\Exceptions\InvalidFileExtensionException;
use vakazona\SitemapGenerator\Generator\CSVSitemapGenerator;
use vakazona\SitemapGenerator\Generator\JSONSitemapGenerator;
use vakazona\SitemapGenerator\Generator\SitemapGeneratorInterface;
use vakazona\SitemapGenerator\Generator\XMLSitemapGenerator;
use vakazona\SitemapGenerator\Validator\SitemapValidatorInterface;

class SitemapGenerator
{
    private $data;
    private $validator;

    public function __construct(SitemapValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @throws InvalidFileExtensionException
     */
    public function generateSitemap(SitemapData $data): void
    {
        $this->validator->validate($data);
        $this->data = $data;

        $generator = $this->getGenerator($this->data->fileType);
        $generator->generate($this->data);
    }

    private function getGenerator(string $fileType): SitemapGeneratorInterface
    {
        switch ($fileType) {
            case 'xml':
                return new XMLSitemapGenerator();
            case 'csv':
                return new CSVSitemapGenerator();
            case 'json':
                return new JSONSitemapGenerator();
            default:
                throw new InvalidFileExtensionException("Unsupported file type: $fileType");
        }
    }

}
