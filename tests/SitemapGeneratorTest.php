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
            'filePath' => __DIR__.'/test.csv',
        ]);
        $generator = new SitemapGenerator($sitemapData);

        $this->assertInstanceOf(SitemapGenerator::class, $generator);
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

    public function testConstructorWithInvalidDataPages()
    {
        $this->expectException(InvalidSitemapDataException::class);

        $sitemapData = new SitemapData([
            'pages' => [['lastmod' => '2022-01-01', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'txt',
            'filePath' => __DIR__.'?test.csv',
        ]);
        $generator = new SitemapGenerator($sitemapData);
    }
}