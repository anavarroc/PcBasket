<?php

namespace PcBasket\Domain\Player;

use PcBasket\Domain\Role\PlayerRole;

class Player
{
    private int $number;
    private string $name;
    private PlayerRole $role;
    private CoachRating $coachRating;

    public function __construct(
        int $number,
        string $name,
        PlayerRole $role,
        CoachRating $coachRating
    ) {
        $this->number = $number;
        $this->name = $name;
        $this->role = $role;
        $this->coachRating = $coachRating;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRole(): PlayerRole
    {
        return $this->role;
    }

    public function getCoachRating(): CoachRating
    {
        return $this->coachRating;
    }

    public function toArray(): array
    {
        return [
            'number' => $this->number,
            'name' => $this->name,
            'role' => (string) $this->role,
            'rating' => $this->coachRating->getRating(),
        ];
    }
}
