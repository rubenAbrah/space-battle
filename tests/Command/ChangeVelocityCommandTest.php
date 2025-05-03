<?php
namespace Tests\Command;

use App\Command\ChangeVelocityCommand;
use App\Movement\MovableInterface;
use App\Rotation\RotatableInterface;
use PHPUnit\Framework\TestCase;

class ChangeVelocityCommandTest extends TestCase
{
    public function testExecuteChangesVelocity()
    {
        $movable = $this->createMock(MovableInterface::class);
        $movable->method('getVelocity')->willReturn([10, 0]);
        $movable->expects($this->once())
                ->method('setVelocity')
                ->with($this->callback(function($velocity) {
                    return is_array($velocity) && count($velocity) === 2;
                }));
        
        $rotatable = $this->createMock(RotatableInterface::class);
        $rotatable->method('getDirection')->willReturn(1);
        $rotatable->method('getDirectionsNumber')->willReturn(8);
        
        $command = new ChangeVelocityCommand($movable, $rotatable);
        $command->execute();
    }
}