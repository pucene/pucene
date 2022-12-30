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
use Pucene\Analysis\CharacterFilter\StandardCharacterFilter;

class StandardCharacterFilterTest extends TestCase
{
    public function testFilter(): void
    {
        $filter = new StandardCharacterFilter();

        $this->assertSame('input', $filter->filter('input'));
    }
}
