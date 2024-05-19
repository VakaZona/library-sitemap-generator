<?php

use PHPUnit\Framework\TestCase;
use vakazona\SitemapGenerator\DTO\SitemapData;
use vakazona\SitemapGenerator\Exceptions\InvalidFileExtensionException;
use vakazona\SitemapGenerator\Exceptions\InvalidFilePathException;
use vakazona\SitemapGenerator\Exceptions\InvalidSitemapDataException;
use vakazona\SitemapGenerator\SitemapGenerator;
use vakazona\SitemapGenerator\Validator\SitemapValidator;

class SitemapGeneratorTest extends TestCase
{
    public function testConstructorWithValidData()
    {
        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2022-01-01', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/../test.csv',
        ]);
        $generator = new SitemapGenerator(new SitemapValidator());

        $this->assertInstanceOf(SitemapGenerator::class, $generator);
    }

    public function testConstructorWithInvalidDataRealPath()
    {
        $this->expectException(InvalidFilePathException::class);
        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2022-01-01', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/../../../test.csv',
        ]);
        $generator = new SitemapGenerator(new SitemapValidator());
        $generator->generateSitemap($sitemapData);
    }

    public function testConstructorWithInvalidDataFileType()
    {
        $this->expectException(InvalidFileExtensionException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2022-01-01', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'txt',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator(new SitemapValidator());
        $generator->generateSitemap($sitemapData);
    }

    public function testConstructorWithInvalidDataFilePath()
    {
        $this->expectException(InvalidFilePathException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2022-01-01', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'?test.csv',
        ]);
        $generator = new SitemapGenerator(new SitemapValidator());
        $generator->generateSitemap($sitemapData);
    }

    public function testConstructorWithInvalidDataEmptyPages()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator(new SitemapValidator());
        $generator->generateSitemap($sitemapData);
    }

    public function testConstructorWithInvalidDataPagesNoLoc()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['lastmod' => '2022-01-01', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator(new SitemapValidator());
        $generator->generateSitemap($sitemapData);
    }

    public function testConstructorWithInvalidDataPagesInvalidLastmod()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => 'test', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator(new SitemapValidator());
        $generator->generateSitemap($sitemapData);
    }

    public function testConstructorWithInvalidDataPagesInvalidPriority()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2000-01-01', 'priority' => 1.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator(new SitemapValidator());
        $generator->generateSitemap($sitemapData);
    }

    public function testConstructorWithInvalidDataPagesInvalidChangeFreq()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2000-01-01', 'priority' => 1.0, 'changefreq' => 'mail']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator(new SitemapValidator());
        $generator->generateSitemap($sitemapData);
    }

    public function testConstructorWithInvalidDataFileTypeAndFilePath()
    {
        $this->expectException(InvalidFileExtensionException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2022-01-01', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.json',
        ]);
        $generator = new SitemapGenerator(new SitemapValidator());
        $generator->generateSitemap($sitemapData);
    }


    public function testConstructorWithInvalidDataLastmodYear()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '1990-12-01', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator(new SitemapValidator());
        $generator->generateSitemap($sitemapData);
    }

    public function testConstructorWithInvalidDataLastmodMonth()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2005-20-01', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator(new SitemapValidator());
        $generator->generateSitemap($sitemapData);
    }

    public function testConstructorWithInvalidDataLastmodDay()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2005-01-65', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator(new SitemapValidator());
        $generator->generateSitemap($sitemapData);
    }

    public function testConstructorWithInvalidDataLastmod()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2005-02-31', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator(new SitemapValidator());
        $generator->generateSitemap($sitemapData);
    }

    public function testConstructorWithInvalidPagesCount()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [
                ['loc' => 'https://example.com', 'lastmod' => '2005-02-16', 'priority' => 0.5, 'changefreq' => 'daily'],
                ['loc' => 'https://example.com', 'lastmod' => '2005-02-16', 'priority' => 0.5, 'changefreq' => 'daily'],
                ['loc' => 'https://example.com', 'lastmod' => '2005-02-16', 'priority' => 0.5, 'changefreq' => 'daily'],
                ['loc' => 'https://example.com', 'lastmod' => '2005-02-16', 'priority' => 0.5, 'changefreq' => 'daily'],
                ['loc' => 'https://example.com', 'lastmod' => '2005-02-16', 'priority' => 0.5, 'changefreq' => 'daily'],
                ['loc' => 'https://example.com', 'lastmod' => '2005-02-16', 'priority' => 0.5, 'changefreq' => 'daily'],
                ['loc' => 'https://example.com', 'lastmod' => '2005-02-16', 'priority' => 0.5, 'changefreq' => 'daily'],
                ['loc' => 'https://example.com', 'lastmod' => '2005-02-16', 'priority' => 0.5, 'changefreq' => 'daily'],
                ['loc' => 'https://example.com', 'lastmod' => '2005-02-16', 'priority' => 0.5, 'changefreq' => 'daily'],
                ['loc' => 'https://example.com', 'lastmod' => '2005-02-16', 'priority' => 0.5, 'changefreq' => 'daily'],
                ['loc' => 'https://example.com', 'lastmod' => '2005-02-16', 'priority' => 0.5, 'changefreq' => 'daily'],
            ],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator(new SitemapValidator());
        $generator->generateSitemap($sitemapData);
    }


    public function testConstructorWithInvalidDataPages()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2005-02-23', 'priority' => 0.5, 'changefreq' => 'daily', 'test' => 'test']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator(new SitemapValidator());
        $generator->generateSitemap($sitemapData);
    }

}