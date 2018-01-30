<?php

namespace Troopers\CronBundle\Reporting;


use Symfony\Component\HttpKernel\KernelInterface;
use Troopers\CronBundle\API\Client\ApiClient;
use Troopers\CronBundle\Reporting\Model\TaskReport;

class ReportManager
{
    /**
     * @var Reporter
     */
    private $reporter;

    public function __construct(KernelInterface $kernel)
    {
        if ($kernel->getContainer()->hasParameter('troopers_cron.reporting')) {
            $reporting = $kernel->getContainer()->getParameter('troopers_cron.reporting');

            if (array_key_exists('api', $reporting)) {
                $api = $reporting['api'];
                $this->reporter = new ApiClient($api);
            }
        }
    }

    /**
     * @return bool
     */
    public function isReportingEnabled(): bool
    {
        return $this->reporter !== null;
    }

    public function sendReport(TaskReport $report)
    {
        $this->reporter->sendTaskReport($report);
    }
}