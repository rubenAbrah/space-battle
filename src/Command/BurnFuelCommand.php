<?php
namespace App\Command;

use App\Fuel\FuelInterface;

class BurnFuelCommand implements CommandInterface
{
    private FuelInterface $fuel;

    public function __construct(FuelInterface $fuel)
    {
        $this->fuel = $fuel;
    }

    public function execute(): void
    {
        $newLevel = $this->fuel->getFuelLevel() - $this->fuel->getFuelConsumption();
        $this->fuel->setFuelLevel($newLevel);
    }
}