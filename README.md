# Library for generate sitemap (XML, CSV, JSON)

## Contents

- [Installation](#installation)
- [Usage](#usage)
- [Tests](#tests)

## Installation

```
composer require vakazona/library-sitemap-generator
```

## Usage

```php
$pages = [
            ['loc' => 'https://example.com/page1', 'lastmod' => '2024-05-11', 'priority' => 0.8, 'changefreq' => 'daily'],
            ['loc' => 'https://example.com/page2', 'lastmod' => '2024-05-10', 'priority' => 0.5, 'changefreq' => 'weekly']
        ];

$fileType = 'xml';
$filePath = storage_path('/app/upload/sitemap.xml');

$sitemapGenerator = new SitemapGenerator(new SitemapValidator()));
$sitemapGenerator->generateSitemap(new SitemapData([
    'pages' => $pages,
    'fileType' => $fileType,
    'filePath' => $filePath,
]));

//OR

$sitemapData = new SitemapData([
            'pages' => [['loc' => 'https://example.com', 'lastmod' => '2022-01-01', 'priority' => 0.5, 'changefreq' => 'daily']],
            'fileType' => 'csv',
            'filePath' => __DIR__.'/test.csv',
        ]);
$sitemapData->fileType = 'json';
$sitemapData->filePath = __DIR__.'/test.json';
$generator = new SitemapGenerator(new SitemapValidator());
$generator->generateSitemap($sitemapData);
```

## Tests
```
vendor/bin/phpunit
```