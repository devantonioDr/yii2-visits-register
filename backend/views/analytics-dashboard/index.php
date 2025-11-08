<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\assets\AnalyticsDashboardAsset;

/* @var $this yii\web\View */
/* @var $kpis array */
/* @var $chartData array */
/* @var $topPages ArrayDataProvider */
/* @var $topCtas ArrayDataProvider */
/* @var $deviceStats array */
/* @var $fromDate string */
/* @var $toDate string */

// Title and breadcrumbs are handled by the controller

// Register assets
AnalyticsDashboardAsset::register($this);

// Register Chart.js
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js');

?>

<div class="analytics-dashboard-index">
        <!-- Date Range Filter Box -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-calendar"></i> Date Range Filter
                        </h3>
                        <div class="box-tools pull-right">
                            <?= Html::a('<i class="fa fa-download"></i> Export CSV', 
                                ['export', 'from' => $fromDate, 'to' => $toDate], 
                                ['class' => 'btn btn-success btn-sm']) ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <form method="get" class="form-inline">
                            <div class="form-group">
                                <label for="from">From:</label>
                                <input type="date" name="from" value="<?= $fromDate ?>" class="form-control" style="margin: 0 10px;">
                            </div>
                            <div class="form-group">
                                <label for="to">To:</label>
                                <input type="date" name="to" value="<?= $toDate ?>" class="form-control" style="margin: 0 10px;">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPIs Cards -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?= number_format($kpis['totalEvents']) ?></h3>
                        <p>Total Events</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-bar-chart"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?= number_format($kpis['uniqueVisits']) ?></h3>
                        <p>Unique Visits</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?= number_format($kpis['ctaClicks']) ?></h3>
                        <p>CTA Clicks</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-mouse-pointer"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3><?= $kpis['conversionRate'] ?>%</h3>
                        <p>Conversion Rate</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-percent"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Events Trend Chart -->
            <div class="col-md-8">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-line-chart"></i> Events Trend
                        </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="eventsTrendChart" width="400" height="200"></canvas>
                        <?php if (empty($chartData['trendData'])): ?>
                        <div class="text-center" style="margin-top: 20px;">
                            <p class="text-muted">
                                <i class="fa fa-info-circle"></i> 
                                No event data available. 
                                <a href="<?= Url::to(['/event-feeder/index']) ?>" class="text-primary">
                                    <i class="fa fa-database"></i> Use Event Feeder
                                </a> 
                                to generate test data.
                            </p>
                        </div>
                        <?php else: ?>
                        <div class="text-center" style="margin-top: 10px;">
                            <small class="text-muted">
                                <i class="fa fa-check"></i> 
                                Data loaded: <?= count($chartData['trendData']) ?> days
                                | Total Events: <?= isset($chartData['trendStats']['totalEvents']) ? number_format($chartData['trendStats']['totalEvents']) : '0' ?>
                            </small>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-3 col-xs-6">
                                <div class="description-block border-right">
                                    <span class="description-percentage <?= isset($chartData['trendStats']['eventsChange']['class']) ? $chartData['trendStats']['eventsChange']['class'] : 'text-muted' ?>">
                                        <i class="fa fa-caret-<?= isset($chartData['trendStats']['eventsChange']['direction']) ? $chartData['trendStats']['eventsChange']['direction'] : 'neutral' ?>"></i> 
                                        <?= isset($chartData['trendStats']['eventsChange']['percentage']) ? $chartData['trendStats']['eventsChange']['percentage'] : '0' ?>%
                                    </span>
                                    <h5 class="description-header"><?= isset($chartData['trendStats']['totalEvents']) ? number_format($chartData['trendStats']['totalEvents']) : '0' ?></h5>
                                    <span class="description-text">TOTAL EVENTS</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-6">
                                <div class="description-block border-right">
                                    <span class="description-percentage <?= isset($chartData['trendStats']['visitsChange']['class']) ? $chartData['trendStats']['visitsChange']['class'] : 'text-muted' ?>">
                                        <i class="fa fa-caret-<?= isset($chartData['trendStats']['visitsChange']['direction']) ? $chartData['trendStats']['visitsChange']['direction'] : 'neutral' ?>"></i> 
                                        <?= isset($chartData['trendStats']['visitsChange']['percentage']) ? $chartData['trendStats']['visitsChange']['percentage'] : '0' ?>%
                                    </span>
                                    <h5 class="description-header"><?= isset($chartData['trendStats']['totalVisits']) ? number_format($chartData['trendStats']['totalVisits']) : '0' ?></h5>
                                    <span class="description-text">TOTAL VISITS</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-6">
                                <div class="description-block border-right">
                                    <span class="description-percentage <?= isset($chartData['trendStats']['clicksChange']['class']) ? $chartData['trendStats']['clicksChange']['class'] : 'text-muted' ?>">
                                        <i class="fa fa-caret-<?= isset($chartData['trendStats']['clicksChange']['direction']) ? $chartData['trendStats']['clicksChange']['direction'] : 'neutral' ?>"></i> 
                                        <?= isset($chartData['trendStats']['clicksChange']['percentage']) ? $chartData['trendStats']['clicksChange']['percentage'] : '0' ?>%
                                    </span>
                                    <h5 class="description-header"><?= isset($chartData['trendStats']['totalClicks']) ? number_format($chartData['trendStats']['totalClicks']) : '0' ?></h5>
                                    <span class="description-text">TOTAL CLICKS</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-6">
                                <div class="description-block">
                                    <span class="description-percentage <?= isset($chartData['trendStats']['goalsChange']['class']) ? $chartData['trendStats']['goalsChange']['class'] : 'text-muted' ?>">
                                        <i class="fa fa-caret-<?= isset($chartData['trendStats']['goalsChange']['direction']) ? $chartData['trendStats']['goalsChange']['direction'] : 'neutral' ?>"></i> 
                                        <?= isset($chartData['trendStats']['goalsChange']['percentage']) ? $chartData['trendStats']['goalsChange']['percentage'] : '0' ?>%
                                    </span>
                                    <h5 class="description-header"><?= isset($chartData['trendStats']['goalCompletions']) ? number_format($chartData['trendStats']['goalCompletions']) : '0' ?></h5>
                                    <span class="description-text">GOAL COMPLETIONS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Device Distribution Chart -->
            <div class="col-md-4">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-pie-chart"></i> Device Distribution
                        </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <canvas id="deviceChart" width="400" height="200"></canvas>
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">
                            <?php foreach ($deviceStats as $device): ?>
                            <li>
                                <a href="#">
                                    <?= ucfirst($device['device']) ?>
                                    <span class="pull-right badge bg-<?= $device['device'] === 'mobile' ? 'green' : ($device['device'] === 'desktop' ? 'blue' : 'yellow') ?>">
                                        <?= $device['percentage'] ?>%
                                    </span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Row -->
        <div class="row">
            <!-- Top Pages -->
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-globe"></i> Top Pages
                        </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <?= GridView::widget([
                                'dataProvider' => $topPages,
                                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                                'layout' => '{items}',
                                'columns' => [
                                    [
                                        'attribute' => 'page',
                                        'label' => 'Page',
                                        'format' => 'raw',
                                        'value' => function($model) {
                                            return Html::a(
                                                Html::encode(substr($model['page'], 0, 40) . (strlen($model['page']) > 40 ? '...' : '')),
                                                $model['page'],
                                                ['target' => '_blank', 'title' => $model['page'], 'class' => 'text-blue']
                                            );
                                        }
                                    ],
                                    [
                                        'attribute' => 'total_events',
                                        'label' => 'Events',
                                        'format' => 'raw',
                                        'value' => function($model) {
                                            return '<span class="badge bg-blue">' . number_format($model['total_events']) . '</span>';
                                        }
                                    ],
                                    [
                                        'attribute' => 'unique_visits',
                                        'label' => 'Visits',
                                        'format' => 'raw',
                                        'value' => function($model) {
                                            return '<span class="badge bg-green">' . number_format($model['unique_visits']) . '</span>';
                                        }
                                    ],
                                    [
                                        'attribute' => 'conversion_rate',
                                        'label' => 'Conv. Rate',
                                        'format' => 'raw',
                                        'value' => function($model) {
                                            $rate = $model['conversion_rate'];
                                            $class = $rate > 5 ? 'bg-green' : ($rate > 2 ? 'bg-yellow' : 'bg-red');
                                            return "<span class='badge $class'><strong>{$rate}%</strong></span>";
                                        }
                                    ],
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top CTAs -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-mouse-pointer"></i> Top CTAs
                        </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <?= GridView::widget([
                                'dataProvider' => $topCtas,
                                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                                'layout' => '{items}',
                                'columns' => [
                                    [
                                        'attribute' => 'cta_id',
                                        'label' => 'CTA ID',
                                        'format' => 'raw',
                                        'value' => function($model) {
                                            return Html::tag('code', Html::encode($model['cta_id']), ['class' => 'text-primary']);
                                        }
                                    ],
                                    [
                                        'attribute' => 'page',
                                        'label' => 'Page',
                                        'format' => 'raw',
                                        'value' => function($model) {
                                            return Html::a(
                                                Html::encode(substr($model['page'], 0, 25) . (strlen($model['page']) > 25 ? '...' : '')),
                                                $model['page'],
                                                ['target' => '_blank', 'title' => $model['page'], 'class' => 'text-blue']
                                            );
                                        }
                                    ],
                                    [
                                        'attribute' => 'clicks',
                                        'label' => 'Clicks',
                                        'format' => 'raw',
                                        'value' => function($model) {
                                            return '<span class="badge bg-primary">' . number_format($model['clicks']) . '</span>';
                                        }
                                    ],
                                    [
                                        'attribute' => 'unique_clicks',
                                        'label' => 'Unique',
                                        'format' => 'raw',
                                        'value' => function($model) {
                                            return '<span class="badge bg-green">' . number_format($model['unique_clicks']) . '</span>';
                                        }
                                    ],
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Statistics -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-calendar"></i> Daily Statistics
                        </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover daily-stats-table">
                                <thead>
                                    <tr>
                                        <th><i class="fa fa-calendar"></i> Date</th>
                                        <th><i class="fa fa-bar-chart"></i> Total Events</th>
                                        <th><i class="fa fa-users"></i> Unique Visits</th>
                                        <th><i class="fa fa-eye"></i> Page Views</th>
                                        <th><i class="fa fa-mouse-pointer"></i> CTA Clicks</th>
                                        <th><i class="fa fa-percent"></i> Conversion Rate</th>
                                        <th><i class="fa fa-mobile"></i> Device Breakdown</th>
                                        <th><i class="fa fa-trending-up"></i> Trends</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($dailyStats)): ?>
                                        <?php foreach ($dailyStats as $day): ?>
                                        <tr>
                                            <td>
                                                <strong><?= $day['formatted_date'] ?></strong><br>
                                                <small class="text-muted"><?= $day['day_of_week'] ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-blue"><?= number_format($day['total_events']) ?></span>
                                                <?php if ($day['trend_events']['percentage'] > 0): ?>
                                                    <br><small class="<?= $day['trend_events']['class'] ?>">
                                                        <i class="fa fa-caret-<?= $day['trend_events']['direction'] ?>"></i>
                                                        <?= $day['trend_events']['percentage'] ?>%
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-green"><?= number_format($day['unique_visits']) ?></span>
                                                <?php if ($day['trend_visits']['percentage'] > 0): ?>
                                                    <br><small class="<?= $day['trend_visits']['class'] ?>">
                                                        <i class="fa fa-caret-<?= $day['trend_visits']['direction'] ?>"></i>
                                                        <?= $day['trend_visits']['percentage'] ?>%
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?= number_format($day['page_views']) ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-yellow"><?= number_format($day['cta_clicks']) ?></span>
                                                <?php if ($day['trend_clicks']['percentage'] > 0): ?>
                                                    <br><small class="<?= $day['trend_clicks']['class'] ?>">
                                                        <i class="fa fa-caret-<?= $day['trend_clicks']['direction'] ?>"></i>
                                                        <?= $day['trend_clicks']['percentage'] ?>%
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $day['conversion_rate'] > 5 ? 'green' : ($day['conversion_rate'] > 2 ? 'yellow' : 'red') ?>">
                                                    <strong><?= $day['conversion_rate'] ?>%</strong>
                                                </span>
                                                <br><small class="text-muted"><?= $day['events_per_visit'] ?> events/visit</small>
                                            </td>
                                            <td class="device-breakdown">
                                                <?php if (!empty($day['devices'])): ?>
                                                    <?php foreach ($day['devices'] as $device => $count): ?>
                                                        <span class="label label-<?= $device === 'mobile' ? 'success' : ($device === 'desktop' ? 'primary' : 'warning') ?>">
                                                            <?= ucfirst($device) ?>: <?= $count ?>
                                                        </span><br>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">No data</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group-vertical btn-group-xs">
                                                    <span class="btn btn-<?= $day['trend_events']['direction'] === 'up' ? 'success' : ($day['trend_events']['direction'] === 'down' ? 'danger' : 'default') ?> btn-xs">
                                                        <i class="fa fa-bar-chart"></i> Events
                                                    </span>
                                                    <span class="btn btn-<?= $day['trend_visits']['direction'] === 'up' ? 'success' : ($day['trend_visits']['direction'] === 'down' ? 'danger' : 'default') ?> btn-xs">
                                                        <i class="fa fa-users"></i> Visits
                                                    </span>
                                                    <span class="btn btn-<?= $day['trend_clicks']['direction'] === 'up' ? 'success' : ($day['trend_clicks']['direction'] === 'down' ? 'danger' : 'default') ?> btn-xs">
                                                        <i class="fa fa-mouse-pointer"></i> Clicks
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">
                                                <i class="fa fa-info-circle"></i> No daily data available for the selected date range.
                                                <br><a href="<?= Url::to(['/event-feeder/index']) ?>" class="text-primary">Generate test data</a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Device Statistics -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-mobile"></i> Device Statistics
                        </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th><i class="fa fa-mobile"></i> Device</th>
                                        <th><i class="fa fa-bar-chart"></i> Events</th>
                                        <th><i class="fa fa-pie-chart"></i> Percentage</th>
                                        <th><i class="fa fa-users"></i> Unique Visits</th>
                                        <th><i class="fa fa-mouse-pointer"></i> CTA Clicks</th>
                                        <th><i class="fa fa-percent"></i> Conversion Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($deviceStats as $device): ?>
                                    <tr>
                                        <td>
                                            <span class="label label-<?= $device['device'] === 'mobile' ? 'success' : ($device['device'] === 'desktop' ? 'primary' : 'warning') ?>">
                                                <i class="fa fa-<?= $device['device'] === 'mobile' ? 'mobile' : ($device['device'] === 'desktop' ? 'desktop' : 'tablet') ?>"></i>
                                                <?= ucfirst($device['device']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-blue"><?= number_format($device['total_events']) ?></span>
                                        </td>
                                        <td>
                                            <div class="progress progress-xs">
                                                <div class="progress-bar progress-bar-<?= $device['device'] === 'mobile' ? 'success' : ($device['device'] === 'desktop' ? 'primary' : 'warning') ?>"
                                                     style="width: <?= $device['percentage'] ?>%"></div>
                                            </div>
                                            <small class="text-muted"><?= $device['percentage'] ?>%</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-green"><?= number_format($device['unique_visits']) ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-yellow"><?= number_format($device['cta_clicks']) ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $device['conversion_rate'] > 5 ? 'green' : ($device['conversion_rate'] > 2 ? 'yellow' : 'red') ?>">
                                                <strong><?= $device['conversion_rate'] ?>%</strong>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

<?php
// JavaScript for charts
$trendData = json_encode(isset($chartData['trendData']) ? $chartData['trendData'] : []);
$deviceData = json_encode($deviceStats);

// Debug: Show data in console
$this->registerJs("
console.log('=== Analytics Dashboard Debug ===');
console.log('Trend Data Array Length:', " . $trendData . ".length);
console.log('Trend Data:', " . $trendData . ");

// Validate trend data structure
if (" . $trendData . ".length > 0) {
    console.log('First day data:', " . $trendData . "[0]);
    console.log('Page views in first day:', " . $trendData . "[0].page_views);
    console.log('CTA clicks in first day:', " . $trendData . "[0].cta_clicks);
    
    // Count days with actual data
    var daysWithData = " . $trendData . ".filter(day => day.total > 0).length;
    console.log('Days with events:', daysWithData, '/', " . $trendData . ".length);
} else {
    console.warn('⚠️ No trend data available for chart!');
}

console.log('Device Data:', " . $deviceData . ");
");

$this->registerJs("
// Events Trend Chart
const trendCtx = document.getElementById('eventsTrendChart').getContext('2d');
const trendData = $trendData;

// Format dates for better display
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
};

// Check if we have data
if (trendData && trendData.length > 0) {
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: trendData.map(item => formatDate(item.date)),
            datasets: [
                {
                    label: 'Page Views',
                    data: trendData.map(item => item.page_views || 0),
                    borderColor: '#3c8dbc',
                    backgroundColor: 'rgba(60, 141, 188, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3c8dbc',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                },
                {
                    label: 'CTA Clicks',
                    data: trendData.map(item => item.cta_clicks || 0),
                    borderColor: '#f39c12',
                    backgroundColor: 'rgba(243, 156, 18, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#f39c12',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }
            ]
        },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 20,
                    font: {
                        size: 12,
                        weight: 'bold'
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: '#3c8dbc',
                borderWidth: 1,
                cornerRadius: 6,
                displayColors: true,
                callbacks: {
                    title: function(context) {
                        const date = new Date(trendData[context[0].dataIndex].date);
                        return date.toLocaleDateString('en-US', { 
                            weekday: 'long', 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        });
                    },
                    label: function(context) {
                        return context.dataset.label + ': ' + context.parsed.y.toLocaleString();
                    }
                }
            }
        },
        scales: {
            x: {
                display: true,
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 11
                    },
                    maxTicksLimit: 8
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)',
                    drawBorder: false
                },
                ticks: {
                    font: {
                        size: 11
                    },
                    callback: function(value) {
                        return value.toLocaleString();
                    }
                }
            }
        },
        elements: {
            point: {
                hoverBackgroundColor: '#fff'
            }
        }
    }
});
} else {
    // Show message when no data
    trendCtx.font = '16px Arial';
    trendCtx.fillStyle = '#999';
    trendCtx.textAlign = 'center';
    trendCtx.fillText('No data available', trendCtx.canvas.width / 2, trendCtx.canvas.height / 2);
    trendCtx.fillText('Use Event Feeder to generate test data', trendCtx.canvas.width / 2, trendCtx.canvas.height / 2 + 25);
}

// Device Distribution Chart
const deviceCtx = document.getElementById('deviceChart').getContext('2d');
const deviceData = $deviceData;

new Chart(deviceCtx, {
    type: 'doughnut',
    data: {
        labels: deviceData.map(item => item.device.charAt(0).toUpperCase() + item.device.slice(1)),
        datasets: [{
            data: deviceData.map(item => item.total_events),
            backgroundColor: [
                '#00a65a', // green for mobile
                '#3c8dbc', // blue for desktop
                '#f39c12'  // orange for tablet
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
");
?>

