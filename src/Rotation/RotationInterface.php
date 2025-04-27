<?php

namespace App\Rotation;

interface RotationInterface
{
    public function rotate(RotatableInterface $rotatable): void;
}