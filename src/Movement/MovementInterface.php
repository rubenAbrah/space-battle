<?php

namespace App\Movement;

interface MovementInterface
{
    public function move(MovableInterface $movable): void;
}