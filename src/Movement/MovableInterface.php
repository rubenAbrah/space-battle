<?php

namespace App\Movement;

interface MovableInterface
{
    public function getPosition(): array;
    public function setPosition(array $position): void;
    public function getVelocity(): array;
    public function setVelocity(array $velocity): void;
}