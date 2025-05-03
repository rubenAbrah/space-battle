<?php
namespace App\Command;

use App\Movement\MovableInterface;
use App\Rotation\RotatableInterface;

class ChangeVelocityCommand implements CommandInterface
{
    private MovableInterface $movable;
    private RotatableInterface $rotatable;

    public function __construct(MovableInterface $movable, RotatableInterface $rotatable)
    {
        $this->movable = $movable;
        $this->rotatable = $rotatable;
    }

    public function execute(): void
    {
        $velocity = $this->movable->getVelocity();
        $direction = $this->rotatable->getDirection();
        $directionsNumber = $this->rotatable->getDirectionsNumber();
        
        // Assuming velocity is [x, y] and we need to rotate it based on direction
        $angle = 2 * pi() * $direction / $directionsNumber;
        $speed = sqrt($velocity[0]**2 + $velocity[1]**2);
        
        $newVelocity = [
            round($speed * cos($angle)),
            round($speed * sin($angle))
        ];
        
        $this->movable->setVelocity($newVelocity);
    }
}