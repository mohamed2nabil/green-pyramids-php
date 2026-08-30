<?php
// includes/services/GoogleAnalyticsService.php

require_once __DIR__ . '/../config/google_analytics.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\RunReportRequest;

class GoogleAnalyticsService
{
    private ?BetaAnalyticsDataClient $client = null;
    private string $propertyId;
    private string $cacheDir;

    public function __construct()
    {
        if (!defined('GA4_PROPERTY_ID') || !defined('GOOGLE_APPLICATION_CREDENTIALS_PATH')) {
            throw new RuntimeException('Google Analytics configuration is unavailable.');
        }

        if (!ga4_credentials_available()) {
            throw new RuntimeException('Google Analytics credentials are unavailable.');
        }

        $this->propertyId = GA4_PROPERTY_ID;
        $this->cacheDir = dirname(__DIR__, 2) . '/admin/cache/analytics';
        
        // Instantiate the Google client
        $this->client = new BetaAnalyticsDataClient([
            'credentials' => GOOGLE_APPLICATION_CREDENTIALS_PATH
        ]);
    }

    /**
     * Helper to resolve DateRanges
     */
    private function resolveDateRanges(string $range): ?array
    {
        $today = new DateTime('today');
        $format = static fn (DateTime $date): string => $date->format('Y-m-d');
        $relativePeriod = static function (DateTime $end, int $days) use ($format): array {
            $start = clone $end;
            $start->modify('-' . ($days - 1) . ' days');
            return ['start' => $format($start), 'end' => $format($end)];
        };

        switch ($range) {
            case 'today':
                return [
                    'current' => $relativePeriod($today, 1),
                    'previous' => $relativePeriod((clone $today)->modify('-1 day'), 1)
                ];
            case 'yesterday':
                $yesterday = (clone $today)->modify('-1 day');
                return [
                    'current' => $relativePeriod($yesterday, 1),
                    'previous' => $relativePeriod((clone $today)->modify('-2 days'), 1)
                ];
            case 'last_7_days':
                $days = 7;
                break;
            case 'last_30_days':
                $days = 30;
                break;
            case 'last_90_days':
                $days = 90;
                break;
            case 'this_month':
                $startOfThisMonth = new DateTime('first day of this month');
                $daysInMonth = (int)$today->diff($startOfThisMonth)->format('%a') + 1;
                return [
                    'current' => ['start' => $format($startOfThisMonth), 'end' => $format($today)],
                    'previous' => $relativePeriod((clone $startOfThisMonth)->modify('-1 day'), $daysInMonth)
                ];
            case 'previous_month':
                $start = new DateTime('first day of last month');
                $end = new DateTime('last day of last month');
                $daysInMonth = (int)$end->diff($start)->format('%a') + 1;
                return [
                    'current' => ['start' => $format($start), 'end' => $format($end)],
                    'previous' => $relativePeriod((clone $start)->modify('-1 day'), $daysInMonth)
                ];
        }

        if (isset($days)) {
            return [
                'current' => $relativePeriod($today, $days),
                'previous' => $relativePeriod((clone $today)->modify("-{$days} days"), $days)
            ];
        }

        return null;
    }

    /**
     * Get Cached data or run callback
     */
    private function getCached(string $action, string $range, callable $callback)
    {
        $cacheKey = md5($action . '_' . $range);
        $cacheFile = $this->cacheDir . '/' . $cacheKey . '.json';
        $cacheTime = 600; // 10 minutes

        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
            $data = json_decode(file_get_contents($cacheFile), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            }
        }

        $result = $callback();
        
