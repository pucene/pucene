<?php

$header = <<<EOF
This file is part of Pucene.

(c) asapo.at

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
EOF;

$finder = PhpCsFixer\Finder::create()
    ->in('src')
    ->exclude([
        'src/Analysis/vendor',
    ])
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@PSR12' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
        'header_comment' => ['header' => $header],
    ])
    ->setFinder($finder)
    ;
