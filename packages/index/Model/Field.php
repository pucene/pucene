<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\Index\Model;

use Pucene\Analysis\Token;
use Schranz\Search\SEAL\Schema\FieldType;

class Field
{
    public int $numberOfTerms;

    /**
     * @param Token[] $tokens
     */
    public function __construct(
        public string $name,
        public array $tokens,
        public mixed $value,
        public FieldType $type = FieldType::TEXT,
    ) {
        $this->numberOfTerms = \count($this->tokens);
    }
}
