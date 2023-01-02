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

interface ExpressionBuilderInterface
{
    public function terms(string $field): TermsExpressionBuilderInterface;

    public function values(string $field, string $type): ValuesExpressionBuilderInterface;

    public function and(): CompositeExpressionInterface;

    public function or(): CompositeExpressionInterface;

    public function id(string $id): ExpressionInterface;
}
