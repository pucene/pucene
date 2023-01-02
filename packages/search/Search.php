<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\Search;

use Pucene\Search\Element\CompositeElement;
use Pucene\Search\Element\CompositeType;
use Pucene\Search\Element\ElementInterface;
use Pucene\Search\Element\IdElement;
use Pucene\Search\Element\TermElement;
use Pucene\Search\Element\ValueElement;
use Pucene\Search\QueryBuilder\ExpressionInterface;
use Pucene\Search\QueryBuilder\QueryBuilderInterface;
use Pucene\Search\QueryBuilder\QueryInterface;
use Pucene\Search\QueryBuilder\QueryResult;

class Search
{
    public function __construct(
        private readonly ElementInterface $element,
        private readonly QueryBuilderInterface $queryBuilder,
    ) {
    }

    public function execute(?int $from, ?int $size): QueryResult
    {
        $query = $this->createQuery();
        $query->setFrom($from ?? 0);
        $query->setSize($size ?? 10);

        return $query->execute();
    }

    public function count(): int
    {
        $query = $this->createQuery();

        return $query->count();
    }

    private function createQuery(): QueryInterface
    {
        $expression = $this->interpretElement($this->element);
        $this->queryBuilder->add($expression);

        return $this->queryBuilder->getQuery();
    }

    private function interpretElement(ElementInterface $element): ExpressionInterface
    {
        if ($element instanceof CompositeElement) {
            return $this->interpretCompositionElement($element);
        } elseif ($element instanceof TermElement) {
            return $this->queryBuilder->expr()
                ->terms($element->field)
                ->equals($element->term);
        } elseif ($element instanceof ValueElement) {
            return $this->queryBuilder->expr()
                ->values($element->field, $element->type)
                ->equals($element->value);
        } elseif ($element instanceof IdElement) {
            return $this->queryBuilder->expr()
                ->id($element->id);
        }

        throw new \RuntimeException('Not supported');
    }

    private function interpretCompositionElement(CompositeElement $element): ExpressionInterface
    {
        if (0 === count($element->elements)) {
            throw new \RuntimeException('Not supported');
        }

        if (1 === count($element->elements)) {
            return $this->interpretElement($element->elements[0]);
        }

        $expression = $this->queryBuilder->expr()->and();
        if (CompositeType::OR === $element->type) {
            $expression = $this->queryBuilder->expr()->or();
        }

        foreach ($element->elements as $element) {
            $expression->add($this->interpretElement($element));
        }

        return $expression;
    }
}
