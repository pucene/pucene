<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\Analysis;

class Token
{
    public function __construct(
        public string $term,
        public int $startOffset,
        public int $endOffset,
        public int $position
    ) {
    }

    public function getEncodedTerm(): string
    {
        return utf8_encode($this->term);
    }
}
