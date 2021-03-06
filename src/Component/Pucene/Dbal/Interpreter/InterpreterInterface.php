<?php

namespace Pucene\Component\Pucene\Dbal\Interpreter;

use Pucene\Component\Pucene\Compiler\ElementInterface;
use Pucene\Component\Pucene\Dbal\ScoringAlgorithm;

interface InterpreterInterface
{
    public function interpret(ElementInterface $element, PuceneQueryBuilder $queryBuilder, string $index);

    public function scoring(ElementInterface $element, ScoringAlgorithm $scoring, string $index);
}
