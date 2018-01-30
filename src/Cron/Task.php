<?php

namespace Troopers\CronBundle\Cron;


class Task
{
    /**
     * @var string
     */
    private $command;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @var string
     */
    private $schedule;

    public function __construct()
    {
        $this->arguments = [];
    }


    public function __toString()
    {
        $str = $this->command;

        foreach ($this->arguments as $key => $value) {
            // check if the argument is a named argument or an option
            if ('-' === substr($key, 0, 1)) {
                $str = $str . ' ' . $key;
                if (null !== $value) {
                    $str = $str . ' ' . $value;
                }
            }
            else {
                $str = $str . ' ' . $value;
            }
        }

        return $str;
    }

    /**
     * @param $tasksConfig
     * @return Task[]
     */
    public static function getTasksFromConfig($tasksConfig)
    {
        $tasks = [];
        foreach ($tasksConfig as $taskConfig) {
            $task = new Task();
            $task->setCommand($taskConfig['command']);
            $task->setSchedule($taskConfig['schedule']);
            if (isset($taskConfig['arguments'])) {
                $task->setArguments($taskConfig['arguments']);
            }
            $tasks[] = $task;
        }

        return $tasks;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    public function setCommand(string $command)
    {
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function getSchedule(): string
    {
        return $this->schedule;
    }

    /**
     * @param string $schedule
     */
    public function setSchedule(string $schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }
}