<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\Search\QueryBuilder;

interface QueryBuilderInterface
{
    public function expr(): ExpressionBuilderInterface;

    public function add(ExpressionInterface $expression): void;

    public function getQuery(): QueryInterface;
}