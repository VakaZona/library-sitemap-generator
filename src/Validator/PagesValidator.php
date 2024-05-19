<?php

namespace vakazona\SitemapGenerator\Validator;

use vakazona\SitemapGenerator\DTO\SitemapData;
use vakazona\SitemapGenerator\Exceptions\InvalidSitemapDataException;

class PagesValidator implements ValidatorInterface
{
    private const EXPECTED_KEYS = ['loc', 'lastmod', 'priority', 'changefreq'];


    /**
     * @throws InvalidSitemapDataException
     */
    public function validate(SitemapData $data): void
    {
        $this->validatePages($data->pages);
    }

    /**
     * @throws InvalidSitemapDataException
     */
    private function validatePages(array $pages): void
    {
        $this->checkNotEmpty($pages);
        $this->checkPagesCount($pages);

        foreach ($pages as $page) {
            $this->validatePageFormat($page);
        }
    }

    /**
     * @throws InvalidSitemapDataException
     */
    private function checkNotEmpty(array $pages): void
    {
        if (empty($pages)) {
            throw new InvalidSitemapDataException("Empty page array. Please provide at least one page.");
        }
    }

    /**
     * @throws InvalidSitemapDataException
     */
    private function checkPagesCount(array $pages): void
    {
        if (count($pages) > 10) {
            throw new InvalidSitemapDataException("The number of pages is too large, please specify no more than 10 lines");
        }
    }

    /**
     * @throws InvalidSitemapDataException
     */
    private function validatePageFormat(array $page): void
    {
        $expectedKeys = self::EXPECTED_KEYS;
        $arrayKeys = array_keys($page);
        sort($expectedKeys);
        sort($arrayKeys);

        if ($arrayKeys !== $expectedKeys) {
            throw new InvalidSitemapDataException("Invalid strings in pages.");
        }

        if (!isset($page['loc'], $page['lastmod'], $page['priority'], $page['changefreq'])) {
            throw new InvalidSitemapDataException("Invalid page format. Each page must contain 'loc', 'lastmod', 'priority', and 'changefreq' keys.");
        }

        $this->validateUrl($page['loc']);
        $this->validateDateString($page['lastmod']);
        $this->validatePriority($page['priority']);
        $this->validateChangeFreq($page['changefreq']);
    }

    /**
     * @throws InvalidSitemapDataException
     */
    private function validateUrl(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidSitemapDataException("Invalid 'loc' format. 'loc' must be a valid URL.");
        }
    }

    /**
     * @throws InvalidSitemapDataException
     */
    private function validatePriority(float $priority): void
    {
        if ($priority <= 0 || $priority > 1) {
            throw new InvalidSitemapDataException("Invalid 'priority' value. 'priority' must be a float greater than 0 and less than or equal to 1.");
        }
    }

    /**
     * @throws InvalidSitemapDataException
     */
    private function validateChangeFreq(string $changefreq): void
    {
        $allowedFreqs = ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'];

        if (!in_array($changefreq, $allowedFreqs)) {
            throw new InvalidSitemapDataException("Invalid 'changefreq' value. 'changefreq' must be one of the following: 'always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'.");
        }
    }

    /**
     * @throws InvalidSitemapDataException
     */
    private function validateDateString(string $dateString): void
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
            throw new InvalidSitemapDataException("Invalid 'lastmod' format. 'lastmod' must be in format 'YYYY-MM-DD'.");
        }

        [$year, $month, $day] = explode('-', $dateString);

        $currentYear = date('Y');
        if ($year < 2000 || $year > $currentYear) {
            throw new InvalidSitemapDataException("Invalid 'lastmod' year '$year'. (2000<YEAR<$currentYear)");
        }

        if ($month < 1 || $month > 12) {
            throw new InvalidSitemapDataException("Invalid 'lastmod' month '$month'");
        }

        if ($day < 1 || $day > 31) {
            throw new InvalidSitemapDataException("Invalid 'lastmod' day '$day'");
        }

        if (!checkdate((int)$month, (int)$day, (int)$year)) {
            throw new InvalidSitemapDataException("Invalid 'lastmod' date '$dateString'");
        }
    }
}