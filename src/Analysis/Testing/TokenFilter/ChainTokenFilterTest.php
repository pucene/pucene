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
use Prophecy\PhpUnit\ProphecyTrait;
use Pucene\Analysis\Token;
use Pucene\Analysis\TokenFilter\ChainTokenFilter;
use Pucene\Analysis\TokenFilter\TokenFilterInterface;

class ChainTokenFilterTest extends TestCase
{
    use ProphecyTrait;

    public function testFilter(): void
    {
        $inputToken = new Token('input', 0, 5, 1);
        $outputTokens1 = [
            new Token('output1', 0, 5, 1),
            new Token('output2', 0, 5, 1),
        ];
        $outputTokens21 = [
            new Token('output11', 0, 5, 1),
            new Token('output12', 0, 5, 1),
        ];
        $outputTokens22 = [
            new Token('output21', 0, 5, 1),
            new Token('output22', 0, 5, 1),
        ];

        $filter1 = $this->prophesize(TokenFilterInterface::class);
        $filter1->filter($inputToken)->shouldBeCalledTimes(1)->willReturn($outputTokens1);
        $filter2 = $this->prophesize(TokenFilterInterface::class);
        $filter2->filter($outputTokens1[0])->shouldBeCalledTimes(1)->willReturn($outputTokens21);
        $filter2->filter($outputTokens1[1])->shouldBeCalledTimes(1)->willReturn($outputTokens22);

        $filter = new ChainTokenFilter([
            $filter1->reveal(),
            $filter2->reveal(),
        ]);

        $this->assertSame(array_merge($outputTokens21, $outputTokens22), $filter->filter($inputToken));
    }
}
