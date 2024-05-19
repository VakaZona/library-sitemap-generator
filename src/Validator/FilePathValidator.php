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
        $absoluteFilePath = realpath($filePath);
        $absoluteSourceDirectory = realpath(__DIR__);

        if ($absoluteFilePath === false || $absoluteSourceDirectory === false) {
            throw new InvalidFilePathException();
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
}