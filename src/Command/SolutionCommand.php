<?php

namespace App\Command;

use App\Entity\Train;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SolutionCommand extends Command
{
    private OutputInterface $output;
    private Train $train;

    protected function configure()
    {
        $this
            ->setName('train');
    }

    private function moveToNext($steps = 0, $forward = true)
    {
        if (!$forward) {
            $this->train->moveBackward($steps);
        }

        if (!$forward && !$this->train->getCurrentCarriageLightState() && $this->train->supposeTrainLength($steps)) {
            return $this->output->writeln("Длина поезда {$steps}");
        }

        $this->train->moveForward();

        if ($this->train->getCurrentCarriageLightState()) {
            $this->train->turnCurrentCarriageLightOff();
            return $this->moveToNext($steps + 1, false);
        }

        if (!$forward && !$this->train->getCurrentCarriageLightState()) {
            $this->moveToNext(1);
        } else {
            $this->moveToNext($steps + 1);
        }
    }

    protected
    function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;

        $this->train = new Train();

        while (!$this->train->getCurrentCarriageLightState()) {
            $this->train->moveForward();
        }

        $this->moveToNext();

        return Command::SUCCESS;
    }
}