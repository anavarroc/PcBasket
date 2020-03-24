<?php

namespace PcBasket\Domain\Tactic\TacticCalculator;

use PcBasket\Domain\Player\OrderByRatingDescStrategy;
use PcBasket\Domain\Player\PlayerCollection;
use PcBasket\Domain\Tactic\Tactic;

class TwoTwoOneAttackTacticCalculator extends TacticCalculatorService
{
    const NAME = 'Ataque 2-2-1';
    const POINT_GUARDS = 1;
    const SMALL_FORWARDS = 1;
    const SHOOTING_GUARDS = 1;
    const CENTERS = 1;
    const POWER_FORWARDS = 1;
    // BASE + ALERO + ESCOLTA + PIVOT + ALA-PIVOT

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
        $tacticPlayerList->addCollection($this->getSmallForwards($orderedPlayers));
        $tacticPlayerList->addCollection($this->getShootingGuards($orderedPlayers));
        $tacticPlayerList->addCollection($this->getCenters($orderedPlayers));
        $tacticPlayerList->addCollection($this->getPowerForwards($orderedPlayers));

        return new Tactic(
            self::NAME,
            $tacticPlayerList
        );
    }
}
