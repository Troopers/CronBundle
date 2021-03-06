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

    protected static function getKernelClass()
    {
        if (!isset($_SERVER['KERNEL_CLASS']) && !isset($_ENV['KERNEL_CLASS'])) {
            throw new \LogicException(sprintf('You must set the KERNEL_CLASS environment variable to the fully-qualified class name of your Kernel in phpunit.xml / phpunit.xml.dist or override the %1$s::createKernel() or %1$s::getKernelClass() method.', static::class));
        }

        if (!class_exists($class = $_ENV['KERNEL_CLASS'] ?? $_SERVER['KERNEL_CLASS'])) {
            throw new \RuntimeException(sprintf('Class "%s" doesn\'t exist or cannot be autoloaded. Check that the KERNEL_CLASS value in phpunit.xml matches the fully-qualified class name of your Kernel or override the %s::createKernel() method.', $class, static::class));
        }

        return $class;
    }

    public function tearDown()
    {
    }
}