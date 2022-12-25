<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\Index;

use Pucene\Analysis\AnalyzerInterface;
use Pucene\Index\Driver\DriverInterface;
use Pucene\Index\Model\Document;
use Pucene\Index\Model\Field;
use Pucene\Search\Element\ElementInterface;
use Pucene\Search\Search;
use Schranz\Search\SEAL\Schema\Field\AbstractField;
use Schranz\Search\SEAL\Schema\FieldType;
use Schranz\Search\SEAL\Schema\Index;

class PuceneIndex
{
    public function __construct(
        private AnalyzerInterface $analyzer,
        private Index $index,
        private DriverInterface $driver,
    ) {
    }

    /**
     * @param mixed[] $documentData
     *
     * @return mixed[]
     */
    public function save(array $documentData): array
    {
        $fields = [];
        foreach ($documentData as $fieldName => $fieldContent) {
            /** @var AbstractField|null $indexField */
            $indexField = $this->index->fields[$fieldName] ?? null;
            if (null === $indexField) {
                continue;
            }

            if (!is_array($fieldContent)) {
                $fieldContent = [$fieldContent];
            }

            $terms = [];
            if ($indexField->type === FieldType::TEXT) {
                foreach ($fieldContent as $content) {
                    if (is_string($content)) {
                        $terms[] = $this->analyzer->analyze($content);
                    }
                }
            }

            $fields[] = new Field(
                $fieldName,
                array_merge(...$terms),
                $fieldContent,
                $indexField->type,
            );
        }

        $identifierField = $this->index->getIdentifierField();

        /** @var string $identifier */
        $identifier = $documentData[$identifierField->name];

        $document = new Document($this->index, $identifier, $documentData, $fields);

        $this->driver->createRepository()->persist($document);

        return $documentData;
    }

    public function delete(string $identifier): void
    {
        $this->driver->createRepository()->remove($identifier);
    }

    public function createSearch(ElementInterface $element): Search
    {
        return new Search($element, $this->driver->createQueryBuilder());
    }
}
