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

use Schranz\Search\SEAL\Schema\Index;

class MemoryDriver implements DriverInterface
{
    private ?MemoryRepository $repository;

    public function isInitialized(): bool
    {
        return true;
    }

    public function initialize(): void
    {
        $this->repository = new MemoryRepository();
    }

    public function drop(): void
    {
        $this->repository = null;
    }

    public function createRepository(): MemoryRepository
    {
        if (!$this->repository) {
            throw new \RuntimeException('Driver not initialized');
        }

        return $this->repository;
    }
}
