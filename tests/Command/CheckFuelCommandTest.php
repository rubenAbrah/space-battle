<?php
namespace Tests\Command;

use App\Command\CheckFuelCommand;
use App\Command\CommandException;
use App\Fuel\FuelInterface;
use PHPUnit\Framework\TestCase;

class CheckFuelCommandTest extends TestCase
{
    public function testExecuteWithEnoughFuel()
    {
        $fuel = $this->createMock(FuelInterface::class);
        $fuel->method('getFuelLevel')->willReturn(10);
        $fuel->method('getFuelConsumption')->willReturn(5);

        $command = new CheckFuelCommand($fuel);
        $command->execute(); // Should not throw exception
        $this->assertTrue(true);
    }

    public function testExecuteWithNotEnoughFuel()
    {
        $this->expectException(CommandException::class);

        $fuel = $this->createMock(FuelInterface::class);
        $fuel->method('getFuelLevel')->willReturn(3);
        $fuel->method('getFuelConsumption')->willReturn(5);

        $command = new CheckFuelCommand($fuel);
        $command->execute();
    }
}