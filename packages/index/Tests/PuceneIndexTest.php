<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\Index\Tests;

use PHPUnit\Framework\TestCase;
use Pucene\Analysis\StandardAnalyzer;
use Pucene\Analysis\Token;
use Pucene\Index\Driver\MemoryDriver;
use Pucene\Index\Model\Document;
use Pucene\Index\PuceneIndex;
use Schranz\Search\SEAL\Schema\Field\IdentifierField;
use Schranz\Search\SEAL\Schema\Field\TextField;
use Schranz\Search\SEAL\Schema\Index;

class PuceneIndexTest extends TestCase
{
    private static Index $indexMetadata;
    private static MemoryDriver $driver;
    private static PuceneIndex $index;

    public static function setUpBeforeClass(): void
    {
        self::$indexMetadata = new Index('news', [
            'id' => new IdentifierField('id'),
            'title' => new TextField('title'),
        ]);

        self::$driver = new MemoryDriver();
        self::$index = new PuceneIndex(new StandardAnalyzer(), self::$indexMetadata, self::$driver);
    }

    protected function setUp(): void
    {
        if (self::$driver->isInitialized()) {
            self::$driver->drop();
        }

        self::$driver->initialize();
    }

    public function testSave(): void
    {
        self::$index->save([
            'id' => '123-123-123',
            'title' => 'Test',
        ]);

        $this->assertCount(1, self::$driver->createRepository()->documents);

        $document = self::$driver->createRepository()->documents['123-123-123'];
        $this->assertCount(2, $document->fields);
        $this->assertSame(['123-123-123'], $document->fields[0]->value);
        $this->assertCount(0, $document->fields[0]->tokens);
        $this->assertSame(['Test'], $document->fields[1]->value);
        $this->assertCount(1, $document->fields[1]->tokens);
        $this->assertEquals(new Token('test', 0, 3, 0), $document->fields[1]->tokens[0]);
    }

    public function testDelete(): void
    {
        self::$driver->createRepository()->persist(new Document(self::$indexMetadata, '123-123-123', [
            'id' => '123-123-123',
            'title' => 'Test',
        ], []));

        self::$index->delete('123-123-123');

        $this->assertCount(0, self::$driver->createRepository()->documents);
    }
}
