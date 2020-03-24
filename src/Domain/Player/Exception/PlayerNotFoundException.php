<?php

namespace PcBasket\Domain\Player\Exception;

use Exception;

class PlayerNotFoundException extends Exception
{
    public function __construct($playerId)
    {
        $message = 'Player not found for given number: ' . $playerId;
        parent::__construct($message);
    }
}
