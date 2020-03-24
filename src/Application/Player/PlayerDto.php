<?php

namespace PcBasket\Application\Player;

use PcBasket\Domain\Player\Player;

class PlayerDto
{
    private int $number;
    private string $name;
    private string $roleName;
    private int $rating;

    private function __construct(
        int $number,
        string $name,
        string $roleName,
        int $rating
    ) {
        $this->number = $number;
        $this->name = $name;
        $this->roleName = $roleName;
        $this->rating = $rating;
    }

    public function toArray(): array
    {
        return [
            'number' => $this->number,
            'name' => $this->name,
            'role' => $this->roleName,
            'rating' => $this->rating,
        ];
    }

    public static function fromPlayer(Player $player)
    {
        return new self(
            $player->getNumber(),
            $player->getName(),
            (string) $player->getRole(),
            $player->getCoachRating()->getRating()
        );
    }
}
