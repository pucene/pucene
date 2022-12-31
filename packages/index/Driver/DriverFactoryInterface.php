<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\Index\Driver;

use Schranz\Search\SEAL\Schema\Index;

interface DriverFactoryInterface
{
    public function create(Index $index): DriverInterface;
}
