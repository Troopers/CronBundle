<?php

namespace Troopers\CronBundle\API\Client;


use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
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
        if ('form' === $this->api['format']) {
            return $this->sendFormData($report);
        }
        else {
            throw new Exception('The method ' . $this->api['method'] . ' method is not implemented');
        }
    }

    /**
     * @param TaskReport $report
     * @return ResponseInterface
     */
    private function sendFormData(TaskReport $report)
    {
        $data = $this->transformer->transform($report);

        $authentication = [];
        if (array_key_exists('api_key', $this->api)) {
            $authentication = ['apiKey' => $this->api['api_key']];
        }

        $response = $this->client->post('', [
            'form_params' => array_merge(
                $authentication,
                $data
            )]);

        return $response;
    }
}