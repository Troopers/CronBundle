<?php

namespace Troopers\CronBundle\Command;

use Troopers\CronBundle\Cron\Manager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronRunCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('cron:run')
            ->setDescription('Run any currently scheduled cron jobs')
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getContainer()->get(Manager::class);

        $commandOutput = $manager->runTasks();
        $output->writeln($commandOutput);
    }
}