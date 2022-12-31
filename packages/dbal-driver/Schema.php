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

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Schema as DoctrineSchema;
use Schranz\Search\SEAL\Schema\FieldType;
use Schranz\Search\SEAL\Schema\Index;

/**
 * @internal
 */
class Schema
{
    private string $prefix;

    private DoctrineSchema $schema;

    /**
     * @var string[]
     */
    private array $tableNames;

    public function __construct(
        private Index $index,
    ) {
        $this->prefix = $this->index->name;
        $this->schema = new DoctrineSchema();

        $this->createDocumentsTable();
        $this->createFieldsTable();
        $this->createTermsTable();
        $this->createDocumentTermsTable();
        $this->createDocumentFieldsTables();
    }

    private function createDocumentsTable(): void
    {
        $this->tableNames['documents'] = sprintf('pu_%s_documents', $this->prefix);

        $documents = $this->schema->createTable($this->tableNames['documents']);
        $documents->addColumn('id', 'string', ['length' => 255]);
        $documents->addColumn('number', 'integer', ['autoincrement' => true]);
        $documents->addColumn('type', 'string', ['length' => 255]);
        $documents->addColumn('document', 'json');
        $documents->addColumn('indexed_at', 'datetime');

        $documents->setPrimaryKey(['number']);
        $documents->addIndex(['type']);
        $documents->addUniqueIndex(['id']);
    }

    private function createFieldsTable(): void
    {
        $this->tableNames['fields'] = sprintf('pu_%s_fields', $this->prefix);

        $table = $this->schema->createTable($this->tableNames['fields']);
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('document_id', 'string', ['length' => 255]);
        $table->addColumn('field_name', 'string', ['length' => 255]);
        $table->addColumn('field_length', 'float', ['default' => 0]);

        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint(
            $this->tableNames['documents'],
            ['document_id'],
            ['id'],
            ['onDelete' => 'CASCADE']
        );
        $table->addIndex(['field_name']);
    }

    private function createDocumentTermsTable(): void
    {
        $this->tableNames['document_terms'] = sprintf('pu_%s_document_terms', $this->prefix);

        $fields = $this->schema->createTable($this->tableNames['document_terms']);
        $fields->addColumn('id', 'integer', ['autoincrement' => true]);
        $fields->addColumn('document_id', 'string', ['length' => 255]);
        $fields->addColumn('field_name', 'string', ['length' => 255]);
        $fields->addColumn('term', 'string', ['length' => 255]);
        $fields->addColumn('term_frequency', 'integer', ['default' => 0]);
        $fields->addColumn('field_length', 'float', ['default' => 0]);

        $fields->setPrimaryKey(['id']);
        $fields->addForeignKeyConstraint(
            $this->tableNames['documents'],
            ['document_id'],
            ['id'],
            ['onDelete' => 'CASCADE']
        );
        $fields->addForeignKeyConstraint($this->tableNames['terms'], ['term'], ['term']);
        $fields->addIndex(['field_name', 'term']);
    }

    private function createTermsTable(): void
    {
        $this->tableNames['terms'] = sprintf('pu_%s_terms', $this->prefix);

        $fields = $this->schema->createTable($this->tableNames['terms']);
        $fields->addColumn('term', 'string', ['length' => 255]);

        $fields->setPrimaryKey(['term']);
        $fields->addIndex(['term']);
    }

    /**
     * Create table per mapping type.
     *
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/mapping-types.html
     */
    private function createDocumentFieldsTables(): void
    {
        // Text types
        $this->createDocumentFieldsTable(FieldType::IDENTIFIER, 'text');
        $this->createDocumentFieldsTable(FieldType::TEXT, 'text');
        // TODO KEYWORD $this->createDocumentFieldsTable(FieldType::KEYWORD, 'text');

        // Numeric types
        $this->createDocumentFieldsTable(FieldType::FLOAT, 'float');
        $this->createDocumentFieldsTable(FieldType::INTEGER, 'bigint');

        // Date types
        $this->createDocumentFieldsTable(FieldType::DATETIME, 'datetime');

        // Boolean types
        $this->createDocumentFieldsTable(FieldType::BOOLEAN, 'boolean');
    }

    /**
     * @param FieldType $type
     * @param string $columnType
     * @param array<string, mixed> $options
     */
    private function createDocumentFieldsTable(FieldType $type, string $columnType, array $options = []): void
    {
        $tableName = sprintf('document_field_%ss', strtolower($type->name));
        $this->tableNames[$tableName] = sprintf('pu_%s_' . $tableName, $this->prefix);

        $fields = $this->schema->createTable($this->tableNames[$tableName]);
        $fields->addColumn('id', 'integer', ['autoincrement' => true]);
        $fields->addColumn('document_id', 'string', ['length' => 255]);
        $fields->addColumn('field_name', 'string', ['length' => 255]);
        $fields->addColumn(
            'value',
            $columnType,
            array_merge(
                ['notnull' => false],
                $options
            )
        );

        $fields->setPrimaryKey(['id']);
        $fields->addForeignKeyConstraint(
            $this->tableNames['documents'],
            ['document_id'],
            ['id'],
            ['onDelete' => 'CASCADE']
        );
        $fields->addIndex(['field_name']);

        // Text and blobs can't be indexed.
        if (!in_array($columnType, ['text', 'blob'], true)) {
            $fields->addIndex(['value']);
        }
    }

    /**
     * @return string[]
     */
    public function toSql(AbstractPlatform $platform): array
    {
        return $this->schema->toSql($platform);
    }

    /**
     * @return string[]
     */
    public function toDropSql(AbstractPlatform $platform): array
    {
        return $this->schema->toDropSql($platform);
    }

    public function getDocumentsTableName(): string
    {
        return $this->tableNames['documents'];
    }

    public function getFieldsTableName(): string
    {
        return $this->tableNames['fields'];
    }

    public function getFieldTableName(FieldType $type): ?string
    {
        return $this->tableNames[sprintf('document_field_%ss', strtolower($type->name))] ?? null;
    }

    public function getDocumentTermsTableName(): string
    {
        return $this->tableNames['document_terms'];
    }

    public function getTermsTableName(): string
    {
        return $this->tableNames['terms'];
    }

    public function getColumnType(FieldType $type): string
    {
        return match ($type) {
            FieldType::TEXT, FieldType::IDENTIFIER => 'text', // TODO FieldType::KEYWORD
            FieldType::FLOAT => 'float',
            FieldType::INTEGER => 'bigint',
            FieldType::BOOLEAN => 'boolean',
            FieldType::DATETIME => 'datetime',
            default => throw new \Exception('Unexpected match value'),
        };
    }
}
