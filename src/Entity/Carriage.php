<?php

namespace App\Entity;

class Carriage
{
    public function __construct(private bool $isLightOn)
    {
    }
    public function getLightState(): bool
    {
        return $this->isLightOn;
    }
    public function turnOn(): void
    {
        $this->isLightOn = true;
    }
    public function turnOff(): void
    {
        $this->isLightOn = false;
    }
}