<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\Analysis\CharacterFilter;

class ChainCharacterFilter implements CharacterFilterInterface
{
    /**
     * @param CharacterFilterInterface[] $filters
     */
    public function __construct(
        private array $filters,
    ) {
    }

    public function filter(string $input): string
    {
        foreach ($this->filters as $filter) {
            $input = $filter->filter($input);
        }

        return $input;
    }
}
