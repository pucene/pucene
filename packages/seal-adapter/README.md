# Pucene: Seal Adapter

Adapter package that glues pucene into SEAL universe.

> This is a subtree split of the `pucene/pucene` project create issues in the [main repository](https://github.com/pucene/pucene).

## Usage

```php
<?php

use Doctrine\DBAL\DriverManager;
use Pucene\Analysis\StandardAnalyzer;
use Pucene\DbalDriver\DbalDriverFactory;
use Pucene\Index\PuceneIndexFactory;
use Pucene\SealAdapter\PuceneAdapter;
use Schranz\Search\SEAL\Schema\Index;
use Schranz\Search\SEAL\Schema\Schema;
use Schranz\Search\SEAL\Schema\Field;

$dbalConnection = DriverManager::getConnection([
    'url' => 'mysql://root@127.0.0.1:3306/pucene?serverVersion=8.0',
]);
$driverFactory = new DbalDriverFactory($dbalConnection);

$adapter = PuceneAdapter::createFromFactories(
    new PuceneIndexFactory(
        $driverFactory,
        new StandardAnalyzer(),
    ),
    $driverFactory,
);

$engine = new Engine(
    $adapter,
    new Schema([
        'blog' => new Index('blog', [
            'id' => new Field\IdentifierField('id'),
            'title' => new Field\TextField('title'),
        ]),
    ]),
);
```
