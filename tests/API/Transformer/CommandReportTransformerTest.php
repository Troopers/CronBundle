<?php

namespace Troopers\CronBundle\Tests\API\Transformer;


use PHPUnit\Framework\TestCase;
use Troopers\CronBundle\API\Model\TaskReport;
use Troopers\CronBundle\API\Transformer\CommandReportTransformer;

class CommandReportTransformerTest extends TestCase
{
    /**
     * Test if the transformed array contains the required keys
     */
    public function testArrayHasKeys()
    {
        $transformer = new CommandReportTransformer();

        $report = new TaskReport();
        $report->setCommand('');
        $report->setSuccess(true);

        $data = $transformer->transform($report);

        $this->assertArrayHasKey('command', $data);
        $this->assertArrayHasKey('status', $data);
    }

    /**
     * Test if the transformed array contains the data of the TaskReport
     */
    public function testKeysMatchData()
    {
        $transformer = new CommandReportTransformer();

        $report = new TaskReport();
        $report->setCommand('cache:clear');
        $report->setSuccess(true);

        $data = $transformer->transform($report);

        $this->assertSame($report->getCommand(), $data['command']);
        $this->assertSame($report->isSuccess(), $data['status']);
    }
}