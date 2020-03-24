<?php

namespace PcBasket\Domain\Player\Exception;

use Exception;

class PlayerAlreadyExistsException extends Exception
{
    public function __construct($playerId)
    {
        $message = 'Player already exists for given number: ' . $playerId;
        parent::__construct($message);
    }
}
