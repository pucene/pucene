<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\Search\QueryBuilder;

class QueryResult
{
    /**
     * @param \Generator<array<string, mixed>> $hits
     */
    public function __construct(
        public readonly \Generator $hits,
        public readonly int $total,
        public readonly int $took,
    ) {
    }
}
