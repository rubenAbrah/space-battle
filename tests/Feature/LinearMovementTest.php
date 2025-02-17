<?php

namespace Tests\Feature;

use App\Game\Rotation\LinearMovement;
use App\Game\Interfaces\Movable;
use Tests\TestCase;

class LinearMovementTest extends TestCase
{
    public function testMove()
    {
        $movable = new class implements Movable {
            private $position = [12, 5];
            private $velocity = [-7, 3];

            public function getPosition(): ?array
            {
                return $this->position;
            }

            public function getVelocity(): ?array
            {
                return $this->velocity;
            }

            public function setPosition(array $position): void
            {
                $this->position = $position;
            }
        };

        $linearMovement = new LinearMovement();
        $linearMovement->move($movable);

        $this->assertEquals([5, 8], $movable->getPosition());
    }

    public function testMoveWithInvalidPosition()
    {
        $this->expectException(\Exception::class);

        $movable = new class implements Movable {
            public function getPosition(): ?array
            {
                return null;
            }

            public function getVelocity(): ?array
            {
                return [1, 1];
            }

            public function setPosition(array $position): void
            {
            }
        };

        $linearMovement = new LinearMovement();
        $linearMovement->move($movable);
    }

    public function testMoveWithInvalidVelocity()
    {
        $this->expectException(\Exception::class);

        $movable = new class implements Movable {
            public function getPosition(): ?array
            {
                return [0, 0];
            }

            public function getVelocity(): ?array
            {
                return null;
            }

            public function setPosition(array $position): void
            {
            }
        };

        $linearMovement = new LinearMovement();
        $linearMovement->move($movable);
    }

    public function testMoveWithInvalidSetPosition()
    {
        $this->expectException(\Exception::class);

        $movable = new class implements Movable {
            public function getPosition(): ?array
            {
                return [0, 0];
            }

            public function getVelocity(): ?array
            {
                return [1, 1];
            }

            public function setPosition(array $position): void
            {
                throw new \Exception("Cannot set position.");
            }
        };

        $linearMovement = new LinearMovement();
        $linearMovement->move($movable);
    }
}