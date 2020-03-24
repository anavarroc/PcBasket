<?php

namespace PcBasket\Domain\Player\Exception;

use Exception;
use PcBasket\Domain\Role\PlayerRole;

class NotEnoughPlayersForGivenRoleException extends Exception
{
    public function __construct(PlayerRole $playerRole)
    {
        $message = 'Not Enough players for given role: ' . (string) $playerRole;
        parent::__construct($message);
    }
}
