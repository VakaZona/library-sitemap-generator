<?php

namespace vakazona\SitemapGenerator\Validator;

use vakazona\SitemapGenerator\DTO\SitemapData;
use vakazona\SitemapGenerator\Exceptions\DirectoryCreationException;
use vakazona\SitemapGenerator\Exceptions\FileCreationException;
use vakazona\SitemapGenerator\Exceptions\InvalidFilePathException;

class FilePathValidator implements ValidatorInterface
{
    /**
     * @throws FileCreationException
     * @throws DirectoryCreationException
     * @throws InvalidFilePathException
     */
    public function validate(SitemapData $data): void
    {
        $this->validateFilePath($data->filePath);
    }

    /**
     * @throws InvalidFilePathException
     * @throws DirectoryCreationException
     * @throws FileCreationException
     */
    private function validateFilePath(string $filePath): void
    {
        $this->ensureValidFilePath($filePath);
        $this->ensureWorkDir($filePath);
        $this->ensureDirectoryExists($filePath);
        $this->ensureFileExists($filePath);
        $this->ensureDirectoryIsWritable($filePath);
    }


    /**
     * @throws InvalidFilePathException
     */
    private function ensureWorkDir(string $filePath): void
    {
        if (strpos($filePath, '../') !== false || strpos($filePath, './') !== false) {
            throw new InvalidFilePathException("Relative path not allowed: '$filePath'.");
        }
    }

    /**
     * @throws DirectoryCreationException
     */
    private function ensureDirectoryExists(string $filePath): void
    {
        $directory = dirname($filePath);

        if (!file_exists($directory)) {
            if (!mkdir($directory, 0777, true)) {
                throw new DirectoryCreationException("Failed to create directory '$directory'.");
            }
        }
    }

    /**
     * @throws FileCreationException
     */
    private function ensureFileExists(string $filePath): void
    {
        if (!file_exists($filePath)) {
            if (!touch($filePath)) {
                throw new FileCreationException("Failed to create file '$filePath'.");
            }
        }
    }

    /**
     * @throws InvalidFilePathException
     */
    private function ensureDirectoryIsWritable(string $filePath): void
    {
        $directory = dirname($filePath);

        if (!is_writable($directory)) {
            throw new InvalidFilePathException("Directory '$directory' is not writable.");
        }
    }

    private function ensureValidFilePath(string $filePath): void
    {
        if (preg_match('/[\/\\:*?"<>|]/', $filePath)) {
            throw new InvalidFilePathException("Invalid file path: '$filePath'.");
        }
    }
}
