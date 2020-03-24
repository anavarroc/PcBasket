<?php

namespace PcBasket\Domain\Role;

use PcBasket\Domain\Role\Exception\PlayerRoleNotFoundException;

class RoleFactory
{
    public function build($roleName)
    {
        switch ($roleName) {
            case Center::NAME:
                return new Center();
            case PointGuard::NAME:
                return new PointGuard();
            case PowerForward::NAME:
                return new PowerForward();
            case ShootingGuard::NAME:
                return new ShootingGuard();
            case SmallForward::NAME:
                return new SmallForward();
            default:
                throw new PlayerRoleNotFoundException($roleName);
        }
    }
}
