<?php

namespace Troopers\CronBundle\API\Client;


use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Troopers\CronBundle\Reporting\Reporter;
use Troopers\CronBundle\Reporting\Model\TaskReport;
use Troopers\CronBundle\API\Transformer\CommandReportTransformer;

class ApiClient implements Reporter
{
    private $client;
    private $api;
    private $transformer;

    public function __construct(KernelInterface $kernel, CommandReportTransformer $transformer)
    {
        $this->api = $kernel->getContainer()->getParameter('troopers_cron.reporting')['api'];
        $this->client = new Client(['base_uri' => $this->api['url']]);
        $this->kernel = $kernel;
        $this->transformer = $transformer;
    }

    /**
     * @param TaskReport $report
     * @return ResponseInterface
     */
    public function sendTaskReport(TaskReport $report)
    {
        $apiKey = $this->api['api_key'];
        $data = $this->transformer->transform($report);

        $response = $this->client->post('/api/cron', [
            'form_params' => array_merge(
                ['apiKey' => $apiKey],
                $data
        )]);

        return $response;
    }
}