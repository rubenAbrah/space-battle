<?php
namespace App\Fuel;

interface FuelInterface
{
    public function getFuelLevel(): int;
    public function setFuelLevel(int $level): void;
    public function getFuelConsumption(): int;
}
