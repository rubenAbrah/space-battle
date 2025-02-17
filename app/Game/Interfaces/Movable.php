<?php

namespace App\Game\Interfaces;

interface Movable
{
    public function getPosition(): ?array;
    public function getVelocity(): ?array;
    public function setPosition(array $position): void;
}