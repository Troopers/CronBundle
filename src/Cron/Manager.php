<?php

namespace Troopers\CronBundle\Cron;


use Symfony\Component\Console\Output\NullOutput;
use Troopers\CronBundle\Reporting\ReportManager;
use Troopers\CronBundle\Reporting\Model\TaskReport;
use Cron\CronExpression;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

class Manager
{
    private $kernel;
    private $reporter;

    public function __construct(KernelInterface $kernel, ReportManager $reporter)
    {
        $this->kernel = $kernel;
        $this->reporter = $reporter;
    }

    /**
     * @return string
     */
    public function runTasks()
    {
        $tasks = $this->getDueTasks();

        $output = [];
        foreach ($tasks as $task) {
            $output[] = $this->runTask($task);
        }

        return "\n" . implode("\n", $output);
    }

    /**
     * @param Task $task
     * @return string
     */
    public function runTask(Task $task)
    {
        $app = new Application($this->kernel);
        $app->setAutoExit(false);
        $app->setCatchExceptions(false);

        $input = new ArrayInput(array_merge(
            ['command' => $task->getCommand()],
            $task->getArguments()
        ));

        $output = new BufferedOutput();

        $report = new TaskReport();
        $report->setCommand($input->__toString());

        try {
            $app->run($input, $output);
            $report->setSuccess(true);

            $commandOutput = 'command "' . $task->__toString() . '" successfully executed.';
        }
        catch (\Exception $e) {
            $report->setSuccess(false);
            $report->setException($e);

            $commandOutput = 'ERROR: command "' . $task->__toString() . '" is not defined.';
        }
        finally {
            $report->setOutput($output->fetch());
        }

        if ($this->reporter->isReportingEnabled()) {
            $this->reporter->sendReport($report);
        }

        return $commandOutput;
    }

    /**
     * @return Task[]
     */
    public function getDueTasks()
    {
        $config = $this->kernel->getContainer()->getParameter('troopers_cron.tasks');
        $tasks = Task::getTasksFromConfig($config);

        $dueTasks = [];
        foreach ($tasks as $task) {
            $cron = CronExpression::factory($task->getSchedule());
            if ($cron->isDue()) {
                $dueTasks[] = $task;
            }
        }

        return $dueTasks;
    }
}