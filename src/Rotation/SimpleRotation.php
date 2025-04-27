<?php

namespace App\Rotation;

class SimpleRotation implements RotationInterface
{
    public function rotate(RotatableInterface $rotatable): void
    {
        $direction = $rotatable->getDirection();
        $angularVelocity = $rotatable->getAngularVelocity();
        $directionsNumber = $rotatable->getDirectionsNumber();
        
        $newDirection = ($direction + $angularVelocity) % $directionsNumber;
        if ($newDirection < 0) {
            $newDirection += $directionsNumber;
        }
        
        $rotatable->setDirection($newDirection);
    }
}