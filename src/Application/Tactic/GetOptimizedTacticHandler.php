<?php

namespace PcBasket\Application\Tactic;

use PcBasket\Domain\Player\PlayerCollection;
use PcBasket\Domain\Player\PlayerRepositoryInterface;
use PcBasket\Domain\Tactic\Tactic;
use PcBasket\Domain\Tactic\TacticCalculator\TacticCalculatorService;

class GetOptimizedTacticHandler
{
    private PlayerRepositoryInterface $playerRepository;
    private TacticCalculatorService $tacticCalculatorService;

    public function __construct(
        PlayerRepositoryInterface $playerRepository,
        TacticCalculatorService $tacticCalculatorService
    ) {
        $this->playerRepository = $playerRepository;
        $this->tacticCalculatorService = $tacticCalculatorService;
    }

    public function handle(GetOptimizedTacticQuery $query): TacticDto
    {
        $playerCollection = $this->playerRepository->findAll();
        $tactic = $this->calculateTactic($playerCollection);

        return TacticDto::fromTactic($tactic);
    }

    private function calculateTactic(PlayerCollection $playerCollection): Tactic
    {
        return $this->tacticCalculatorService->execute($playerCollection);
    }
}
