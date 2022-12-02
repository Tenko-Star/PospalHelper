<?php

namespace PospalHelper\Core\Util;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Serializable;

class Collection implements ArrayAccess, Countable, JsonSerializable, Serializable, IteratorAggregate
{

    protected array $item;

    public function __construct(array $item = [])
    {
        $this->item = $item;
        $this->init();
    }

    public function init() {}

    public function get(string $key, $default = null)
    {
        return Arrays::get($this->item, $key, $default);
    }

    public function set(string $key, $value): array
    {
        return Arrays::set($this->item, $key, $value);
    }

    public function remove(string $key)
    {
        Arrays::remove($this->item, $key);
    }

    public function exists(string $name): bool
    {
        return Arrays::exists($this->item, $name);
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($key, $value)
    {
        $this->set((string)$key, $value);
    }

    public function __isset($name)
    {
        return $this->exists((string)$name);
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->item);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return $this->exists((string)$offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->get((string)$offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        $this->set((string)$offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        $this->remove((string)$offset);
    }

    /**
     * @inheritDoc
     */
    public function serialize(): ?string
    {
        return serialize($this->item);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($data)
    {
        return $this->item = unserialize($data);
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->item);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->item;
    }

    public function all(): array
    {
        return $this->item;
    }

    public function toArray(): array
    {
        return $this->all();
    }

    public function toJson(int $option = JSON_UNESCAPED_UNICODE): string
    {
        return json_encode($this->all(), $option);
    }
}