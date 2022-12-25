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

use Pucene\Analysis\CharacterFilter\ChainCharacterFilter;
use Pucene\Analysis\CharacterFilter\StandardCharacterFilter;
use Pucene\Analysis\TokenFilter\ChainTokenFilter;
use Pucene\Analysis\TokenFilter\LowercaseTokenFilter;
use Pucene\Analysis\Tokenizer\StandardTokenizer;

class StandardAnalyzer extends Analyzer
{
    public function __construct()
    {
        parent::__construct(
            new ChainCharacterFilter(
                [
                    new StandardCharacterFilter(),
                ]
            ),
            new StandardTokenizer(),
            new ChainTokenFilter(
                [
                    new LowercaseTokenFilter(),
                ]
            ),
        );
    }
}
