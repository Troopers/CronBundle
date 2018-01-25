<?php

namespace Troopers\CronBundle\Reporting;


use Troopers\CronBundle\Reporting\Model\TaskReport;

interface Reporter
{
    public function sendTaskReport(TaskReport $taskReport);
}