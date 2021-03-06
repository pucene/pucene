<?php

namespace Pucene\Tests\Functional\Comparison;

use Pucene\Component\QueryBuilder\Query\Compound\BoolQuery;
use Pucene\Component\QueryBuilder\Query\FullText\MatchQuery;
use Pucene\Component\QueryBuilder\Query\TermLevel\PrefixQuery;
use Pucene\Component\QueryBuilder\Query\TermLevel\TermQuery;
use Pucene\Component\QueryBuilder\Search;

/**
 * This testcase compares elasticsearch with pucene results for the "bool" query.
 */
class BoolComparisonTest extends ComparisonTestCase
{
    public function provideQueries()
    {
        return [
            [[new TermQuery('title', 'museum')]],
            [[new TermQuery('title', 'museum'), new TermQuery('title', 'arts')]],
            [[new TermQuery('title', 'museum'), new PrefixQuery('title', 'art')]],
            [[new MatchQuery('title', 'Museum Lyon')]],
            [[new MatchQuery('title', 'Museum Lyon'), new MatchQuery('title', 'Art Museum')]],
            [[new MatchQuery('title', 'Museum Lyon'), new TermQuery('title', 'arts')]],
            [
                [
                    (new BoolQuery())->should(new TermQuery('title', 'museum'))->should(new TermQuery('title', 'lyon')),
                    new TermQuery('title', 'arts'),
                ],
            ],
        ];
    }

    /**
     * @dataProvider provideQueries
     */
    public function testShould($queries, $size = 50)
    {
        $query = new BoolQuery();
        foreach ($queries as $innerQuery) {
            $query->should($innerQuery);
        }

        $this->assertSearch((new Search($query))->setSize($size));
    }

    /**
     * @dataProvider provideQueries
     */
    public function testMust($queries, $size = 50)
    {
        $query = new BoolQuery();
        foreach ($queries as $innerQuery) {
            $query->must($innerQuery);
        }

        $this->assertSearch((new Search($query))->setSize($size));
    }

    /**
     * @dataProvider provideQueries
     */
    public function testFilter($queries, $size = 50)
    {
        $query = new BoolQuery();
        foreach ($queries as $innerQuery) {
            $query->must($innerQuery);
        }

        $this->assertSearch((new Search($query))->setSize($size));
    }

    /**
     * @dataProvider provideQueries
     */
    public function testMustNot($queries, $size = 50)
    {
        $query = new BoolQuery();
        foreach ($queries as $innerQuery) {
            $query->must($innerQuery);
        }

        $this->assertSearch((new Search($query))->setSize($size));
    }

    public function testMustAndShouldTermQuery()
    {
        $query = new BoolQuery();
        $query->must(new TermQuery('title', 'museum'));
        $query->should(new TermQuery('title', 'lyon'));

        $this->assertSearch(new Search($query));
    }

    public function testMustAndShouldAndFilterTermQuery()
    {
        $query = new BoolQuery();
        $query->must(new TermQuery('title', 'museum'));
        $query->should(new TermQuery('title', 'lyon'));
        $query->filter(new TermQuery('title', 'arts'));

        $this->assertSearch(new Search($query));
    }

    public function testMustAndShouldAndMustNotAndFilterTermQuery()
    {
        $query = new BoolQuery();
        $query->must(new TermQuery('title', 'museum'));
        $query->should(new TermQuery('title', 'lyon'));
        $query->filter(new TermQuery('title', 'arts'));
        $query->mustNot(new TermQuery('title', 'pushkin'));

        $this->assertSearch(new Search($query));
    }

    public function testShouldTermQueryFloat()
    {
        $query = new BoolQuery();
        $query->must(new TermQuery('title', 'museum'));
        $query->must(new TermQuery('seed', 0.19));

        $this->assertSearch(new Search($query));
    }

    public function testShouldTermQueryKeyword()
    {
        $query = new BoolQuery();
        $query->must(new TermQuery('title', 'museum'));
        $query->must(new TermQuery('title.raw', 'George Washington'));

        $this->assertSearch(new Search($query));
    }

    public function testShouldTermQueryInteger()
    {
        $query = new BoolQuery();
        $query->must(new TermQuery('title', 'museum'));
        $query->must(new TermQuery('pageId', 315));

        $this->assertSearch(new Search($query));
    }

    public function testShouldTermQueryDate()
    {
        $query = new BoolQuery();
        $query->must(new TermQuery('title', 'museum'));
        $query->must(new TermQuery('modified', '2017-11-21T09:39:53Z'));

        $this->assertSearch(new Search($query));
    }
}
