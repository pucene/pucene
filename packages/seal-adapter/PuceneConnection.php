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

use Pucene\Index\PuceneIndexFactory;
use Schranz\Search\SEAL\Adapter\ConnectionInterface;
use Schranz\Search\SEAL\Schema\Index;
use Schranz\Search\SEAL\Search\Condition\IdentifierCondition;
use Schranz\Search\SEAL\Search\Result;
use Schranz\Search\SEAL\Search\Search;
use Pucene\Search as PuceneSearch;

class PuceneConnection implements ConnectionInterface
{
    public function __construct(
        private PuceneIndexFactory $indexFactory,
    ) {
    }

    /**
     * @param mixed[] $document
     *
     * @return mixed[]
     */
    public function save(Index $index, array $document): array
    {
        return $this->indexFactory->create($index)->save($document);
    }

    public function delete(Index $index, string $identifier): void
    {
        $this->indexFactory->create($index)->delete($identifier);
    }

    public function search(Search $search): Result
    {
        /** @var Index $index */
        $index = $search->indexes[0];

        $composite = new PuceneSearch\Element\CompositeElement(
            PuceneSearch\Element\CompositeType::AND,
            array_map(function (mixed $condition) use ($index) {
                return match (get_class($condition)) {
                    IdentifierCondition::class => new PuceneSearch\Element\TermElement(
                        $index->getIdentifierField()->name,
                        $condition->identifier,
                    ),
                    default => throw new \Exception(),
                };
            }, $search->filters),
        );

        $puceneSearch = $this->indexFactory->create($index)->createSearch($composite);
        $result = $puceneSearch->execute($search->offset, $search->limit);

        return new Result($result->hits, $result->total);
    }
}
