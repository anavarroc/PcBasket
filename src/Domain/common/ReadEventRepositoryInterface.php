<?php

namespace PcBasket\Domain\common;

interface ReadEventRepositoryInterface
{
    public function findAll(): ReadEventCollection;
}
