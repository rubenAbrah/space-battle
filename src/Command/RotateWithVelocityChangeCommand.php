<?php
namespace App\Command;

use App\Rotation\RotatableInterface;
use App\Movement\MovableInterface;

class RotateWithVelocityChangeCommand implements CommandInterface
{
    private RotatableInterface $rotatable;
    private ?MovableInterface $movable;

    public function __construct(RotatableInterface $rotatable, ?MovableInterface $movable = null)
    {
        $this->rotatable = $rotatable;
        $this->movable = $movable;
    }

    public function execute(): void
    {
        $originalDirection = $this->rotatable->getDirection();
        $angularVelocity = $this->rotatable->getAngularVelocity();
        $directionsNumber = $this->rotatable->getDirectionsNumber();
        
        // Validate directionsNumber to prevent division by zero
        if ($directionsNumber <= 0) {
            throw new \InvalidArgumentException("Directions number must be positive");
        }

        $newDirection = ($originalDirection + $angularVelocity) % $directionsNumber;
        if ($newDirection < 0) {
            $newDirection += $directionsNumber;
        }
        
        $this->rotatable->setDirection($newDirection);
        
        if ($this->movable !== null) {
            $changeVelocityCommand = new ChangeVelocityCommand($this->movable, $this->rotatable);
            $changeVelocityCommand->execute();
        }
    }
}