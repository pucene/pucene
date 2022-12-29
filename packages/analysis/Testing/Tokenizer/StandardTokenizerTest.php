<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\Analysis\Testing\Tokenizer;

use PHPUnit\Framework\TestCase;
use Pucene\Analysis\Token;
use Pucene\Analysis\Tokenizer\StandardTokenizer;

class StandardTokenizerTest extends TestCase
{
    /**
     * @return mixed[]
     */
    public function provideTokenizeData(): array
    {
        return [
            [
                'The 2 QUICK Brown-Foxes jumped over the lazy dog\'s bone.',
                [
                    new Token('The', 0, 3, 0),
                    new Token('2', 4, 5, 1),
                    new Token('QUICK', 6, 11, 2),
                    new Token('Brown', 12, 17, 3),
                    new Token('Foxes', 18, 23, 4),
                    new Token('jumped', 24, 30, 5),
                    new Token('over', 31, 35, 6),
                    new Token('the', 36, 39, 7),
                    new Token('lazy', 40, 44, 8),
                    new Token('dog\'s', 45, 50, 9),
                    new Token('bone', 51, 55, 10),
                ],
            ],
        ];
    }

    /**
     * @param Token[] $expected
     *
     * @dataProvider provideTokenizeData
     */
    public function testTokenize(string $input, array $expected): void
    {
        $tokenizer = new StandardTokenizer();

        $this->assertEquals($expected, $tokenizer->tokenize($input));
    }
}
