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
use Pucene\Analysis\StandardAnalyzer;
use Pucene\DbalDriver\DbalDriverFactory;
use Pucene\Index\PuceneIndexFactory;
use Pucene\SealAdapter\PuceneAdapter;
use Schranz\Search\SEAL\Testing\AbstractAdapterTestCase;

class PuceneAdapterTest extends AbstractAdapterTestCase
{
    public static function setUpBeforeClass(): void
    {
        $dbalConnection = DriverManager::getConnection([
            'url' => 'mysql://root@127.0.0.1:3306/pucene?serverVersion=8.0',
        ]);
        $driverFactory = new DbalDriverFactory($dbalConnection);

        self::$adapter = PuceneAdapter::createFromFactories(
            new PuceneIndexFactory(
                $driverFactory,
                new StandardAnalyzer(),
            ),
            $driverFactory,
        );
    }

    public function testDocument(): void
    {
        $this->markTestSkipped();
    }
}
