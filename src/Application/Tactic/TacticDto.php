<?php

namespace PcBasket\Application\Tactic;

use PcBasket\Application\Player\PlayerDto;
use PcBasket\Application\Player\PlayerDtoCollection;
use PcBasket\Domain\Tactic\Tactic;

class acticDto
{
    private string $tacticName;
    private PlayerDtoCollection $players;

    public function __construct(
        string $tacticName,
        PlayerDtoCollection $players
    ) {
        $this->tacticName = $tacticName;
        $this->players = $players;
    }

    public static function fromTactic(Tactic $tactic)
    {
        $playerCollection = new PlayerDtoCollection();
        foreach ($tactic->getPlayers() as $player) {
            $playerCollection->add(PlayerDto::fromPlayer($player));
        }

        return new self(
            $tactic->getName(),
            $playerCollection
        );
    }

    public function toArray()
    {
        return [
            'name' => $this->tacticName,
            'players' => $this->players->toArray(),
        ];
    }
}
