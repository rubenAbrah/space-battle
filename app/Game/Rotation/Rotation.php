<?php

namespace App\Game\Rotation;

use App\Game\Interfaces\Rotatable;

class Rotation
{
    public function rotate(Rotatable $rotatable): void
    {
        $angle = $rotatable->getAngle();
        $angularVelocity = $rotatable->getAngularVelocity();

        if ($angle === null || $angularVelocity === null) {
            throw new \Exception("Cannot rotate object: angle or angular velocity is not set.");
        }

        $newAngle = $angle + $angularVelocity;
        $rotatable->setAngle($newAngle);
    }
}