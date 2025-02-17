<?php

namespace Tests\Feature;

use App\Game\Rotation\Rotation;
use App\Game\Interfaces\Rotatable;
use Tests\TestCase;

class RotationTest extends TestCase
{
    public function testRotate()
    {
        $rotatable = new class implements Rotatable {
            private $angle = 90;
            private $angularVelocity = 45;

            public function getAngle(): ?int
            {
                return $this->angle;
            }

            public function getAngularVelocity(): ?int
            {
                return $this->angularVelocity;
            }

            public function setAngle(int $angle): void
            {
                $this->angle = $angle;
            }
        };

        $rotation = new Rotation();
        $rotation->rotate($rotatable);

        $this->assertEquals(135, $rotatable->getAngle());
    }

    public function testRotateWithInvalidAngle()
    {
        $this->expectException(\Exception::class);

        $rotatable = new class implements Rotatable {
            public function getAngle(): ?int
            {
                return null;
            }

            public function getAngularVelocity(): ?int
            {
                return 45;
            }

            public function setAngle(int $angle): void
            {
            }
        };

        $rotation = new Rotation();
        $rotation->rotate($rotatable);
    }

    public function testRotateWithInvalidAngularVelocity()
    {
        $this->expectException(\Exception::class);

        $rotatable = new class implements Rotatable {
            public function getAngle(): ?int
            {
                return 90;
            }

            public function getAngularVelocity(): ?int
            {
                return null;
            }

            public function setAngle(int $angle): void
            {
            }
        };

        $rotation = new Rotation();
        $rotation->rotate($rotatable);
    }

    public function testRotateWithInvalidSetAngle()
    {
        $this->expectException(\Exception::class);

        $rotatable = new class implements Rotatable {
            public function getAngle(): ?int
            {
                return 90;
            }

            public function getAngularVelocity(): ?int
            {
                return 45;
            }

            public function setAngle(int $angle): void
            {
                throw new \Exception("Cannot set angle.");
            }
        };

        $rotation = new Rotation();
        $rotation->rotate($rotatable);
    }
}