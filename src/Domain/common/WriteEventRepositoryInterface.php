<?php

namespace PcBasket\Domain\common;

interface WriteEventRepositoryInterface
{
    public function save(WriteEvent $event);
}
