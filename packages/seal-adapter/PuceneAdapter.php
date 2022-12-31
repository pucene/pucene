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
use Pucene\Index\PuceneIndexFactory;
use Schranz\Search\SEAL\Adapter\AdapterInterface;
use Schranz\Search\SEAL\Adapter\ConnectionInterface;
use Schranz\Search\SEAL\Adapter\SchemaManagerInterface;

class PuceneAdapter implements AdapterInterface
{
    public static function createFromFactories(
        PuceneIndexFactory $indexFactory,
        DriverFactoryInterface $driverFactory,
    ): self {
        return new self(
            new PuceneConnection($indexFactory),
            new PuceneSchemaManager($driverFactory),
        );
    }

    private readonly ConnectionInterface $connection;

    private readonly SchemaManagerInterface $schemaManager;

    public function __construct(
        ConnectionInterface $connection,
        SchemaManagerInterface $schemaManager,
    ) {
        $this->connection = $connection;
        $this->schemaManager = $schemaManager;
    }

    public function getSchemaManager(): SchemaManagerInterface
    {
        return $this->schemaManager;
    }

    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }
}
