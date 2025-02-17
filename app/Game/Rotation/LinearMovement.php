<?php

namespace App\Game\Rotation;

use App\Game\Interfaces\Movable;

class LinearMovement
{
    public function move(Movable $movable): void
    {
        $position = $movable->getPosition();
        $velocity = $movable->getVelocity();

        if ($position === null || $velocity === null) {
            throw new \Exception("Cannot move object: position or velocity is not set.");
        }

        $newPosition = [
            $position[0] + $velocity[0],
            $position[1] + $velocity[1],
        ];

        $movable->setPosition($newPosition);
    }
}