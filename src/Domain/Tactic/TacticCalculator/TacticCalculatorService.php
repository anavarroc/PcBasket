<?php

namespace PcBasket\Domain\Tactic\TacticCalculator;

use PcBasket\Domain\Player\Exception\NotEnoughPlayersForGivenRoleException;
use PcBasket\Domain\Player\PlayerCollection;
use PcBasket\Domain\Role\Center;
use PcBasket\Domain\Role\PointGuard;
use PcBasket\Domain\Role\PowerForward;
use PcBasket\Domain\Role\ShootingGuard;
use PcBasket\Domain\Role\SmallForward;
use PcBasket\Domain\Tactic\Tactic;

abstract class TacticCalculatorService
{
    const POINT_GUARDS = 0;
    const SHOOTING_GUARDS = 0;
    const POWER_FORWARDS = 0;
    const CENTERS = 0;
    const SMALL_FORWARDS = 0;

    abstract public function execute(PlayerCollection $players): Tactic;

    protected function getCenters(PlayerCollection $players): PlayerCollection
    {
        $centers = $players->filterByRole(new Center());
        $centers->slice(static::CENTERS);
        if ($centers->count() < static::CENTERS) {
            throw new NotEnoughPlayersForGivenRoleException(new Center());
        }

        return $centers;
    }

    protected function getPointGuards(PlayerCollection $players): PlayerCollection
    {
        $pointGuards = $players->filterByRole(new PointGuard());
        $pointGuards->slice(static::POINT_GUARDS);
        if ($pointGuards->count() < static::POINT_GUARDS) {
            throw new NotEnoughPlayersForGivenRoleException(new PointGuard());
        }

        return $pointGuards;
    }

    protected function getPowerForwards(PlayerCollection $players): PlayerCollection
    {
        $powerForwards = $players->filterByRole(new PowerForward());
        $powerForwards->slice(static::POWER_FORWARDS);
        if ($powerForwards->count() < static::POWER_FORWARDS) {
            throw new NotEnoughPlayersForGivenRoleException(new PowerForward());
        }

        return $powerForwards;
    }

    protected function getShootingGuards(PlayerCollection $players): PlayerCollection
    {
        $shootingGuards = $players->filterByRole(new ShootingGuard());
        $shootingGuards->slice(static::SHOOTING_GUARDS);
        if ($shootingGuards->count() < static::SHOOTING_GUARDS) {
            throw new NotEnoughPlayersForGivenRoleException(new ShootingGuard());
        }

        return $shootingGuards;
    }

    protected function getSmallForwards(PlayerCollection $players): PlayerCollection
    {
        $shootingGuards = $players->filterByRole(new SmallForward());
        $shootingGuards->slice(static::SMALL_FORWARDS);
        if ($shootingGuards->count() < static::SMALL_FORWARDS) {
            throw new NotEnoughPlayersForGivenRoleException(new SmallForward());
        }

        return $shootingGuards;
    }
}
