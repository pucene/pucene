<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\DbalDriver\QueryBuilder;

use Pucene\Search\QueryBuilder\ExpressionBuilderInterface;
use Pucene\Search\QueryBuilder\ExpressionInterface;
use Pucene\Search\QueryBuilder\QueryBuilderInterface;
use Pucene\Search\QueryBuilder\QueryInterface;

class QueryBuilder implements QueryBuilderInterface
{
    public function expr(): ExpressionBuilderInterface
    {
        // TODO: Implement expr() method.
    }

    public function add(ExpressionInterface $expression): void
    {
        // TODO: Implement add() method.
    }

    public function getQuery(): QueryInterface
    {
        // TODO: Implement getQuery() method.
    }
}
