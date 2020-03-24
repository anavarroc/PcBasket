<?php

namespace PcBasket\Domain\Tactic\TacticCalculator;

use PcBasket\Domain\Player\OrderByRatingDescStrategy;
use PcBasket\Domain\Player\PlayerCollection;
use PcBasket\Domain\Tactic\Tactic;

class OneThreeOneDefenceTacticCalculator extends TacticCalculatorService
{
    const NAME = 'Defensa 1-3-1';
    const POINT_GUARDS = 1;
    const SHOOTING_GUARDS = 2;
    const POWER_FORWARDS = 1;
    const CENTERS = 1;
    //BASE + ESCOLTA + ESCOLTA + ALA-PIVOT + PIVOT

    private OrderByRatingDescStrategy $orderStrategy;

    public function __construct(OrderByRatingDescStrategy $orderStrategy)
    {
        $this->orderStrategy = $orderStrategy;
    }

    public function execute(PlayerCollection $players): Tactic
    {
        $tacticPlayerList = new PlayerCollection();
        $orderedPlayers = $this->orderStrategy->execute($players);
        $tacticPlayerList->addCollection($this->getPointGuards($orderedPlayers));
        $tacticPlayerList->addCollection($this->getShootingGuards($orderedPlayers));
        $tacticPlayerList->addCollection($this->getPowerForwards($orderedPlayers));
        $tacticPlayerList->addCollection($this->getCenters($orderedPlayers));

        return new Tactic(
            self::NAME,
            $tacticPlayerList
        );
    }
}
