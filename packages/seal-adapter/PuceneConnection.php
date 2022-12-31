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
use Schranz\Search\SEAL\Search\Result;
use Schranz\Search\SEAL\Search\Search;

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
        // TODO: Implement search() method.

        return new Result($this->mockGenerator(), 1);
    }

    private function mockGenerator(): \Generator
    {
        yield ['id' => 1];
    }
}
