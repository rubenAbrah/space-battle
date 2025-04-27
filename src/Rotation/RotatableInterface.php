<?php

namespace App\Rotation;

interface RotatableInterface
{
    public function getDirection(): int;
    public function setDirection(int $direction): void;
    public function getAngularVelocity(): int;
    public function getDirectionsNumber(): int;
}