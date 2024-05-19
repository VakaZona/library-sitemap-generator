<?php

use PHPUnit\Framework\TestCase;
use vakazona\SitemapGenerator\DTO\SitemapData;
use vakazona\SitemapGenerator\Exceptions\InvalidSitemapDataException;
use vakazona\SitemapGenerator\SitemapGenerator;

class SitemapGeneratorTest extends TestCase
{
    public function testConstructorWithValidData()
    {

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2022-01-01', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/../test.csv',
        ]);
        $generator = new SitemapGenerator($sitemapData);

        $this->assertInstanceOf(SitemapGenerator::class, $generator);
    }

    public function testConstructorWithInvalidDataRealPath()
    {
        $this->expectException(InvalidSitemapDataException::class);
        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2022-01-01', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/../../../test.csv',
        ]);
        $generator = new SitemapGenerator($sitemapData);
    }

    public function testConstructorWithInvalidDataFileType()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2022-01-01', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'txt',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator($sitemapData);
    }

    public function testConstructorWithInvalidDataFilePath()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2022-01-01', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'txt',
            'filePath' => __DIR__.'?test.csv',
        ]);
        $generator = new SitemapGenerator($sitemapData);
    }

    public function testConstructorWithInvalidDataEmptyPages()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [],
            'fileType' => 'txt',
            'filePath' => __DIR__.'?test.csv',
        ]);
        $generator = new SitemapGenerator($sitemapData);
    }

    public function testConstructorWithInvalidDataPagesNoLoc()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['lastmod' => '2022-01-01', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'txt',
            'filePath' => __DIR__.'?test.csv',
        ]);
        $generator = new SitemapGenerator($sitemapData);
    }

    public function testConstructorWithInvalidDataPagesInvalidLastmod()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => 'test', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator($sitemapData);
    }

    public function testConstructorWithInvalidDataPagesInvalidPriority()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2000-01-01', 'priority' => 1.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator($sitemapData);
    }

    public function testConstructorWithInvalidDataPagesInvalidChangeFreq()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2000-01-01', 'priority' => 1.0, 'changefreq' => 'mail']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator($sitemapData);
    }

    public function testConstructorWithInvalidDataFileTypeAndFilePath()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2022-01-01', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.json',
        ]);
        $generator = new SitemapGenerator($sitemapData);
    }


    public function testConstructorWithInvalidDataLastmodYear()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '1990-12-01', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator($sitemapData);
    }

    public function testConstructorWithInvalidDataLastmodMonth()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2005-20-01', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator($sitemapData);
    }

    public function testConstructorWithInvalidDataLastmodDay()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2005-01-65', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator($sitemapData);
    }

    public function testConstructorWithInvalidDataLastmod()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2005-02-31', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator($sitemapData);
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
        $generator = new SitemapGenerator($sitemapData);
    }


    public function testConstructorWithInvalidDataPages()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2005-02-23', 'priority' => 0.5, 'changefreq' => 'daily', 'test' => 'test']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator($sitemapData);
    }

}