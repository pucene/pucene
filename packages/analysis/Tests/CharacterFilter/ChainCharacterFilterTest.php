<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\Analysis\Tests\CharacterFilter;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Pucene\Analysis\CharacterFilter\ChainCharacterFilter;
use Pucene\Analysis\CharacterFilter\CharacterFilterInterface;

class ChainCharacterFilterTest extends TestCase
{
    use ProphecyTrait;

    public function testFilter(): void
    {
        $filter1 = $this->prophesize(CharacterFilterInterface::class);
        $filter1->filter('input')->shouldBeCalledTimes(1)->willReturn('output1');
        $filter2 = $this->prophesize(CharacterFilterInterface::class);
        $filter2->filter('output1')->shouldBeCalledTimes(1)->willReturn('output2');

        $filter = new ChainCharacterFilter([
            $filter1->reveal(),
            $filter2->reveal(),
        ]);

        $this->assertSame('output2', $filter->filter('input'));
    }
}
