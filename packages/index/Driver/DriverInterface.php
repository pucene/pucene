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

interface DriverInterface
{
    public function isInitialized(): bool;

    public function initialize(): void;

    public function drop(): void;

    public function createRepository(): RepositoryInterface;
}
