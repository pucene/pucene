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
use Doctrine\DBAL\Result;
use Pucene\DbalDriver\Schema;
use Pucene\Index\Driver\RepositoryInterface;
use Pucene\Index\Model\Document;
use Pucene\Index\Model\Field;
use Schranz\Search\SEAL\Schema\FieldType;
use Schranz\Search\SEAL\Schema\Index;

class DbalRepository implements RepositoryInterface
{
    public function __construct(
        private Connection $connection,
        private Index $index,
        private Schema $schema,
    ) {
    }

    public function persist(Document $document): void
    {
        $this->insertDocument($this->schema, $this->index, $document->id, $document->source);

        foreach ($document->fields as $field) {
            $this->insertField(
                $this->schema,
                $document->id,
                $field->name,
                $field->numberOfTerms,
            );

            $this->insertValue($this->schema, $document->id, $field);
            $this->insertTokens($this->schema, $document->id, $field);
        }
    }

    public function remove(string $identifier): void
    {
        $this->connection->delete($this->schema->getDocumentsTableName(), ['id' => $identifier]);
    }

    /**
     * @param mixed[] $document
     */
    protected function insertDocument(Schema $schema, Index $index, string $id, array $document): void
    {
        $this->connection->insert(
            $schema->getDocumentsTableName(),
            [
                'id' => $id,
                'type' => $index->name,
                'document' => json_encode($document),
                'indexed_at' => new \DateTime(),
            ],
            [
                \PDO::PARAM_STR,
                \PDO::PARAM_STR,
                \PDO::PARAM_STR,
                'datetime',
            ]
        );
    }

    protected function insertField(Schema $schema, string $documentId, string $fieldName, int $fieldLength): void
    {
        $this->connection->insert(
            $schema->getFieldsTableName(),
            [
                'document_id' => $documentId,
                'field_name' => $fieldName,
                'field_length' => $fieldLength,
            ]
        );
    }

    protected function insertValue(Schema $schema, string $documentId, Field $field): void
    {
        $value = $field->value;
        if (!is_array($value)) {
            $value = [$value];
        }

        foreach ($value as $item) {
            if (FieldType::DATETIME === $field->type) {
                $date = new \DateTime($item);
                $item = $date->format('Y-m-d H:i:s');
            } elseif (FieldType::BOOLEAN === $field->type) {
                $item = $item ? 1 : 0;
            }

            $tableName = $schema->getFieldTableName($field->type);
            if (!$tableName) {
                continue;
            }
            $this->connection->insert(
                $tableName,
                [
                    'document_id' => $documentId,
                    'field_name' => $field->name,
                    'value' => $item,
                ],
                [
                    \PDO::PARAM_STR,
                    \PDO::PARAM_STR,
                    \PDO::PARAM_STR,
                    $schema->getColumnType($field->type),
                ]
            );
        }
    }

    protected function insertTokens(Schema $schema, string $documentId, Field $field): void
    {
        $fieldTerms = [];
        foreach ($field->tokens as $token) {
            if (!array_key_exists($token->getEncodedTerm(), $fieldTerms)) {
                $fieldTerms[$token->getEncodedTerm()] = 0;
            }

            ++$fieldTerms[$token->getEncodedTerm()];

            if ($fieldTerms[$token->getEncodedTerm()] > 1) {
                continue;
            }

            $this->insertTerm($schema, $token->getEncodedTerm());
            $this->insertToken(
                $schema,
                $documentId,
                $field->name,
                $token->getEncodedTerm(),
                $field->numberOfTerms
            );
        }

        // update term frequency
        foreach ($fieldTerms as $term => $frequency) {
            $this->connection->update(
                $schema->getDocumentTermsTableName(),
                [
                    'term_frequency' => $frequency,
                ],
                [
                    'document_id' => $documentId,
                    'field_name' => $field->name,
                    'term' => $term,
                ]
            );
        }
    }

    protected function insertToken(
        Schema $schema,
        string $documentId,
        string $fieldName,
        string $term,
        int $fieldLength,
    ): void {
        $this->connection->insert(
            $schema->getDocumentTermsTableName(),
            [
                'document_id' => $documentId,
                'field_name' => $fieldName,
                'term' => $term,
                'field_length' => $fieldLength,
            ]
        );
    }

    protected function insertTerm(Schema $schema, string $term): void
    {
        $result = $this->connection->createQueryBuilder()
            ->select('term.term')
            ->from($schema->getTermsTableName(), 'term')
            ->where('term.term = :term')
            ->setParameter('term', $term)
            ->execute();

        if ($result instanceof Result && $result->fetch()) {
            return;
        }

        $this->connection->insert(
            $schema->getTermsTableName(),
            [
                'term' => $term,
            ]
        );
    }
}