        // Ensure cache directory exists and is writable
        if (!file_exists($this->cacheDir)) {
            @mkdir($this->cacheDir, 0755, true);
        }
        @file_put_contents($cacheFile, json_encode($result));
        return $result;
    }

    /**
     * Retrieve overview metrics
     */
    public function getOverview(string $range): array
    {
        return $this->getCached('overview', $range, function() use ($range) {
            $dates = $this->resolveDateRanges($range);
            if (!$dates) {
                throw new Exception("Invalid date range specified.");
            }

            $currentMetrics = $this->runReport($dates['current'], [
                'activeUsers', 'totalUsers', 'newUsers', 'sessions', 
                'engagedSessions', 'screenPageViews', 'averageSessionDuration', 'engagementRate'
            ]);

            $previousMetrics = $this->runReport($dates['previous'], [
                'activeUsers', 'totalUsers', 'newUsers', 'sessions', 
                'engagedSessions', 'screenPageViews', 'averageSessionDuration', 'engagementRate'
            ]);

            $result = [];
            foreach ($currentMetrics as $metric => $currValue) {
                $prevValue = $previousMetrics[$metric] ?? 0;
                $diff = $currValue - $prevValue;
                $pct = 0.0;
                if ($prevValue > 0) {
                    $pct = ($diff / $prevValue) * 100;
                }
                
                $result[$metric] = [
                    'current' => $currValue,
                    'previous' => $prevValue,
                    'difference' => $diff,
                    'percentage_change' => round($pct, 2)
                ];
            }
            return $result;
        });
    }

    /**
     * Retrieve daily users/sessions timeline
     */
    public function getTimeline(string $range): array
    {
        return $this->getCached('timeline', $range, function() use ($range) {
            $dates = $this->resolveDateRanges($range);
            if (!$dates) {
                throw new Exception("Invalid date range specified.");
            }

            $response = $this->client->runReport(new RunReportRequest([
                'property' => 'properties/' . $this->propertyId,
                'date_ranges' => [
                    new DateRange([
                        'start_date' => $dates['current']['start'],
                        'end_date' => $dates['current']['end']
                    ])
                ],
                'dimensions' => [
                    new Dimension(['name' => 'date'])
                ],
                'metrics' => [
                    new Metric(['name' => 'activeUsers']),
                    new Metric(['name' => 'sessions'])
                ]
            ]));

            $rows = [];
            foreach ($response->getRows() as $row) {
                $rawDate = $row->getDimensionValues()[0]->getValue(); // YYYYMMDD
                $formattedDate = substr($rawDate, 0, 4) . '-' . substr($rawDate, 4, 2) . '-' . substr($rawDate, 6, 2);
                $rows[] = [
                    'date' => $formattedDate,
                    'activeUsers' => (int)$row->getMetricValues()[0]->getValue(),
                    'sessions' => (int)$row->getMetricValues()[1]->getValue()
                ];
            }

            // Sort by date ascending
            usort($rows, function($a, $b) {
                return strcmp($a['date'], $b['date']);
            });

            return $rows;
        });
    }

    /**
     * Retrieve Top Pages
     */
    public function getTopPages(string $range, int $limit = 10): array
    {
        return $this->getCached('top_pages_' . $limit, $range, function() use ($range, $limit) {
            $dates = $this->resolveDateRanges($range);
            if (!$dates) {
                throw new Exception("Invalid date range specified.");
            }

            $response = $this->client->runReport(new RunReportRequest([
                'property' => 'properties/' . $this->propertyId,
                'date_ranges' => [
                    new DateRange([
                        'start_date' => $dates['current']['start'],
                        'end_date' => $dates['current']['end']
                    ])
                ],
                'dimensions' => [
                    new Dimension(['name' => 'pagePath']),
                    new Dimension(['name' => 'pageTitle'])
                ],
                'metrics' => [
                    new Metric(['name' => 'screenPageViews']),
                    new Metric(['name' => 'activeUsers'])
                ]
            ]));

            $pages = [];
            foreach ($response->getRows() as $row) {
                $pages[] = [
                    'pagePath' => $row->getDimensionValues()[0]->getValue(),
                    'pageTitle' => $row->getDimensionValues()[1]->getValue(),
                    'screenPageViews' => (int)$row->getMetricValues()[0]->getValue(),
                    'activeUsers' => (int)$row->getMetricValues()[1]->getValue()
                ];
            }

            // Sort by views descending
            usort($pages, function($a, $b) {
                return $b['screenPageViews'] <=> $a['screenPageViews'];
            });

            return array_slice($pages, 0, $limit);
        });
    }

    /**
     * Retrieve Traffic Sources
     */
    public function getTrafficSources(string $range): array
    {
        return $this->getCached('traffic_sources', $range, function() use ($range) {
            $dates = $this->resolveDateRanges($range);
            if (!$dates) {
                throw new Exception("Invalid date range specified.");
            }

            $response = $this->client->runReport(new RunReportRequest([
                'property' => 'properties/' . $this->propertyId,
                'date_ranges' => [
                    new DateRange([
                        'start_date' => $dates['current']['start'],
                        'end_date' => $dates['current']['end']
                    ])
                ],
                'dimensions' => [
                    new Dimension(['name' => 'sessionDefaultChannelGroup']),
                    new Dimension(['name' => 'sessionSource']),
                    new Dimension(['name' => 'sessionMedium'])
                ],
                'metrics' => [
                    new Metric(['name' => 'sessions']),
                    new Metric(['name' => 'activeUsers'])
                ]
            ]));

            $sources = [];
            foreach ($response->getRows() as $row) {
                $sources[] = [
                    'sessionDefaultChannelGroup' => $row->getDimensionValues()[0]->getValue(),
                    'sessionSource' => $row->getDimensionValues()[1]->getValue(),
                    'sessionMedium' => $row->getDimensionValues()[2]->getValue(),
                    'sessions' => (int)$row->getMetricValues()[0]->getValue(),
                    'activeUsers' => (int)$row->getMetricValues()[1]->getValue()
                ];
            }

            return $sources;
        });
    }

    /**
     * Retrieve Countries
     */
    public function getCountries(string $range, int $limit = 10): array
    {
        return $this->getCached('countries_' . $limit, $range, function() use ($range, $limit) {
            $dates = $this->resolveDateRanges($range);
            if (!$dates) {
                throw new Exception("Invalid date range specified.");
            }

            $response = $this->client->runReport(new RunReportRequest([
                'property' => 'properties/' . $this->propertyId,
                'date_ranges' => [
                    new DateRange([
                        'start_date' => $dates['current']['start'],
                        'end_date' => $dates['current']['end']
                    ])
                ],
                'dimensions' => [
                    new Dimension(['name' => 'country'])
                ],
                'metrics' => [
                    new Metric(['name' => 'activeUsers']),
                    new Metric(['name' => 'sessions'])
                ]
            ]));

            $countries = [];
            foreach ($response->getRows() as $row) {
                $countries[] = [
                    'country' => $row->getDimensionValues()[0]->getValue(),
                    'activeUsers' => (int)$row->getMetricValues()[0]->getValue(),
                    'sessions' => (int)$row->getMetricValues()[1]->getValue()
                ];
            }

            // Sort by activeUsers descending
            usort($countries, function($a, $b) {
                return $b['activeUsers'] <=> $a['activeUsers'];
            });

            return array_slice($countries, 0, $limit);
        });
    }

    /**
     * Retrieve Devices
     */
    public function getDevices(string $range): array
    {
        return $this->getCached('devices', $range, function() use ($range) {
            $dates = $this->resolveDateRanges($range);
            if (!$dates) {
                throw new Exception("Invalid date range specified.");
            }

            $response = $this->client->runReport(new RunReportRequest([
                'property' => 'properties/' . $this->propertyId,
                'date_ranges' => [
                    new DateRange([
                        'start_date' => $dates['current']['start'],
                        'end_date' => $dates['current']['end']
                    ])
                ],
                'dimensions' => [
                    new Dimension(['name' => 'deviceCategory'])
                ],
                'metrics' => [
                    new Metric(['name' => 'activeUsers']),
                    new Metric(['name' => 'sessions'])
                ]
            ]));

            $devices = [];
            foreach ($response->getRows() as $row) {
                $devices[] = [
                    'deviceCategory' => $row->getDimensionValues()[0]->getValue(),
                    'activeUsers' => (int)$row->getMetricValues()[0]->getValue(),
                    'sessions' => (int)$row->getMetricValues()[1]->getValue()
                ];
            }

            return $devices;
        });
    }

    /**
     * Helper to run report for basic metrics
     */
    private function runReport(array $dateRange, array $metricNames): array
    {
        $metrics = [];
        foreach ($metricNames as $m) {
            $metrics[] = new Metric(['name' => $m]);
        }

        $response = $this->client->runReport(new RunReportRequest([
            'property' => 'properties/' . $this->propertyId,
            'date_ranges' => [
                new DateRange([
                    'start_date' => $dateRange['start'],
                    'end_date' => $dateRange['end']
                ])
            ],
            'metrics' => $metrics
        ]));

        $result = [];
        $rows = $response->getRows();
        if (count($rows) > 0) {
            $row = $rows[0];
            foreach ($metricNames as $index => $m) {
                $val = $row->getMetricValues()[$index]->getValue();
                if (in_array($m, ['engagementRate', 'averageSessionDuration'], true)) {
                    $result[$m] = (float)$val;
                } else {
                    $result[$m] = (int)$val;
                }
            }
        } else {
            // Default zero values
            foreach ($metricNames as $m) {
                $result[$m] = 0;
            }
        }

        return $result;
    }
}
