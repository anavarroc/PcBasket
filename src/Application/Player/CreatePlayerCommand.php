<?php

namespace PcBasket\Application\Player;

class CreatePlayerCommand
{
    private int $number;
    private string $name;
    private string $role;
    private int $coachRating;

    public function __construct(
        int $number,
        string $name,
        string $role,
        int $coachRating
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

    public function getRole(): string
    {
        return $this->role;
    }

    public function getCoachRating(): int
    {
        return $this->coachRating;
    }
}
