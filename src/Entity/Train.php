<?php

namespace App\Entity;

class Train
{
    /** @var Carriage[] */
    private array $carriages;

    private int $personPosition = 0;

    public function __construct()
    {
        $this->carriages = array_map(function () {
            return new Carriage((bool)rand(0, 1));
        }, array_fill(0, rand(3, 100), null));

        // хотя бы один должен быть включен, иначе задача не имеет решения
        $this->carriages[rand(0, count($this->carriages) - 1)]->turnOn();
    }

    public function moveForward(int $steps = 1): void
    {
        $this->personPosition += abs($steps);
    }

    public function moveBackward(int $steps = 1): void
    {
        $this->personPosition -= abs($steps);
    }

    private function getCurrentCarriageIndex(): int
    {
        $carriagesCount = count($this->carriages);

        $fullCirclesSteps = floor(abs($this->personPosition) / $carriagesCount) * $carriagesCount;

        if($fullCirclesSteps && !($this->personPosition % $carriagesCount)) return 0;

        if($this->personPosition >= 0) return $this->personPosition - $fullCirclesSteps;

        return $carriagesCount - (abs($this->personPosition) - $fullCirclesSteps);
    }

    public function getCurrentCarriageLightState(): bool
    {
        return $this->carriages[$this->getCurrentCarriageIndex()]->getLightState();
    }

    public function turnCurrentCarriageLightOn(): void
    {
        $this->carriages[$this->getCurrentCarriageIndex()]->turnOn();
    }

    public function turnCurrentCarriageLightOff(): void
    {
        $this->carriages[$this->getCurrentCarriageIndex()]->turnOff();
    }

    public function supposeTrainLength(int $length): bool|\Exception
    {
        $trainLength = count($this->carriages);

        if (count($this->carriages) !== $length) throw new \Exception("Ты предположил - {$length}. реальная длина поезда - {$trainLength}");

        return $trainLength === $length;
    }

}