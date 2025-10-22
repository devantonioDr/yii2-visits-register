<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AnalyticsDashboardAsset;

/* @var $this yii\web\View */

// Title and breadcrumbs are handled by the controller

// Register assets
AnalyticsDashboardAsset::register($this);

?>

<div class="event-feeder-index">
    <div class="row">
        <div class="col-md-12">
            <!-- Statistics Box -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-database"></i> Event Statistics
                    </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row" id="event-stats">
                        <div class="col-md-3">
                            <div class="description-block border-right">
                                <span class="description-percentage text-blue">
                                    <i class="fa fa-bar-chart"></i>
                                </span>
                                <h5 class="description-header" id="total-events">-</h5>
                                <span class="description-text">TOTAL EVENTS</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="description-block border-right">
                                <span class="description-percentage text-green">
                                    <i class="fa fa-eye"></i>
                                </span>
                                <h5 class="description-header" id="page-views">-</h5>
                                <span class="description-text">PAGE VIEWS</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="description-block border-right">
                                <span class="description-percentage text-yellow">
                                    <i class="fa fa-mouse-pointer"></i>
                                </span>
                                <h5 class="description-header" id="cta-clicks">-</h5>
                                <span class="description-text">CTA CLICKS</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="description-block">
                                <span class="description-percentage text-purple">
                                    <i class="fa fa-users"></i>
                                </span>
                                <h5 class="description-header" id="unique-visits">-</h5>
                                <span class="description-text">UNIQUE VISITS</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Single Event Creator -->
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-plus"></i> Create Single Event
                    </h3>
                </div>
                <div class="box-body">
                    <form id="single-event-form">
                        <div class="form-group">
                            <label for="url">URL *</label>
                            <input type="url" class="form-control" id="url" name="url" 
                                   placeholder="https://example.com/page" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="type">Event Type</label>
                            <select class="form-control" id="type" name="type">
                                <option value="page_view">Page View</option>
                                <option value="cta_click">CTA Click</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="cta-id-group" style="display: none;">
                            <label for="cta_id">CTA ID</label>
                            <input type="text" class="form-control" id="cta_id" name="cta_id" 
                                   placeholder="buy-now-button">
                        </div>
                        
                        <div class="form-group">
                            <label for="visit_id">Visit ID (optional)</label>
                            <input type="text" class="form-control" id="visit_id" name="visit_id" 
                                   placeholder="Leave empty for auto-generation">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="screen_resolution">Screen Resolution</label>
                                    <select class="form-control" id="screen_resolution" name="screen_resolution">
                                        <option value="">Auto</option>
                                        <option value="1920x1080">1920x1080</option>
                                        <option value="1366x768">1366x768</option>
                                        <option value="1440x900">1440x900</option>
                                        <option value="1536x864">1536x864</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="timezone">Timezone</label>
                                    <select class="form-control" id="timezone" name="timezone">
                                        <option value="">Auto</option>
                                        <option value="America/New_York">America/New_York</option>
                                        <option value="America/Los_Angeles">America/Los_Angeles</option>
                                        <option value="Europe/London">Europe/London</option>
                                        <option value="Europe/Madrid">Europe/Madrid</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="utm_source">UTM Source</label>
                                    <input type="text" class="form-control" id="utm_source" name="utm_source" 
                                           placeholder="google">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="utm_medium">UTM Medium</label>
                                    <input type="text" class="form-control" id="utm_medium" name="utm_medium" 
                                           placeholder="cpc">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="utm_campaign">UTM Campaign</label>
                                    <input type="text" class="form-control" id="utm_campaign" name="utm_campaign" 
                                           placeholder="summer-sale">
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Create Event
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bulk Event Generator -->
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-magic"></i> Bulk Event Generator
                    </h3>
                </div>
                <div class="box-body">
                    <form id="bulk-event-form">
                        <div class="form-group">
                            <label for="count">Number of Events</label>
                            <input type="number" class="form-control" id="count" name="count" 
                                   value="50" min="1" max="1000" required>
                            <small class="help-block">Between 1 and 1000 events</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="days">Time Range (days)</label>
                            <input type="number" class="form-control" id="days" name="days" 
                                   value="7" min="1" max="30" required>
                            <small class="help-block">Events will be distributed over the last N days</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="event_type">Event Type</label>
                            <select class="form-control" id="event_type" name="event_type">
                                <option value="mixed">Mixed (Page Views + CTA Clicks)</option>
                                <option value="page_views">Page Views Only</option>
                                <option value="cta_clicks">CTA Clicks Only</option>
                            </select>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            <strong>Bulk Generator Features:</strong>
                            <ul class="mb-0">
                                <li>Random URLs from sample pages</li>
                                <li>Random devices (desktop, mobile, tablet)</li>
                                <li>Random referrers and UTM parameters</li>
                                <li>Realistic timestamps distribution</li>
                                <li>Auto-generated visit IDs</li>
                            </ul>
                        </div>
                        
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-magic"></i> Generate Events
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-cogs"></i> Actions
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-info" id="refresh-stats">
                                <i class="fa fa-refresh"></i> Refresh Statistics
                            </button>
                            <button type="button" class="btn btn-warning" id="quick-fill">
                                <i class="fa fa-flash"></i> Quick Fill (100 events)
                            </button>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-danger" id="clear-all">
                                <i class="fa fa-trash"></i> Clear All Events
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Area -->
    <div class="row">
        <div class="col-md-12">
            <div id="results" style="display: none;">
                <div class="alert alert-success">
                    <h4><i class="fa fa-check"></i> Success!</h4>
                    <p id="result-message"></p>
                </div>
            </div>
            
            <div id="error-results" style="display: none;">
                <div class="alert alert-danger">
                    <h4><i class="fa fa-exclamation-triangle"></i> Error!</h4>
                    <p id="error-message"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs("
$(document).ready(function() {
    // Load initial statistics
    loadStats();
    
    // Event type change handler
    $('#type').change(function() {
        if ($(this).val() === 'cta_click') {
            $('#cta-id-group').show();
        } else {
            $('#cta-id-group').hide();
        }
    });
    
    // Single event form
    $('#single-event-form').on('submit', function(e) {
        e.preventDefault();
        createSingleEvent();
    });
    
    // Bulk event form
    $('#bulk-event-form').on('submit', function(e) {
        e.preventDefault();
        generateBulkEvents();
    });
    
    // Action buttons
    $('#refresh-stats').click(function() {
        loadStats();
    });
    
    $('#quick-fill').click(function() {
        $('#count').val(100);
        $('#days').val(7);
        $('#event_type').val('mixed');
        generateBulkEvents();
    });
    
    $('#clear-all').click(function() {
        if (confirm('Are you sure you want to delete ALL events? This action cannot be undone.')) {
            clearAllEvents();
        }
    });
});

function loadStats() {
    $.ajax({
        url: '" . Url::to(['stats']) . "',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#total-events').text(response.data.total_events.toLocaleString());
                $('#page-views').text(response.data.page_views.toLocaleString());
                $('#cta-clicks').text(response.data.cta_clicks.toLocaleString());
                $('#unique-visits').text(response.data.unique_visits.toLocaleString());
            }
        },
        error: function() {
            console.error('Failed to load statistics');
        }
    });
}

