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
use Pucene\Analysis\TokenFilter\LowercaseTokenFilter;

class LowercaseTokenFilterTest extends TestCase
{
    public function testFilter(): void
    {
        $filter = new LowercaseTokenFilter();

        $result = $filter->filter(new Token('TEST', 0, 5, 1));
        $this->assertCount(1, $result);
        $this->assertSame('test', $result[0]->term);
        $this->assertSame(0, $result[0]->startOffset);
        $this->assertSame(5, $result[0]->endOffset);
        $this->assertSame(1, $result[0]->position);
    }
}
