<?php

namespace Ollieread\Admin\Support;

use Google_Client;
use Google_Service_AnalyticsReporting;
use Google_Service_AnalyticsReporting_DateRange;
use Google_Service_AnalyticsReporting_Dimension;
use Google_Service_AnalyticsReporting_GetReportsRequest;
use Google_Service_AnalyticsReporting_Metric;
use Google_Service_AnalyticsReporting_ReportRequest;

class Analytics
{
    /**
     * @var Google_Service_AnalyticsReporting
     */
    private $google;

    /**
     * Analytics constructor.
     *
     * @throws \Google_Exception
     */
    public function __construct()
    {
        $this->loadAnalytics();
    }

    public function get(string $viewId, array $dimensions = [], array $metrics = []): array
    {
        $dateRange = new Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate('30daysAgo');
        $dateRange->setEndDate('today');

        $request = new Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($viewId);
        $request->setDateRanges($dateRange);

        if ($metrics) {
            $request->setMetrics($this->processMetrics($metrics));
        }

        if ($dimensions) {
            $request->setDimensions($this->processDimensions($dimensions));
        }

        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests([$request]);

        $report = $this->google->reports->batchGet($body)->getReports()[0];
        $data   = $report->getData();

        $results = [];

        foreach ($data->getRows() as $row) {
            $rowResults = [];
            /**
             * @var \Google_Service_AnalyticsReporting_ReportRow $row
             */
            $keys   = $row->getDimensions();

            foreach ($row->getMetrics() as $metric) {
                /**
                 * @var \Google_Service_AnalyticsReporting_DateRangeValues $metric
                 */
                $rowResults[] = array_combine($keys, $metric->getValues());
            }

            $results[] = $rowResults;
        }

        return array_merge(...$results);
    }

    /**
     * @throws \Google_Exception
     */
    private function loadAnalytics(): void
    {
        // Create the google client
        $client = new Google_Client;
        $client->setApplicationName('ollieread.com');
        $client->setAuthConfig(base_path('google_client_secret.json'));
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);

        // Create the analytics instance
        $this->google = new Google_Service_AnalyticsReporting($client);
    }

    private function processMetrics(array $metricNames): array
    {
        $metrics = [];

        foreach ($metricNames as $name) {
            $metric = new Google_Service_AnalyticsReporting_Metric();
            $metric->setExpression($name);

            $metrics[] = $metric;
        }

        return $metrics;
    }

    private function processDimensions(array $dimensionNames): array
    {
        $dimensions = [];

        foreach ($dimensionNames as $name) {
            $dimension = new Google_Service_AnalyticsReporting_Dimension();
            $dimension->setName($name);

            $dimensions[] = $dimension;
        }

        return $dimensions;
    }
}
