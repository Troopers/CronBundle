<?php

namespace Troopers\CronBundle\Tests\Command;


use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class CronRunCommandTest extends KernelTestCase
{
    public function testRunCommand()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $app = new Application($kernel);
        $app->setAutoExit(false);

        $input = new ArrayInput(['command' => 'cron:run']);
        $output = new BufferedOutput();
        $app->run($input, $output);

        $commandOutput = $output->fetch();

        // Assert running a valid command shows a success message
        $this->assertContains(
            'command "help cache:clear" successfully executed',
            $commandOutput
        );

        // Assert running a wrong command shows an error
        $this->assertContains(
            'ERROR: command "error" is not defined.',
            $commandOutput
        );
    }

    public function tearDown()
    {
    }
}