<?php

namespace PcBasket\Domain\Player;

use PcBasket\Domain\Player\Exception\InvalidCoachRatingException;

class CoachRating
{
    const MIN_RATING = 0;
    const MAX_RATING = 100;
    private int $rating;

    public function __construct(int $rating)
    {
        $this->rating = $rating;
    }

    public function isValidRating($rating)
    {
        if (
            $rating < self::MIN_RATING
            || $rating > self::MAX_RATING
        ) {
            throw new InvalidCoachRatingException($rating);
        }
    }

    public function isEqual(self $coachRating)
    {
        return $this->rating === $coachRating->getRating();
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function isHigherThan(self $coachRating)
    {
        return $this->rating > $coachRating->getRating();
    }
}
