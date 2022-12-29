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

class StandardTokenFilter implements TokenFilterInterface
{
    public function filter(Token $token): array
    {
        return [$token];
    }
}
