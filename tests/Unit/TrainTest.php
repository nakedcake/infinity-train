<?php

namespace App\Tests\Unit;

use App\Entity\Carriage;
use App\Entity\Train;
use PHPUnit\Framework\TestCase;

class TrainTest extends TestCase
{
    public function getPrivateProperty(&$object, string $propertyName)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $propery = $reflection->getProperty($propertyName);
        $propery->setAccessible(true);

        return $propery->getValue($object);
    }
    public function invokePrivateMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function testTrain_supposeLength_correct()
    {
        $train = new Train();

        $carriages = $this->getPrivateProperty($train, 'carriages');

        self::assertTrue($train->supposeTrainLength(count($carriages)));
    }

    public function testTrain_supposeLength_incorrect()
    {
        $train = new Train();

        $carriages = $this->getPrivateProperty($train, 'carriages');

        $this->expectException(\Exception::class);

        $train->supposeTrainLength(count($carriages) - 1);
    }

    public function testTrain_getCurrentCarriageIndex_correctIndexAfterMoveForwardOneStep()
    {
        $train = new Train();

        $train->moveForward();
        self::assertSame(1, $this->getPrivateProperty($train, 'personPosition'));
        self::assertSame(1, $this->invokePrivateMethod($train, 'getCurrentCarriageIndex'));
    }

    public function testTrain_getCurrentCarriageIndex_correctIndexAfterMoveForwardOneFullCircle()
    {
        $train = new Train();

        $carriages = $this->getPrivateProperty($train, 'carriages');

        $train->moveForward(count($carriages));

        self::assertSame(count($carriages), $this->getPrivateProperty($train, 'personPosition'));
        self::assertSame(0, $this->invokePrivateMethod($train, 'getCurrentCarriageIndex'));
    }


    public function testTrain_getCurrentCarriageIndex_correctIndexAfterMoveForwardRandomSteps()
    {
        $train = new Train();

        $carriagesCount = count($this->getPrivateProperty($train, 'carriages'));

        $additionalSteps = rand(1, $carriagesCount - 1);

        $train->moveForward($carriagesCount + $additionalSteps);

        self::assertSame($carriagesCount + $additionalSteps, $this->getPrivateProperty($train, 'personPosition'));
        self::assertSame($additionalSteps, $this->invokePrivateMethod($train, 'getCurrentCarriageIndex'));
    }

    public function testTrain_getCurrentCarriageIndex_correctIndexAfterMoveBackwardOneStep()
    {
        $train = new Train();

        $carriagesCount = count($this->getPrivateProperty($train, 'carriages'));

        $train->moveBackward();
        self::assertSame(-1, $this->getPrivateProperty($train, 'personPosition'));
        self::assertSame($carriagesCount - 1, $this->invokePrivateMethod($train, 'getCurrentCarriageIndex'));
    }

    public function testTrain_getCurrentCarriageIndex_correctIndexAfterMoveBackwardOneFullCircle()
    {
        $train = new Train();

        $carriagesCount = count($this->getPrivateProperty($train, 'carriages'));

        $train->moveBackward($carriagesCount);

        self::assertSame($carriagesCount * -1, $this->getPrivateProperty($train, 'personPosition'));
        self::assertSame(0, $this->invokePrivateMethod($train, 'getCurrentCarriageIndex'));
    }

    public function testTrain_getCurrentCarriageIndex_correctIndexAfterMoveBackwardRandomSteps()
    {
        $train = new Train();

        $carriagesCount = count($this->getPrivateProperty($train, 'carriages'));

        $additionalSteps = rand(1, $carriagesCount - 1);

        $train->moveBackward($carriagesCount + $additionalSteps);

        self::assertSame(($carriagesCount + $additionalSteps) * -1, $this->getPrivateProperty($train, 'personPosition'));
        self::assertSame($carriagesCount - $additionalSteps, $this->invokePrivateMethod($train, 'getCurrentCarriageIndex'));
    }

    public function testTrain_getCurrentCarriageLightState_returnCorrectState()
    {
        $train = new Train();

        /** @var Carriage[] $carriages */
        $carriages = $this->getPrivateProperty($train, 'carriages');

        for ($i = 0; $i <= count($carriages) - 1; $i++) {
            self::assertSame($carriages[$i]->getLightState(), $train->getCurrentCarriageLightState());

            $train->moveForward();
        }
    }
}