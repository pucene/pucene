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

interface TermsExpressionBuilderInterface
{
    public function equals(string $term): ExpressionInterface;
}
