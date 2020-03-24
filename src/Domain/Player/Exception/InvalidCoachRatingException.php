<?php

namespace PcBasket\Domain\Player\Exception;

use Exception;

class InvalidCoachRatingException extends Exception
{
    public function __construct($rating)
    {
        $message = 'Invalid value for given rating: ' . $rating;
        parent::__construct($message);
    }
}
