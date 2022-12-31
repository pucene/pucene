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
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Pucene\Index\Driver\DriverInterface;
use Pucene\Index\Driver\RepositoryInterface;
use Schranz\Search\SEAL\Schema\Index;

class DbalDriver implements DriverInterface
{
    /**
     * @var AbstractSchemaManager<MySQLPlatform>
     */
    private AbstractSchemaManager $schemaManager;

    private Schema $schema;

    public function __construct(
        private Connection $connection,
        private Index $index,
    ) {
        $this->schemaManager = $this->connection->createSchemaManager();
        $this->schema = new Schema($this->index);
    }

    public function isInitialized(): bool
    {
        return $this->schemaManager->tablesExist([$this->schema->getDocumentsTableName()]);
    }

    public function initialize(): void
    {
        if ($this->schemaManager->tablesExist($this->schema->getDocumentsTableName())) {
            return;
        }

        foreach ($this->schema->toSql($this->connection->getDatabasePlatform()) as $sql) {
            $this->connection->exec($sql);
        }
    }

    public function drop(): void
    {
        if (!$this->isInitialized()) {
            return;
        }

        foreach ($this->schema->toDropSql($this->connection->getDatabasePlatform()) as $sql) {
            $this->connection->exec($sql);
        }
    }

    public function createRepository(): RepositoryInterface
    {
        return new DbalRepository(
            $this->connection,
            $this->index,
            $this->schema,
        );
    }
}
