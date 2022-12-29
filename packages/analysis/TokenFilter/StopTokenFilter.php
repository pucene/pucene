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

/**
 * TODO: stopwords_path, ignore_case, remove_trailing.
 */
class StopTokenFilter implements TokenFilterInterface
{
    /**
     * @param string[] $stopWords
     */
    public function __construct(
        private array $stopWords,
    ) {
    }

    public function filter(Token $token): array
    {
        if (in_array(mb_strtolower($token->term), $this->stopWords, true)) {
            return [];
        }

        return [$token];
    }
}
