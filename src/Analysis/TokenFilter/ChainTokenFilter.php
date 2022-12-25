<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\Analysis\TokenFilter;

use Pucene\Analysis\Token;

class ChainTokenFilter implements TokenFilterInterface
{
    /**
     * @param TokenFilterInterface[] $filters
     */
    public function __construct(
        private array $filters,
    ) {
    }

    public function filter(Token $token): array
    {
        $tokens = [$token];
        foreach ($this->filters as $filter) {
            $tokens = $this->doFilter($filter, $tokens);
        }

        return $tokens;
    }

    /**
     * @param Token[] $tokens
     *
     * @return Token[]
     */
    private function doFilter(TokenFilterInterface $filter, array $tokens): array
    {
        $result = [];
        foreach ($tokens as $token) {
            $result = array_merge($result, $filter->filter($token));
        }

        return $result;
    }
}
