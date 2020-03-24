<?php

namespace PcBasket\Domain\Role\Exception;

use Exception;

class PlayerRoleNotFoundException extends Exception
{
    public function __construct($roleName)
    {
        $message = 'Player role not found for given role name: ' . $roleName;
        parent::__construct($message);
    }
}
