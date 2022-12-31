<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\Index\Driver;

use Pucene\Index\Model\Document;

class MemoryRepository implements RepositoryInterface
{
    /**
     * @param Document[] $documents
     */
    public function __construct(
        public array $documents = [],
    ) {
    }

    public function persist(Document $document): void
    {
        $this->documents[$document->id] = $document;
    }

    public function remove(string $identifier): void
    {
        unset($this->documents[$identifier]);
    }
}
