<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Advanced Project Template</h1>
    <br>
</p>

Yii 2 Advanced Project Template is a skeleton [Yii 2](http://www.yiiframework.com/) application best for
developing complex Web applications with multiple tiers.

The template includes three tiers: front end, back end, and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![build](https://github.com/yiisoft/yii2-app-advanced/workflows/build/badge.svg)](https://github.com/yiisoft/yii2-app-advanced/actions?query=workflow%3Abuild)

DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```

CONFIGURATION
-------------

### Analytics Configuration

Analytics configuration is managed through Yii2 parameters. Default values are defined in `common/config/params.php` and can be overridden in `common/config/params-local.php` for local environment-specific settings.

**Available settings:**

- `enabled` - Enable or disable analytics tracking (default: `true`)
- `api_endpoint` - Analytics API endpoint URL (default: `https://backoffice.familiacuts.com/v1/event/track`)
- `debug_mode` - Enable debug mode for detailed console logs (default: `true`)
- `track_page_views` - Track page views (default: `true`)
- `track_cta_clicks` - Track CTA button clicks (default: `true`)
- `track_scroll_depth` - Track scroll depth (future feature, default: `false`)
- `track_time_on_page` - Track time on page (future feature, default: `false`)

**Example override in `common/config/params-local.php`:**

```php
return [
    'analytics' => [
        'enabled' => false,
        'api_endpoint' => 'http://localhost:20080/v1/event/track',
        'debug_mode' => true,
        'track_page_views' => true,
        'track_cta_clicks' => true,
        'track_scroll_depth' => false,
        'track_time_on_page' => false,
    ],
];
```

**Note:** Values in `params-local.php` override defaults from `params.php`. The `params-local.php` file is typically not committed to version control, making it perfect for local development settings.