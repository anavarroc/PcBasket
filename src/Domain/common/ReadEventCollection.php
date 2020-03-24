<?php

namespace PcBasket\Domain\common;

class ReadEventCollection implements CollectionInterface
{
    private array $items;
    private int $current;

    public function __construct()
    {
        $this->reset();
    }

    private function reset()
    {
        $this->items = [];
        $this->rewind();
    }

    public function unshift($value)
    {
        array_unshift($this->items, $value);
        $this->rewind();
    }

    public function add(ReadEvent $value)
    {
        $this->items[] = $value;
        $this->rewind();

        return $this;
    }

    public function addArray(array $array)
    {
        foreach ($array as $item) {
            $this->add($item);
        }

        return $this;
    }

    public function current()
    {
        return $this->items[$this->current];
    }

    public function next()
    {
        ++$this->current;
    }

    public function key()
    {
        return $this->current;
    }

    public function valid()
    {
        return array_key_exists($this->current, $this->items);
    }

    public function rewind()
    {
        $this->current = 0;
    }

    public function count()
    {
        return count($this->items);
    }
}
