<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Driver;

use Pucene\Index\Driver\DriverFactoryInterface;
use Pucene\Index\Driver\DriverInterface;
use Pucene\Index\Driver\MemoryDriver;
use Schranz\Search\SEAL\Schema\Index;

class MemoryDriverFactory implements DriverFactoryInterface
{
    public function create(Index $index): DriverInterface
    {
        return new MemoryDriver();
    }
}
