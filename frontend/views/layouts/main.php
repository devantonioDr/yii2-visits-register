<?php

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use common\components\MyHelpers;
use common\models\page\PageSiteConfig;
use common\models\page\PageBrandColorsConfig;
use common\models\page\PageGoogleTagManagerConfig;
use common\models\page\PageCustomScriptConfig;

/* @var $this \yii\web\View */
/* @var $content string */

// ========================================
// SITE CONFIGURATION
// ========================================
$siteConfigModel = PageSiteConfig::getConfig();
$site_config = $siteConfigModel ? [
    'name' => $siteConfigModel->name ?? 'FamiliaCuts',
    'title' => $siteConfigModel->title ?? 'FamiliaCuts - Your style, our passion',
    'description' => $siteConfigModel->description ?? 'The best barbershop in the city with premium services',
    'language' => 'en'
] : [
    'name' => 'FamiliaCuts',
    'title' => 'FamiliaCuts - Your style, our passion',
    'description' => 'The best barbershop in the city with premium services',
    'language' => 'en'
];

// ========================================
// BRAND COLORS - Material Design 3 System
// ========================================
$brandColorsModel = PageBrandColorsConfig::getConfig();
$brand_colors = $brandColorsModel ? [
    // Primary colors
    'primary' => $brandColorsModel->primary ?? '#2b9d1d',
    'on-primary' => $brandColorsModel->on_primary ?? '#ffffff',
    'primary-container' => $brandColorsModel->primary_container ?? '#b8f0ad',
    'on-primary-container' => $brandColorsModel->on_primary_container ?? '#002201',

    // Secondary colors
    'secondary' => $brandColorsModel->secondary ?? '#d4af37',
    'on-secondary' => $brandColorsModel->on_secondary ?? '#1a1a1a',
    'secondary-container' => $brandColorsModel->secondary_container ?? '#fff4d9',
    'on-secondary-container' => $brandColorsModel->on_secondary_container ?? '#3d2f00',

    // Tertiary colors
    'tertiary' => $brandColorsModel->tertiary ?? '#1a1a1a',
    'on-tertiary' => $brandColorsModel->on_tertiary ?? '#ffffff',
    'tertiary-container' => $brandColorsModel->tertiary_container ?? '#3d3d3d',
    'on-tertiary-container' => $brandColorsModel->on_tertiary_container ?? '#e5e5e5',

    // Error colors
    'error' => $brandColorsModel->error ?? '#ba1a1a',
    'on-error' => $brandColorsModel->on_error ?? '#ffffff',
    'error-container' => $brandColorsModel->error_container ?? '#ffdad6',
    'on-error-container' => $brandColorsModel->on_error_container ?? '#410002',

    // Surface colors
    'surface' => $brandColorsModel->surface ?? '#fefefe',
    'on-surface' => $brandColorsModel->on_surface ?? '#1c1c1c',
    'surface-variant' => $brandColorsModel->surface_variant ?? '#e7e7e7',
    'on-surface-variant' => $brandColorsModel->on_surface_variant ?? '#5f5f5f',

    // Outline & borders
    'outline' => $brandColorsModel->outline ?? '#c7c7c7',
    'outline-variant' => $brandColorsModel->outline_variant ?? '#e0e0e0',

    // Shadow & overlay
    'shadow' => $brandColorsModel->shadow ?? '#000000',
    'scrim' => $brandColorsModel->scrim ?? '#000000',

    // Inverse colors (for dark elements on light backgrounds)
    'inverse-surface' => $brandColorsModel->inverse_surface ?? '#1c1c1c',
    'inverse-on-surface' => $brandColorsModel->inverse_on_surface ?? '#f4f4f4',
    'inverse-primary' => $brandColorsModel->inverse_primary ?? '#9bdc8f',

    // Background
    'background' => $brandColorsModel->background ?? '#f8fdf7',
    'on-background' => $brandColorsModel->on_background ?? '#1a1c1a'
] : [
    // Primary colors
    'primary' => '#2b9d1d',
    'on-primary' => '#ffffff',
    'primary-container' => '#b8f0ad',
    'on-primary-container' => '#002201',

    // Secondary colors
    'secondary' => '#d4af37',
    'on-secondary' => '#1a1a1a',
    'secondary-container' => '#fff4d9',
    'on-secondary-container' => '#3d2f00',

    // Tertiary colors
    'tertiary' => '#1a1a1a',
    'on-tertiary' => '#ffffff',
    'tertiary-container' => '#3d3d3d',
    'on-tertiary-container' => '#e5e5e5',

    // Error colors
    'error' => '#ba1a1a',
    'on-error' => '#ffffff',
    'error-container' => '#ffdad6',
    'on-error-container' => '#410002',

    // Surface colors
    'surface' => '#fefefe',
    'on-surface' => '#1c1c1c',
    'surface-variant' => '#e7e7e7',
    'on-surface-variant' => '#5f5f5f',

    // Outline & borders
    'outline' => '#c7c7c7',
    'outline-variant' => '#e0e0e0',

    // Shadow & overlay
    'shadow' => '#000000',
    'scrim' => '#000000',

    // Inverse colors (for dark elements on light backgrounds)
    'inverse-surface' => '#1c1c1c',
    'inverse-on-surface' => '#f4f4f4',
    'inverse-primary' => '#9bdc8f',

    // Background
    'background' => '#f8fdf7',
    'on-background' => '#1a1c1a'
];

