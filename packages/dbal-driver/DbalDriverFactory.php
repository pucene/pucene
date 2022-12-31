<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\DbalDriver;

use Doctrine\DBAL\Connection;
use Pucene\Index\Driver\DriverFactoryInterface;
use Pucene\Index\Driver\DriverInterface;
use Schranz\Search\SEAL\Schema\Index;

class DbalDriverFactory implements DriverFactoryInterface
{
    public function __construct(
        private Connection $connection,
    ) {
    }

    public function create(Index $index): DriverInterface
    {
        return new DbalDriver($this->connection, $index);
    }
}
