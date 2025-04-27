<?php

namespace App\Movement;

class LinearMovement implements MovementInterface
{
    public function move(MovableInterface $movable): void
    {
        $position = $movable->getPosition();
        $velocity = $movable->getVelocity();
        
        if (count($position) !== 2 || count($velocity) !== 2) {
            throw new \InvalidArgumentException("Position and velocity must be arrays of 2 elements");
        }
        
        $newPosition = [
            $position[0] + $velocity[0],
            $position[1] + $velocity[1]
        ];
        
        $movable->setPosition($newPosition);
    }
}