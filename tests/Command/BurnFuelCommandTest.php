<?php
namespace Tests\Command;

use App\Command\BurnFuelCommand;
use App\Fuel\FuelInterface;
use PHPUnit\Framework\TestCase;

class BurnFuelCommandTest extends TestCase
{
    public function testExecute()
    {
        $fuel = $this->createMock(FuelInterface::class);
        $fuel->expects($this->once())
             ->method('getFuelLevel')
             ->willReturn(10);
        $fuel->expects($this->once())
             ->method('getFuelConsumption')
             ->willReturn(3);
        $fuel->expects($this->once())
             ->method('setFuelLevel')
             ->with(7);
             
        $command = new BurnFuelCommand($fuel);
        $command->execute();
    }
}