// ========================================
// ANALYTICS CONFIGURATION (from Yii2 params)
// ========================================
$analytics_config = Yii::$app->params['analytics'] ?? [
    'enabled' => true,
    'api_endpoint' => 'https://backoffice.familiacuts.com/v1/event/track',
    'debug_mode' => true,
    'track_page_views' => true,
    'track_cta_clicks' => true,
    'track_scroll_depth' => false,
    'track_time_on_page' => false,
    'cta_buttons' => [
        'cta-hero-main',
        'cta-hero-portfolio',
        'cta-portfolio-section',
        'cta-about-section',
        'cta-services-section'
    ]
];

// ========================================
// GOOGLE TAG MANAGER & GOOGLE TAG CONFIGURATION
// ========================================
$gtmConfig = PageGoogleTagManagerConfig::getConfig();
$gtm_id = ($gtmConfig && $gtmConfig->isActive()) ? $gtmConfig->gtm_id : null;
$gtag_id = ($gtmConfig && $gtmConfig->isGtagActive()) ? $gtmConfig->gtag_id : null;

// ========================================
// CUSTOM SCRIPTS CONFIGURATION
// ========================================
$customScripts = PageCustomScriptConfig::getEnabledScripts();



// ========================================
// HELPER FUNCTIONS
// ========================================
// Helper functions are now in common\components\MyHelpers

// Make variables available to view
$this->params['site_config'] = $site_config;
$this->params['brand_colors'] = $brand_colors;
$this->params['analytics_config'] = $analytics_config;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?php echo MyHelpers::e($site_config['language']); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo MyHelpers::e($site_config['description']); ?>">
    <?= Html::csrfMetaTags() ?>
    <title><?php echo MyHelpers::e($site_config['title']); ?></title>

    <?php if ($gtag_id): ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo MyHelpers::e($gtag_id); ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?php echo MyHelpers::e($gtag_id); ?>');
    </script>
    <!-- End Google tag (gtag.js) -->
    <?php endif; ?>

    <?php if ($gtm_id): ?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?php echo MyHelpers::e($gtm_id); ?>');</script>
    <!-- End Google Tag Manager -->
    <?php endif; ?>

    <?php if (!empty($customScripts)): ?>
    <!-- Custom Scripts -->
    <?php foreach ($customScripts as $script): ?>
    <!-- Custom Script: <?php echo Html::encode($script->label); ?> -->
    <?php echo $script->script; ?>
    <!-- End Custom Script: <?php echo Html::encode($script->label); ?> -->
    <?php endforeach; ?>
    <!-- End Custom Scripts -->
    <?php endif; ?>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= Url::to('@web/css/styles.css') ?>">

    <!-- Dynamic CSS Colors -->
    <style>
        <?php echo MyHelpers::getCSSColors($brand_colors); ?>
    </style>
    
    <?php $this->head() ?>
</head>

<body>
    <?php if ($gtm_id): ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo MyHelpers::e($gtm_id); ?>"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php endif; ?>
    <?php $this->beginBody() ?>

    <?= $content ?>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Analytics Tracking Module -->
    <script src="<?= Url::to('@web/js/tracking.js') ?>"></script>
    
    <!-- Initialize AOS and Smooth Scroll -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS (Animate On Scroll)
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 1000,
                    once: true,
                    offset: 100
                });
            }

            // Smooth scroll for internal links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href !== '#' && href.length > 1) {
                        e.preventDefault();
                        const target = document.querySelector(href);
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }
                });
            });

            console.log('✅ Landing page loaded successfully (PHP version)');
            
            // ========================================
            // Initialize Analytics Tracking
            // ========================================
            if (typeof AnalyticsTracker !== 'undefined') {
                // Get configuration from PHP
                const analyticsConfig = <?php echo json_encode($analytics_config); ?>;
                
                // Create tracker instance
                window.tracker = new AnalyticsTracker({
                    enabled: analyticsConfig.enabled,
                    apiEndpoint: analyticsConfig.api_endpoint,
                    debugMode: analyticsConfig.debug_mode,
                    trackPageViews: analyticsConfig.track_page_views,
                    trackCTAClicks: analyticsConfig.track_cta_clicks,
                    ctaButtons: analyticsConfig.cta_buttons
                });
                
                // Initialize tracking
                window.tracker.init();
                
                console.log('✅ Analytics tracker ready');
            } else {
                console.warn('⚠️ Analytics tracker not loaded');
            }
        });
    </script>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>

