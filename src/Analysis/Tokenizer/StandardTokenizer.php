<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\Analysis\Tokenizer;

use Pucene\Analysis\Token;

class StandardTokenizer implements TokenizerInterface
{
    public const ACCENTED_CHARACTERS = 'àèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ';

    public function tokenize(string $input): array
    {
        $tokens = [];

        $start = 0;
        $position = 0;
        $term = '';
        for ($i = 0, $length = strlen($input); $i < $length; ++$i) {
            if (preg_match('/[:a-zA-Z' . self::ACCENTED_CHARACTERS . '0-9\']/', $input[$i])) {
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
}
