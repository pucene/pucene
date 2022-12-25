<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\Analysis\Testing\TokenFilter;

use PHPUnit\Framework\TestCase;
use Pucene\Analysis\Token;
use Pucene\Analysis\TokenFilter\GermanStopTokenFilter;

class GermanStopTokenFilterTest extends TestCase
{
    /**
     * @return mixed[]
     */
    public function provideFilterData(): array
    {
        return [
            ['test', ['test']],
            ['TEST', ['TEST']],
            ['und', []],
            ['UND', []],
        ];
    }

    /**
     * @param string[] $expected
     *
     * @dataProvider provideFilterData
     */
    public function testFilter(string $input, array $expected): void
    {
        $filter = new GermanStopTokenFilter();

        $this->assertSame(
            $expected,
            \array_map(fn (Token $token) => $token->term, $filter->filter(new Token($input, 0, 5, 0))),
        );
    }
}
