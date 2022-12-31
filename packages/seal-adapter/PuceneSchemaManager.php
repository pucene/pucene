<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\SealAdapter;

use Pucene\Index\Driver\DriverFactoryInterface;
use Schranz\Search\SEAL\Adapter\SchemaManagerInterface;
use Schranz\Search\SEAL\Schema\Index;

class PuceneSchemaManager implements SchemaManagerInterface
{
    public function __construct(
        private DriverFactoryInterface $driverFactory,
    ) {
    }

    public function existIndex(Index $index): bool
    {
        return $this->driverFactory->create($index)->isInitialized();
    }

    public function dropIndex(Index $index): void
    {
        $this->driverFactory->create($index)->drop();
    }

    public function createIndex(Index $index): void
    {
        $this->driverFactory->create($index)->initialize();
    }
}
