# Pucene: Index

The index is the main entry point for pucene. It analyses, stores and searches for documents.

To store the documents it uses a driver which is independent and the reference implementation is done in
[pucene/dbal-driver](https://github.com/pucene/dbal-driver).

> This is a subtree split of the `pucene/pucene` project create issues in the [main repository](https://github.com/pucene/pucene).

## Usage

```php
<?php

use Pucene\Analysis\StandardAnalyzer;
use Pucene\Index\Driver\MemoryDriverFactory;
use Pucene\Index\PuceneIndexFactory;
use Schranz\Search\SEAL\Schema\Index;
use Schranz\Search\SEAL\Schema\Field;

$driverFactory = new MemoryDriverFactory();

$indexFactory = new PuceneIndexFactory(
    $driverFactory,
    new StandardAnalyzer(),
);

$index = $indexFactory->create(new Index('blog', [
    'id' => new Field\IdentifierField('id'),
    'title' => new Field\TextField('title'),
]));
```
