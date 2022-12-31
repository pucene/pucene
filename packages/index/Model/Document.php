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

use Schranz\Search\SEAL\Schema\Index;

class Document
{
    /**
     * @param array <string, mixed> $source
     * @param Field[] $fields
     */
    public function __construct(
        public Index $index,
        public string $id,
        public array $source,
        public array $fields,
    ) {
    }
}
