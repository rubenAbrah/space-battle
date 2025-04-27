<?php

namespace Tests\Rotation;

use App\Rotation\RotatableInterface;
use App\Rotation\SimpleRotation;
use PHPUnit\Framework\TestCase;

class SimpleRotationTest extends TestCase
{
    public function testRotateChangesDirectionCorrectly()
    {
        $rotatable = $this->createMock(RotatableInterface::class);
        $rotatable->method('getDirection')->willReturn(3);
        $rotatable->method('getAngularVelocity')->willReturn(2);
        $rotatable->method('getDirectionsNumber')->willReturn(8);
        $rotatable->expects($this->once())
            ->method('setDirection')
            ->with(5);
        
        $rotation = new SimpleRotation();
        $rotation->rotate($rotatable);
    }
    
    public function testRotateHandlesNegativeDirection()
    {
        $rotatable = $this->createMock(RotatableInterface::class);
        $rotatable->method('getDirection')->willReturn(1);
        $rotatable->method('getAngularVelocity')->willReturn(-3);
        $rotatable->method('getDirectionsNumber')->willReturn(8);
        $rotatable->expects($this->once())
            ->method('setDirection')
            ->with(6);
        
        $rotation = new SimpleRotation();
        $rotation->rotate($rotatable);
    }
}