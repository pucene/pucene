<?php

namespace Pucene\Component\Pucene\Compiler\Element;

class IdsElement extends BaseElement
{
    /**
     * @var string[]
     */
    private $ids;

    public function __construct(array $ids, float $boost = 1)
    {
        parent::__construct($boost);

        $this->ids = $ids;
    }

    /**
     * @return \string[]
     */
    public function getIds(): array
    {
        return $this->ids;
    }
}
