<?php

namespace vakazona\SitemapGenerator\Validator;

use vakazona\SitemapGenerator\DTO\SitemapData;
use vakazona\SitemapGenerator\Exceptions\InvalidFileExtensionException;

class FileTypeValidator implements ValidatorInterface
{
    private const ALLOWED_FILE_TYPES = ['xml', 'csv', 'json'];

    /**
     * @throws InvalidFileExtensionException
     */
    public function validate(SitemapData $data): void
    {
        $this->checkExtensions($data->fileType, $data->filePath);
        $this->validateFileType($data->fileType);
    }

    /**
     * @throws InvalidFileExtensionException
     */
    private function checkExtensions(string $fileType, string $filePath): void
    {
        $expectedExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        if ($fileType !== $expectedExtension) {
            throw new InvalidFileExtensionException("Expected '$fileType' file type, but found '$expectedExtension' extension in '$filePath'");
        }
    }

    /**
     * @throws InvalidFileExtensionException
     */
    private function validateFileType(string $fileType): void
    {
        if (!in_array($fileType, self::ALLOWED_FILE_TYPES)) {
            $allowedTypes = implode(', ', self::ALLOWED_FILE_TYPES);
            throw new InvalidFileExtensionException("Invalid file type '$fileType'. Allowed types: $allowedTypes");
        }
    }
}