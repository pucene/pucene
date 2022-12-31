<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\Index;

use Pucene\Analysis\AnalyzerInterface;
use Pucene\Index\Driver\DriverFactoryInterface;
use Schranz\Search\SEAL\Schema\Index;

class PuceneIndexFactory
{
    public function __construct(
        private DriverFactoryInterface $driverFactory,
        private AnalyzerInterface $analyzer,
    ) {
    }

    public function create(Index $index): PuceneIndex
    {
        return new PuceneIndex(
            $this->analyzer,
            $index,
            $this->driverFactory->create($index),
        );
    }
}
