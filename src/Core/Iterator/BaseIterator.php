<?php

namespace PospalHelper\Core\Iterator;

use PospalHelper\Core\Exception\PospalException;

abstract class BaseIterator implements \Iterator
{
    private ?array $current = null;

    private \Closure $getter;

    private ?array $postBackParams = null;

    private int $pageSize = 0;

    private bool $isEnd;

    private bool $needGetNext;

    private int $count;
    private int $max = 0;

    public function __construct(\Closure $getter)
    {
        $this->getter = $getter;
        $this->count = 0;
        $this->isEnd = false;
        $this->needGetNext = true;
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
        if ($this->needGetNext && !$this->isEnd) {
            $getter = $this->getter;
            $response = $getter($this->postBackParams);
            if ($response->isEnd() || ($this->max > 0 && $this->count > $this->max)) {
                $this->isEnd = true;
            }

            $this->current = $response->data;
            $this->postBackParams = $response->params;
            $this->pageSize = $response->pageSize;

            $this->needGetNext = false;
            $this->count++;
        }

        return $this->current;
    }

    public function next(): void
    {
        $this->needGetNext = true;
    }

    public function key(): ?array
    {
        return $this->postBackParams;
    }

    public function valid(): bool
    {
        return !$this->isEnd;
    }

    public function rewind()
    {
        $this->postBackParams = null;
        $this->count = 0;
        $this->isEnd = false;
        $this->needGetNext = true;
    }

    public function getSize(): int
    {
        return $this->pageSize;
    }

    public function setMax(int $max)
    {
        $this->max = $max;
    }
}