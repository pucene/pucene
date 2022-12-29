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

use Pucene\Analysis\Analyzer;
use Pucene\Analysis\CharacterFilter\ChainCharacterFilter;
use Pucene\Analysis\CharacterFilter\CharacterFilterInterface;
use Pucene\Analysis\Token;
use Pucene\Analysis\TokenFilter\ChainTokenFilter;
use Pucene\Analysis\TokenFilter\TokenFilterInterface;
use Pucene\Analysis\Tokenizer\TokenizerInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

$style = new SymfonyStyle(new ArgvInput(), new ConsoleOutput());
$table = $style->createTable();

$style->writeln((string) file_get_contents(__DIR__ . '/logo.txt'));

$style->title('Custom Analyzer');

$style->text(<<<EOF
This example analyses a text with a highly customized analyzer where all the parts of the analyzer:

EOF);

$style->listing([
    'Character filter: Remove all numbers',
    'Tokenizer: Split sentence into tokens by using whitespaces',
    'Token filter: Add additional token with the reversed term.',
]);

$style->note('Analysing: "The 2 QUICK Brown-Foxes jumped over the lazy dog\'s bone."');

$analyzer = new Analyzer(
    new ChainCharacterFilter(
        [
            new class () implements CharacterFilterInterface {
                public function filter(string $input): string
                {
                    return (string) preg_replace('/[0-9]/', '', $input);
                }
            }
        ]
    ),
    new class () implements TokenizerInterface {
        public function tokenize(string $input): array
        {
            $tokens = [];

            $start = 0;
            $position = 0;
            $term = '';
            for ($i = 0, $length = strlen($input); $i < $length; ++$i) {
                if (preg_match('/[^\s]/', $input[$i])) {
                    $term .= $input[$i];

                    continue;
                }

                if (strlen($term) > 0) {
                    $tokens[] = new Token($term, $start, $i, $position);
                    ++$position;
                }

                $start = $i + 1;
                $term = '';
            }

            if (strlen($term) > 0) {
                $tokens[] = new Token($term, $start, $i - 1, $position);
            }

            return $tokens;
        }
    },
    new ChainTokenFilter(
        [
            new class () implements TokenFilterInterface {
                public function filter(Token $token): array
                {
                    return [
                        $token,
                        new Token(strrev($token->term), $token->startOffset, $token->endOffset, $token->position),
                    ];
                }
            }
        ]
    ),
);
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
