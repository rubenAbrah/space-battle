<?php
namespace Tests\Command;

use App\Command\RotateWithVelocityChangeCommand;
use App\Movement\MovableInterface;
use App\Rotation\RotatableInterface;
use PHPUnit\Framework\TestCase;

class RotateWithVelocityChangeCommandTest extends TestCase
{
    public function testExecuteWithMovable()
    {
        $rotatable = $this->createMock(RotatableInterface::class);
        $rotatable->method('getDirection')->willReturn(1);
        $rotatable->method('getAngularVelocity')->willReturn(1);
        $rotatable->method('getDirectionsNumber')->willReturn(8);
        $rotatable->expects($this->once())
                 ->method('setDirection')
                 ->with(2);
        
        $movable = $this->createMock(MovableInterface::class);
        $movable->expects($this->once())->method('setVelocity');
        
        $command = new RotateWithVelocityChangeCommand($rotatable, $movable);
        $command->execute();
    }
    
    public function testExecuteWithoutMovable()
    {
        $rotatable = $this->createMock(RotatableInterface::class);
        $rotatable->method('getDirection')->willReturn(1);
        $rotatable->method('getAngularVelocity')->willReturn(1);
        $rotatable->method('getDirectionsNumber')->willReturn(8);
        $rotatable->expects($this->once())
                 ->method('setDirection')
                 ->with(2);
        
        $command = new RotateWithVelocityChangeCommand($rotatable);
        $command->execute(); // Should not try to change velocity
    }
    
    public function testThrowsExceptionForZeroDirections()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $rotatable = $this->createMock(RotatableInterface::class);
        $rotatable->method('getDirectionsNumber')->willReturn(0);
        
        $command = new RotateWithVelocityChangeCommand($rotatable);
        $command->execute();
    }
}