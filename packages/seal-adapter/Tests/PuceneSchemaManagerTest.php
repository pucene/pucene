<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tests;

use Doctrine\DBAL\DriverManager;
use Pucene\DbalDriver\DbalDriverFactory;
use Pucene\SealAdapter\PuceneSchemaManager;
use Schranz\Search\SEAL\Testing\AbstractSchemaManagerTestCase;

class PuceneSchemaManagerTest extends AbstractSchemaManagerTestCase
{
    public static function setUpBeforeClass(): void
    {
        $dbalConnection = DriverManager::getConnection([
            'url' => 'mysql://root@127.0.0.1:3306/pucene?serverVersion=8.0',
        ]);
        $driverFactory = new DbalDriverFactory($dbalConnection);

        self::$schemaManager = new PuceneSchemaManager($driverFactory);
    }
}
