<?php

namespace Tests\Movement;

use App\Movement\LinearMovement;
use App\Movement\MovableInterface;
use PHPUnit\Framework\TestCase;

class LinearMovementTest extends TestCase
{
    public function testMoveChangesPositionCorrectly()
    {
        $movable = $this->createMock(MovableInterface::class);
        $movable->method('getPosition')->willReturn([12, 5]);
        $movable->method('getVelocity')->willReturn([-7, 3]);
        $movable->expects($this->once())
            ->method('setPosition')
            ->with([5, 8]);
        
        $movement = new LinearMovement();
        $movement->move($movable);
    }
    
    public function testMoveFailsWhenCannotReadPosition()
    {
        $this->expectException(\Exception::class);
        
        $movable = $this->createMock(MovableInterface::class);
        $movable->method('getPosition')->willThrowException(new \Exception("Can't read position"));
        
        $movement = new LinearMovement();
        $movement->move($movable);
    }
    
    public function testMoveFailsWhenCannotReadVelocity()
    {
        $this->expectException(\Exception::class);
        
        $movable = $this->createMock(MovableInterface::class);
        $movable->method('getPosition')->willReturn([12, 5]);
        $movable->method('getVelocity')->willThrowException(new \Exception("Can't read velocity"));
        
        $movement = new LinearMovement();
        $movement->move($movable);
    }
    
    public function testMoveFailsWhenCannotSetPosition()
    {
        $this->expectException(\Exception::class);
        
        $movable = $this->createMock(MovableInterface::class);
        $movable->method('getPosition')->willReturn([12, 5]);
        $movable->method('getVelocity')->willReturn([-7, 3]);
        $movable->method('setPosition')->willThrowException(new \Exception("Can't set position"));
        
        $movement = new LinearMovement();
        $movement->move($movable);
    }
}