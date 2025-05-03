<?php
namespace App\Command;

use App\Fuel\FuelInterface;

class CheckFuelCommand implements CommandInterface
{
    private FuelInterface $fuel;

    public function __construct(FuelInterface $fuel)
    {
        $this->fuel = $fuel;
    }

    public function execute(): void
    {
        if ($this->fuel->getFuelLevel() < $this->fuel->getFuelConsumption()) {
            throw new CommandException("Not enough fuel");
        }
    }
}