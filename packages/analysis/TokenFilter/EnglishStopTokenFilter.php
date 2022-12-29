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

class EnglishStopTokenFilter extends StopTokenFilter
{
    public function __construct()
    {
        parent::__construct(StopWords::ENGLISH);
    }
}
