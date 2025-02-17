<?php

namespace App\Game\Interfaces;

interface Rotatable
{
    public function getAngle(): ?int;
    public function getAngularVelocity(): ?int;
    public function setAngle(int $angle): void;
}