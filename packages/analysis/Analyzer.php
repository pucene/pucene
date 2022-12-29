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

use Pucene\Analysis\CharacterFilter\CharacterFilterInterface;
use Pucene\Analysis\TokenFilter\TokenFilterInterface;
use Pucene\Analysis\Tokenizer\TokenizerInterface;

class Analyzer implements AnalyzerInterface
{
    public function __construct(
        protected CharacterFilterInterface $characterFilter,
        protected TokenizerInterface $tokenizer,
        protected TokenFilterInterface $tokenFilter
    ) {
    }

    public function analyze(string $fieldContent): array
    {
        $input = $this->characterFilter->filter($fieldContent);
        $tokens = $this->tokenizer->tokenize($input);

        $result = [];
        foreach ($tokens as $token) {
            $result = array_merge($result, $this->tokenFilter->filter($token));
        }

        return $result;
    }
}
