/**
 * Analytics Dashboard JavaScript
 */

$(document).ready(function() {
    
    // Initialize dashboard
    initDashboard();
    
    // Auto-refresh functionality (optional)
    // setInterval(refreshDashboard, 300000); // 5 minutes
    
    // Date range picker enhancement
    initDateRangePicker();
    
    // Export functionality
    initExportFeatures();
    
});

/**
 * Initialize dashboard components
 */
function initDashboard() {
    console.log('Analytics Dashboard initialized');
    
    // Add loading states to charts
    $('.box-body canvas').each(function() {
        $(this).closest('.box-body').addClass('loading');
    });
    
    // Remove loading states after charts are rendered
    setTimeout(function() {
        $('.box-body').removeClass('loading');
    }, 1000);
}

/**
 * Initialize date range picker
 */
function initDateRangePicker() {
    // Add date validation
    $('input[type="date"]').on('change', function() {
        var fromDate = $('input[name="from"]').val();
        var toDate = $('input[name="to"]').val();
        
        if (fromDate && toDate) {
            if (new Date(fromDate) > new Date(toDate)) {
                alert('From date cannot be greater than To date');
                $(this).val('');
            }
        }
    });
}

/**
 * Initialize export features
 */
function initExportFeatures() {
    // Add click tracking for export button
    $('a[href*="export"]').on('click', function() {
        // Track export event
        trackEvent('export_clicked', {
            from_date: $('input[name="from"]').val(),
            to_date: $('input[name="to"]').val()
        });
    });
}

/**
 * Refresh dashboard data
 */
function refreshDashboard() {
    // Reload the page to get fresh data
    window.location.reload();
}

/**
 * Track custom events (if needed for internal analytics)
 */
function trackEvent(eventType, data) {
    // This could be used to track dashboard interactions
    console.log('Dashboard event:', eventType, data);
    
    // You could send this to your analytics API
    // fetch('/v1/event/track', {
    //     method: 'POST',
    //     headers: {
    //         'Content-Type': 'application/json',
    //     },
    //     body: JSON.stringify({
    //         url: window.location.href,
    //         type: 'dashboard_interaction',
    //         meta: {
    //             interaction_type: eventType,
    //             ...data
    //         }
    //     })
    // });
}

/**
 * Format numbers with commas
 */
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

/**
 * Show loading state
 */
function showLoading(element) {
    $(element).addClass('loading');
}

/**
 * Hide loading state
 */
function hideLoading(element) {
    $(element).removeClass('loading');
}

/**
 * Show notification
 */
function showNotification(message, type) {
    type = type || 'info';
    
    // Create notification element
    var notification = $('<div class="alert alert-' + type + ' alert-dismissible" role="alert">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>' +
        message +
        '</div>');
    
    // Add to top of dashboard
    $('.analytics-dashboard-index').prepend(notification);
    
    // Auto-dismiss after 5 seconds
    setTimeout(function() {
        notification.fadeOut();
    }, 5000);
}

/**
 * Chart utility functions
 */
var ChartUtils = {
    
    /**
     * Get color for device type
     */
    getDeviceColor: function(device) {
        var colors = {
            'mobile': '#00a65a',
            'desktop': '#3c8dbc',
            'tablet': '#f39c12'
        };
        return colors[device] || '#777';
    },
    
    /**
     * Get color for conversion rate
     */
    getConversionColor: function(rate) {
        if (rate > 5) return '#5cb85c';
        if (rate > 2) return '#f0ad4e';
        return '#d9534f';
    },
    
    /**
     * Format percentage
     */
    formatPercentage: function(value) {
        return value.toFixed(1) + '%';
    }
};

/**
 * Table enhancement functions
 */
function enhanceTables() {
    // Add sorting indicators
    $('.table th').on('click', function() {
        var table = $(this).closest('table');
        var tbody = table.find('tbody');
        var rows = tbody.find('tr').toArray();
        
        // Simple sorting logic (you might want to use a proper sorting library)
        var column = $(this).index();
        var isAsc = $(this).hasClass('sort-asc');
        
        // Remove existing sort classes
        table.find('th').removeClass('sort-asc sort-desc');
        
        // Add new sort class
        $(this).addClass(isAsc ? 'sort-desc' : 'sort-asc');
        
        // Sort rows (basic implementation)
        rows.sort(function(a, b) {
            var aVal = $(a).find('td').eq(column).text();
            var bVal = $(b).find('td').eq(column).text();
            
            // Try to parse as numbers
            var aNum = parseFloat(aVal.replace(/[^\d.-]/g, ''));
            var bNum = parseFloat(bVal.replace(/[^\d.-]/g, ''));
            
            if (!isNaN(aNum) && !isNaN(bNum)) {
                return isAsc ? bNum - aNum : aNum - bNum;
            } else {
                return isAsc ? bVal.localeCompare(aVal) : aVal.localeCompare(bVal);
            }
        });
        
        // Re-append sorted rows
        tbody.empty().append(rows);
    });
}

// Initialize table enhancements
$(document).ready(function() {
    enhanceTables();
});

