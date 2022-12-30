<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\Analysis\Tests\TokenFilter;

use PHPUnit\Framework\TestCase;
use Pucene\Analysis\Token;
use Pucene\Analysis\TokenFilter\StandardTokenFilter;

class StandardTokenFilterTest extends TestCase
{
    public function testFilter(): void
    {
        $filter = new StandardTokenFilter();

        $token = new Token('test', 0, 5, 1);
        $this->assertSame([$token], $filter->filter($token));
    }
}
