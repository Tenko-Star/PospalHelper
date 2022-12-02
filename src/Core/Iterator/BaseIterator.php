<?php

namespace PospalHelper\Core\Iterator;

use PospalHelper\Core\Exception\PospalException;

abstract class BaseIterator implements \Iterator
{
    private ?array $current = null;
    private \Closure $getter;
    private ?array $postBackParams = null;
    private int $pageSize = 0;
    private bool $isEnd = false;

    public function __construct(\Closure $getter)
    {
        $this->getter = $getter;
    }

    public function set(array $data)
    {
        $this->current = $data;
    }

    /**
     * @return array
     * @throws PospalException
     */
    public function current(): array
    {
        if ($this->current === null) {
            $this->next();
        }

        return $this->current;
    }

    /**
     * @throws PospalException
     * @return array
     */
    public function next(): ?array
    {
        if ($this->isEnd()) {
            return null;
        }

        $getter = $this->getter;
        $response = $getter($this->postBackParams);
        if (!$response->hasParams()) {
            $this->isEnd = true;
            return $this->current;
        }

        $this->current = $response->data;
        $this->postBackParams = $response->params;
        $this->pageSize = $response->pageSize;

        return $this->current;
    }

    public function key(): array
    {
        return $this->postBackParams;
    }

    public function valid(): bool
    {
        return true;
    }

    public function rewind()
    {
        $this->postBackParams = null;
        $this->next();
    }

    public function getSize(): int
    {
        return $this->pageSize;
    }

    public function isEnd(): bool
    {
        return $this->isEnd;
    }
}