<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require dirname(__DIR__) . '/vendor/autoload.php';

use Pucene\Analysis\StandardAnalyzer;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

$style = new SymfonyStyle(new ArgvInput(), new ConsoleOutput());
$table = $style->createTable();

$style->writeln((string) file_get_contents(__DIR__ . '/logo.txt'));

$style->title('Standard Analyzer');

$style->text(<<<EOF
This example analyses a text with the standard analyzer:

EOF);

$style->note('Analysing: "The 2 QUICK Brown-Foxes jumped over the lazy dog\'s bone."');

$analyzer = new StandardAnalyzer();
$tokens = $analyzer->analyze('The 2 QUICK Brown-Foxes jumped over the lazy dog\'s bone.');

$table->setHeaders([
    'Term',
    'Start Offset',
    'End Offset',
    'Position',
]);

foreach ($tokens as $token) {
    $table->addRow([
        $token->term,
        $token->startOffset,
        $token->endOffset,
        $token->position,
    ]);
}

$table->render();
$style->newLine();
