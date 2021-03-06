<?php

namespace Pucene\Tests\Functional\Comparison;

use Pucene\Component\QueryBuilder\Query\MatchAllQuery;
use Pucene\Component\QueryBuilder\Search;
use Pucene\Component\QueryBuilder\Sort\IdSort;

/**
 * This testcase compares elasticsearch with pucene results for the "match_all" query.
 */
class MatchAllComparisonTest extends ComparisonTestCase
{
    public function testSearchMatchAll()
    {
        $search = new Search(new MatchAllQuery());
        $search->addSort(new IdSort());

        $this->assertSearch($search);
    }
}