function createSingleEvent() {
    var formData = $('#single-event-form').serialize();
    
    $.ajax({
        url: '" . Url::to(['create-single']) . "',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showResult('Event created successfully! ID: ' + response.data.id);
                loadStats();
                $('#single-event-form')[0].reset();
            } else {
                showError(response.message);
            }
        },
        error: function() {
            showError('Failed to create event');
        }
    });
}

function generateBulkEvents() {
    var formData = $('#bulk-event-form').serialize();
    
    $.ajax({
        url: '" . Url::to(['generate-bulk']) . "',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showResult(response.message);
                loadStats();
            } else {
                showError(response.message);
            }
        },
        error: function() {
            showError('Failed to generate bulk events');
        }
    });
}

function clearAllEvents() {
    $.ajax({
        url: '" . Url::to(['clear-all']) . "',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showResult(response.message);
                loadStats();
            } else {
                showError(response.message);
            }
        },
        error: function() {
            showError('Failed to clear events');
        }
    });
}

function showResult(message) {
    $('#result-message').text(message);
    $('#results').show();
    $('#error-results').hide();
    
    setTimeout(function() {
        $('#results').fadeOut();
    }, 5000);
}

function showError(message) {
    $('#error-message').text(message);
    $('#error-results').show();
    $('#results').hide();
    
    setTimeout(function() {
        $('#error-results').fadeOut();
    }, 5000);
}
");
?>
