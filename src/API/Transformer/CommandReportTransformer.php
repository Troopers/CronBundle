<?php

namespace Troopers\CronBundle\API\Transformer;


use Troopers\CronBundle\Reporting\Model\TaskReport;

class CommandReportTransformer
{
    /**
     * @param TaskReport $report
     * @return array
     */
    public function transform(TaskReport $report)
    {
        // status follows bash convention (0 if command has succeeded, 1 if an error occured)
        $status = $report->isSuccess() ? 0 : 1;

        return [
            'command' => $report->getCommand(),
            'status' => $status,
        ];
    }
}