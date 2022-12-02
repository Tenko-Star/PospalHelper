<?php

namespace PospalHelper\Core\Iterator;

class IteratorResponse
{
    public array $data;
    public array $params;
    public int $pageSize;

    public function __construct(array $data, int $pageSize, array $params)
    {
        $this->data = $data;
        $this->params = $params;
        $this->pageSize = $pageSize;
    }

    public function isEnd(): bool
    {
        return count($this->data) < $this->pageSize;
    }
}