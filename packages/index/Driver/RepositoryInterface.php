<?php

/*
 * This file is part of Pucene.
 *
 * (c) asapo.at
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Pucene\Index\Driver;

use Pucene\Index\Model\Document;

interface RepositoryInterface
{
    public function persist(Document $document): void;

    public function remove(string $identifier): void;
}
