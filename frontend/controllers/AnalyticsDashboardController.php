<?php

namespace frontend\controllers;

use common\models\Event;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Analytics Dashboard Controller
 * 
 * Provides analytics dashboard for event tracking data
 */
class AnalyticsDashboardController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Main dashboard view
     * @return string
     */
    public function actionIndex()
    {
        $this->view->title = 'Analytics Dashboard';
        $this->view->params['breadcrumbs'][] = $this->view->title;
        
        $dateRange = $this->getDateRange();
        $fromDate = $dateRange['from'];
        $toDate = $dateRange['to'];

        // Get main KPIs
        $kpis = $this->getMainKpis($fromDate, $toDate);
        
        // Get chart data
        $chartData = $this->getChartData($fromDate, $toDate);
        
        // Get table data
        $topPages = $this->getTopPages($fromDate, $toDate);
        $topCtas = $this->getTopCtas($fromDate, $toDate);
        $deviceStats = $this->getDeviceStats($fromDate, $toDate);
        $dailyStats = $this->getDailyStats($fromDate, $toDate);

        return $this->render('index', [
            'kpis' => $kpis,
            'chartData' => $chartData,
            'topPages' => $topPages,
            'topCtas' => $topCtas,
            'deviceStats' => $deviceStats,
            'dailyStats' => $dailyStats,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }

    /**
     * Get date range from request or default to last 30 days
     * @return array
     */
    protected function getDateRange()
    {
        $request = Yii::$app->request;
        $fromDate = $request->get('from', date('Y-m-d', strtotime('-30 days')));
        $toDate = $request->get('to', date('Y-m-d'));
        
        return [
            'from' => $fromDate,
            'to' => $toDate,
        ];
    }

    /**
     * Get main KPIs for the dashboard
     * @param string $fromDate
     * @param string $toDate
     * @return array
     */
    protected function getMainKpis($fromDate, $toDate)
    {
        $query = Event::find()
            ->where(['>=', 'ts', $fromDate . ' 00:00:00'])
            ->andWhere(['<=', 'ts', $toDate . ' 23:59:59']);

        // Total events
        $totalEvents = $query->count();

        // Unique visits
        $uniqueVisits = $query->select('visit_id')->distinct()->count();

        // CTA clicks
        $ctaClicks = $query->andWhere(['type' => Event::TYPE_CTA_CLICK])->count();

        // Page views
        $pageViews = $query->andWhere(['type' => Event::TYPE_PAGE_VIEW])->count();

        // Conversion rate
        $conversionRate = $pageViews > 0 ? round(($ctaClicks / $pageViews) * 100, 2) : 0;

        // Device distribution
        $deviceStats = $query->select(['device', 'COUNT(*) as count'])
            ->groupBy('device')
            ->asArray()
            ->all();

        $deviceDistribution = [];
        foreach ($deviceStats as $stat) {
            $deviceDistribution[$stat['device']] = $stat['count'];
        }

        $totalDeviceEvents = array_sum($deviceDistribution);
        $mobilePercentage = isset($deviceDistribution['mobile']) 
            ? round(($deviceDistribution['mobile'] / $totalDeviceEvents) * 100, 1) 
            : 0;

        return [
            'totalEvents' => $totalEvents,
            'uniqueVisits' => $uniqueVisits,
            'ctaClicks' => $ctaClicks,
            'conversionRate' => $conversionRate,
            'mobilePercentage' => $mobilePercentage,
            'deviceDistribution' => $deviceDistribution,
        ];
    }

    /**
     * Get chart data for trends
     * @param string $fromDate
     * @param string $toDate
     * @return array
     */
    protected function getChartData($fromDate, $toDate)
    {
        // Daily events trend
        $dailyEvents = Event::find()
            ->select(['DATE(ts) as date', 'COUNT(*) as total', 'type'])
            ->where(['>=', 'ts', $fromDate . ' 00:00:00'])
            ->andWhere(['<=', 'ts', $toDate . ' 23:59:59'])
            ->groupBy(['DATE(ts)', 'type'])
            ->asArray()
            ->all();

        // Debug: Log the query results
        Yii::info('Chart data query returned: ' . count($dailyEvents) . ' records', __METHOD__);
        if (count($dailyEvents) > 0) {
            Yii::info('Sample data: ' . json_encode(array_slice($dailyEvents, 0, 3)), __METHOD__);
        }

        $trendData = [];
        $dates = [];
        
        // Generate all dates in range
        $current = strtotime($fromDate);
        $end = strtotime($toDate);
        while ($current <= $end) {
            $date = date('Y-m-d', $current);
            $dates[] = $date;
            $trendData[$date] = [
                'date' => $date,
                'page_views' => 0,
                'cta_clicks' => 0,
                'total' => 0,
            ];
            $current = strtotime('+1 day', $current);
        }

        // Fill in actual data
        foreach ($dailyEvents as $event) {
            $date = $event['date'];
            if (isset($trendData[$date])) {
                // Map event types to the correct keys
                if ($event['type'] === Event::TYPE_PAGE_VIEW) {
                    $trendData[$date]['page_views'] = (int)$event['total'];
                } elseif ($event['type'] === Event::TYPE_CTA_CLICK) {
                    $trendData[$date]['cta_clicks'] = (int)$event['total'];
                }
                $trendData[$date]['total'] += (int)$event['total'];
            }
        }

        // Debug: Log final trend data
        Yii::info('Final trend data: ' . json_encode(array_values($trendData)), __METHOD__);

        // Calculate trend statistics
        $trendStats = $this->calculateTrendStats($trendData, $fromDate, $toDate);

        return [
            'trendData' => array_values($trendData),
            'dates' => $dates,
            'trendStats' => $trendStats,
        ];
    }

    /**
     * Calculate trend statistics for the footer
     * @param array $trendData
     * @param string $fromDate
     * @param string $toDate
     * @return array
     */
    protected function calculateTrendStats($trendData, $fromDate, $toDate)
    {
        $totalEvents = array_sum(array_column($trendData, 'total'));
        $totalPageViews = array_sum(array_column($trendData, 'page_views'));
        $totalCtaClicks = array_sum(array_column($trendData, 'cta_clicks'));
        
        // Calculate unique visits from the period
        $uniqueVisits = Event::find()
            ->select('visit_id')
            ->where(['>=', 'ts', $fromDate . ' 00:00:00'])
            ->andWhere(['<=', 'ts', $toDate . ' 23:59:59'])
            ->distinct()
            ->count();

        // Calculate previous period for comparison
        $daysDiff = (strtotime($toDate) - strtotime($fromDate)) / (60 * 60 * 24) + 1;
        $prevFromDate = date('Y-m-d', strtotime($fromDate . ' -' . $daysDiff . ' days'));
        $prevToDate = date('Y-m-d', strtotime($fromDate . ' -1 day'));

        $prevTotalEvents = Event::find()
            ->where(['>=', 'ts', $prevFromDate . ' 00:00:00'])
            ->andWhere(['<=', 'ts', $prevToDate . ' 23:59:59'])
            ->count();

        $prevUniqueVisits = Event::find()
            ->select('visit_id')
            ->where(['>=', 'ts', $prevFromDate . ' 00:00:00'])
            ->andWhere(['<=', 'ts', $prevToDate . ' 23:59:59'])
            ->distinct()
            ->count();

        $prevCtaClicks = Event::find()
            ->where(['>=', 'ts', $prevFromDate . ' 00:00:00'])
            ->andWhere(['<=', 'ts', $prevToDate . ' 23:59:59'])
            ->andWhere(['type' => Event::TYPE_CTA_CLICK])
            ->count();

        // Calculate percentage changes
        $eventsChange = $this->calculatePercentageChange($prevTotalEvents, $totalEvents);
        $visitsChange = $this->calculatePercentageChange($prevUniqueVisits, $uniqueVisits);
        $clicksChange = $this->calculatePercentageChange($prevCtaClicks, $totalCtaClicks);
        
        // Goal completions (same as CTA clicks for now)
        $goalCompletions = $totalCtaClicks;
        $prevGoalCompletions = $prevCtaClicks;
        $goalsChange = $this->calculatePercentageChange($prevGoalCompletions, $goalCompletions);

        return [
            'totalEvents' => $totalEvents,
            'totalVisits' => $uniqueVisits,
            'totalClicks' => $totalCtaClicks,
            'goalCompletions' => $goalCompletions,
            'eventsChange' => $eventsChange,
            'visitsChange' => $visitsChange,
            'clicksChange' => $clicksChange,
            'goalsChange' => $goalsChange,
        ];
    }

    /**
     * Calculate percentage change between two values
     * @param int $oldValue
     * @param int $newValue
     * @return array
     */
    protected function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return [
                'percentage' => $newValue > 0 ? 100 : 0,
                'direction' => $newValue > 0 ? 'up' : 'neutral',
                'class' => $newValue > 0 ? 'text-green' : 'text-muted'
            ];
        }

        $percentage = round((($newValue - $oldValue) / $oldValue) * 100, 1);
        $direction = $percentage > 0 ? 'up' : ($percentage < 0 ? 'down' : 'neutral');
        
        $class = 'text-muted';
        if ($percentage > 0) {
            $class = 'text-green';
        } elseif ($percentage < 0) {
            $class = 'text-red';
        }

        return [
            'percentage' => abs($percentage),
            'direction' => $direction,
            'class' => $class
        ];
    }

    /**
     * Get top pages data
     * @param string $fromDate
     * @param string $toDate
     * @return ArrayDataProvider
     */
    protected function getTopPages($fromDate, $toDate)
    {
        $data = Event::find()
            ->select([
                'page',
                'COUNT(*) as total_events',
                'COUNT(DISTINCT visit_id) as unique_visits',
                'COUNT(CASE WHEN type = "cta_click" THEN 1 END) as cta_clicks',
            ])
            ->where(['>=', 'ts', $fromDate . ' 00:00:00'])
            ->andWhere(['<=', 'ts', $toDate . ' 23:59:59'])
            ->groupBy('page')
            ->orderBy(['total_events' => SORT_DESC])
            ->limit(10)
            ->asArray()
            ->all();

        // Calculate conversion rate for each page
        foreach ($data as &$page) {
            $pageViews = $page['total_events'] - $page['cta_clicks'];
            $page['conversion_rate'] = $pageViews > 0 
                ? round(($page['cta_clicks'] / $pageViews) * 100, 2) 
                : 0;
        }

        return new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
        ]);
    }

    /**
     * Get top CTAs data
     * @param string $fromDate
     * @param string $toDate
     * @return ArrayDataProvider
     */
    protected function getTopCtas($fromDate, $toDate)
    {
        $data = Event::find()
            ->select([
                'cta_id',
                'page',
                'COUNT(*) as clicks',
                'COUNT(DISTINCT visit_id) as unique_clicks',
            ])
            ->where(['>=', 'ts', $fromDate . ' 00:00:00'])
            ->andWhere(['<=', 'ts', $toDate . ' 23:59:59'])
            ->andWhere(['type' => Event::TYPE_CTA_CLICK])
            ->andWhere(['not', ['cta_id' => null]])
            ->groupBy(['cta_id', 'page'])
            ->orderBy(['clicks' => SORT_DESC])
            ->limit(10)
            ->asArray()
            ->all();

        return new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
        ]);
    }

    /**
     * Get device statistics
     * @param string $fromDate
     * @param string $toDate
     * @return array
     */
    protected function getDeviceStats($fromDate, $toDate)
    {
        $data = Event::find()
            ->select([
                'device',
                'COUNT(*) as total_events',
                'COUNT(DISTINCT visit_id) as unique_visits',
                'COUNT(CASE WHEN type = "cta_click" THEN 1 END) as cta_clicks',
            ])
            ->where(['>=', 'ts', $fromDate . ' 00:00:00'])
            ->andWhere(['<=', 'ts', $toDate . ' 23:59:59'])
            ->andWhere(['not', ['device' => null]])
            ->groupBy('device')
            ->asArray()
            ->all();

        $totalEvents = array_sum(ArrayHelper::getColumn($data, 'total_events'));
        
        foreach ($data as &$device) {
            $device['percentage'] = $totalEvents > 0 
                ? round(($device['total_events'] / $totalEvents) * 100, 1) 
                : 0;
            
            $pageViews = $device['total_events'] - $device['cta_clicks'];
            $device['conversion_rate'] = $pageViews > 0 
                ? round(($device['cta_clicks'] / $pageViews) * 100, 2) 
                : 0;
        }

        return $data;
    }

    /**
     * Export data to CSV
     * @return \yii\web\Response
     */
    public function actionExport()
    {
        $dateRange = $this->getDateRange();
        $fromDate = $dateRange['from'];
        $toDate = $dateRange['to'];

        $data = Event::find()
            ->where(['>=', 'ts', $fromDate . ' 00:00:00'])
            ->andWhere(['<=', 'ts', $toDate . ' 23:59:59'])
            ->orderBy(['ts' => SORT_DESC])
            ->asArray()
            ->all();

        $filename = 'analytics_export_' . $fromDate . '_to_' . $toDate . '.csv';
        
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $response->headers->add('Content-Type', 'text/csv');
        $response->headers->add('Content-Disposition', 'attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        
        // CSV headers
        if (!empty($data)) {
            fputcsv($output, array_keys($data[0]));
            
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        
        return $response;
    }

    /**
     * Get daily statistics for the selected date range
     * @param string $fromDate
     * @param string $toDate
     * @return array
     */
    protected function getDailyStats($fromDate, $toDate)
    {
        // Get daily aggregated data
        $dailyData = Event::find()
            ->select([
                'DATE(ts) as date',
                'COUNT(*) as total_events',
                'COUNT(DISTINCT visit_id) as unique_visits',
                'SUM(CASE WHEN type = "page_view" THEN 1 ELSE 0 END) as page_views',
                'SUM(CASE WHEN type = "cta_click" THEN 1 ELSE 0 END) as cta_clicks',
                'COUNT(DISTINCT CASE WHEN type = "cta_click" THEN visit_id END) as unique_cta_clicks',
                'COUNT(DISTINCT device) as device_types',
                'COUNT(DISTINCT page) as unique_pages'
            ])
            ->where(['>=', 'ts', $fromDate . ' 00:00:00'])
            ->andWhere(['<=', 'ts', $toDate . ' 23:59:59'])
            ->groupBy('DATE(ts)')
            ->orderBy('DATE(ts) DESC')
            ->asArray()
            ->all();

        // Get device breakdown for each day
        $deviceBreakdown = Event::find()
            ->select([
                'DATE(ts) as date',
                'device',
                'COUNT(*) as count'
            ])
            ->where(['>=', 'ts', $fromDate . ' 00:00:00'])
            ->andWhere(['<=', 'ts', $toDate . ' 23:59:59'])
            ->groupBy(['DATE(ts)', 'device'])
            ->asArray()
            ->all();

        // Organize device data by date
        $deviceByDate = [];
        foreach ($deviceBreakdown as $device) {
            $deviceByDate[$device['date']][$device['device']] = $device['count'];
        }

        // Enhance daily data with additional calculations
        foreach ($dailyData as &$day) {
            $date = $day['date'];
            
            // Calculate conversion rate
            $day['conversion_rate'] = $day['unique_visits'] > 0 ? 
                round(($day['unique_cta_clicks'] / $day['unique_visits']) * 100, 1) : 0;
            
            // Calculate events per visit
            $day['events_per_visit'] = $day['unique_visits'] > 0 ? 
                round($day['total_events'] / $day['unique_visits'], 1) : 0;
            
            // Add device breakdown
            $day['devices'] = $deviceByDate[$date] ?? [];
            
            // Calculate device percentages
            $totalDeviceEvents = array_sum($day['devices']);
            foreach ($day['devices'] as $device => $count) {
                $day['device_percentages'][$device] = $totalDeviceEvents > 0 ? 
                    round(($count / $totalDeviceEvents) * 100, 1) : 0;
            }
            
            // Format date for display
            $day['formatted_date'] = date('M j, Y', strtotime($date));
            $day['day_of_week'] = date('l', strtotime($date));
            
            // Add trend indicators (compare with previous day)
            $day['trend_events'] = $this->calculateDayTrend($dailyData, $date, 'total_events');
            $day['trend_visits'] = $this->calculateDayTrend($dailyData, $date, 'unique_visits');
            $day['trend_clicks'] = $this->calculateDayTrend($dailyData, $date, 'cta_clicks');
        }

        return $dailyData;
    }

    /**
     * Calculate trend for a specific day compared to previous day
     * @param array $dailyData
     * @param string $currentDate
     * @param string $field
     * @return array
     */
    protected function calculateDayTrend($dailyData, $currentDate, $field)
    {
        $currentIndex = array_search($currentDate, array_column($dailyData, 'date'));
        $previousIndex = $currentIndex + 1; // Since data is ordered DESC
        
        if ($previousIndex >= count($dailyData)) {
            return [
                'percentage' => 0,
                'direction' => 'neutral',
                'class' => 'text-muted'
            ];
        }
        
        $currentValue = $dailyData[$currentIndex][$field];
        $previousValue = $dailyData[$previousIndex][$field];
        
        return $this->calculatePercentageChange($previousValue, $currentValue);
    }
}
