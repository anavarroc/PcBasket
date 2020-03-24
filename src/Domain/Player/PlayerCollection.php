<?php

namespace PcBasket\Domain\Player;

use PcBasket\Domain\common\CollectionInterface;
use PcBasket\Domain\Role\PlayerRole;

class PlayerCollection implements CollectionInterface
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

    public function slice($amount)
    {
        $this->items = array_slice($this->items, 0, $amount);
        $this->rewind();
    }

    public function add(Player $value): self
    {
        $this->items[] = $value;
        $this->rewind();

        return $this;
    }

    public function addCollection(self $other): self
    {
        foreach ($other as $item) {
            $this->add($item);
        }

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

    public function filterByRole(PlayerRole $role): self
    {
        $newCollection = new self();
        foreach ($this->items as $player) {
            if ($player->getRole()->isEqual($role)) {
                $newCollection->add($player);
            }
        }

        return $newCollection;
    }

    public function toArray()
    {
        $players = [];
        foreach ($this->items as $player) {
            $players[] = $player->toArray();
        }

        return $players;
    }
}
