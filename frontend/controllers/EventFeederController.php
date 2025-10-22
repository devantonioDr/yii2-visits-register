<?php

namespace frontend\controllers;

use common\models\Event;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\helpers\Json;

/**
 * Event Feeder Controller
 * 
 * Provides interface for generating test events for analytics dashboard
 */
class EventFeederController extends Controller
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
     * Main feeder interface
     * @return string
     */
    public function actionIndex()
    {
        $this->view->title = 'Event Feeder';
        $this->view->params['breadcrumbs'][] = $this->view->title;

        return $this->render('index');
    }

    /**
     * Create a single test event
     * @return array
     */
    public function actionCreateSingle()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $data = $request->post();

        try {
            // Validate required fields
            if (empty($data['url'])) {
                return ['success' => false, 'message' => 'URL is required'];
            }

            // Create event
            $event = Event::createFromUrl($data['url'], $data['type'] ?? Event::TYPE_PAGE_VIEW, [
                'cta_id' => $data['cta_id'] ?? null,
                'visit_id' => $data['visit_id'] ?? null,
                'screen_resolution' => $data['screen_resolution'] ?? null,
                'timezone' => $data['timezone'] ?? null,
                'utm_source' => $data['utm_source'] ?? null,
                'utm_medium' => $data['utm_medium'] ?? null,
                'utm_campaign' => $data['utm_campaign'] ?? null,
            ]);

            if ($event->save()) {
                return [
                    'success' => true,
                    'message' => 'Event created successfully',
                    'data' => [
                        'id' => $event->id,
                        'type' => $event->type,
                        'page' => $event->page,
                        'visit_id' => $event->visit_id,
                        'ts' => $event->ts,
                    ]
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to create event',
                    'errors' => $event->getErrors()
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate bulk test events
     * @return array
     */
    public function actionGenerateBulk()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $data = $request->post();

        try {
            $count = (int)($data['count'] ?? 10);
            $days = (int)($data['days'] ?? 7);
            $eventType = $data['event_type'] ?? 'mixed';

            if ($count < 1 || $count > 1000) {
                return ['success' => false, 'message' => 'Count must be between 1 and 1000'];
            }

            if ($days < 1 || $days > 30) {
                return ['success' => false, 'message' => 'Days must be between 1 and 30'];
            }

            $generated = $this->generateBulkEvents($count, $days, $eventType);

            return [
                'success' => true,
                'message' => "Generated {$generated} test events successfully",
                'data' => [
                    'count' => $generated,
                    'days' => $days,
                    'type' => $eventType
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Clear all test events
     * @return array
     */
    public function actionClearAll()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $deleted = Event::deleteAll();
            
            return [
                'success' => true,
                'message' => "Deleted {$deleted} events successfully"
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get event statistics
     * @return array
     */
    public function actionStats()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $totalEvents = Event::find()->count();
            $pageViews = Event::find()->where(['type' => Event::TYPE_PAGE_VIEW])->count();
            $ctaClicks = Event::find()->where(['type' => Event::TYPE_CTA_CLICK])->count();
            $uniqueVisits = Event::find()->select('visit_id')->distinct()->count();

            $latestEvent = Event::find()->orderBy(['ts' => SORT_DESC])->one();
            $oldestEvent = Event::find()->orderBy(['ts' => SORT_ASC])->one();

            return [
                'success' => true,
                'data' => [
                    'total_events' => $totalEvents,
                    'page_views' => $pageViews,
                    'cta_clicks' => $ctaClicks,
                    'unique_visits' => $uniqueVisits,
                    'latest_event' => $latestEvent ? $latestEvent->ts : null,
                    'oldest_event' => $oldestEvent ? $oldestEvent->ts : null,
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Quick data generation for chart testing
     * @return array
     */
    public function actionQuickChartData()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            // Clear existing data
            Event::deleteAll();
            
            // Generate 50 events over 7 days
            $generated = $this->generateBulkEvents(50, 7, 'mixed');

            return [
                'success' => true,
                'message' => "Generated {$generated} events for chart testing",
                'data' => [
                    'count' => $generated,
                    'days' => 7,
                    'type' => 'mixed'
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate bulk events
     * @param int $count
     * @param int $days
     * @param string $eventType
     * @return int
     */
    protected function generateBulkEvents($count, $days, $eventType)
    {
        // Sample data
        $pages = [
            'https://example.com/home',
            'https://example.com/products',
            'https://example.com/about',
            'https://example.com/contact',
            'https://example.com/blog',
            'https://example.com/pricing',
            'https://example.com/features',
            'https://example.com/support',
            'https://example.com/login',
            'https://example.com/register',
        ];

        $ctas = [
            'buy-now-button',
            'signup-form',
            'download-pdf',
            'contact-us',
            'learn-more',
            'get-started',
            'try-free',
            'subscribe-newsletter',
            'request-demo',
            'view-pricing',
        ];

        $devices = ['desktop', 'mobile', 'tablet'];
        $referrers = [
            'https://google.com',
            'https://facebook.com',
            'https://twitter.com',
            'https://linkedin.com',
            'https://youtube.com',
            null, // Direct traffic
        ];

        $utmSources = ['google', 'facebook', 'twitter', 'linkedin', 'direct', 'email'];
        $utmMediums = ['cpc', 'organic', 'social', 'email', 'referral'];
        $utmCampaigns = ['summer-sale', 'new-product', 'newsletter', 'retargeting', 'brand-awareness'];

        // Generate visit IDs
        $visitIds = [];
        for ($i = 0; $i < 20; $i++) {
            $visitIds[] = $this->generateUuid();
        }

        $generated = 0;
        $startDate = strtotime("-{$days} days");
        $endDate = time();

        for ($i = 0; $i < $count; $i++) {
            $event = new Event();
            
            // Random time within the date range
            $randomTime = mt_rand($startDate, $endDate);
            $event->ts = date('Y-m-d H:i:s', $randomTime);
            
            // Determine event type
            if ($eventType === 'mixed') {
                $event->type = mt_rand(0, 1) ? Event::TYPE_PAGE_VIEW : Event::TYPE_CTA_CLICK;
            } elseif ($eventType === 'page_views') {
                $event->type = Event::TYPE_PAGE_VIEW;
            } else {
                $event->type = Event::TYPE_CTA_CLICK;
            }
            
            $event->page = $pages[array_rand($pages)];
            
            if ($event->type === Event::TYPE_CTA_CLICK) {
                $event->cta_id = $ctas[array_rand($ctas)];
            }
            
            $event->visit_id = $visitIds[array_rand($visitIds)];
            $event->device = $devices[array_rand($devices)];
            $event->referrer = $referrers[array_rand($referrers)];
            
            // Generate hashes
            $event->ip_hash = hash('sha256', '192.168.1.' . mt_rand(1, 255) . Yii::$app->params['eventSalt']);
            $event->ua_hash = hash('sha256', 'Mozilla/5.0 test user agent' . mt_rand(1, 1000) . Yii::$app->params['eventSalt']);
            
            // Add meta data
            $meta = [
                'screen_resolution' => mt_rand(0, 1) ? '1920x1080' : '1366x768',
                'timezone' => 'America/New_York',
                'utm_source' => $utmSources[array_rand($utmSources)],
                'utm_medium' => $utmMediums[array_rand($utmMediums)],
                'utm_campaign' => $utmCampaigns[array_rand($utmCampaigns)],
            ];
            
            $event->setMetaArray($meta);
            
            if ($event->save()) {
                $generated++;
            }
        }

        return $generated;
    }

    /**
     * Generate UUID
     * @return string
     */
    protected function generateUuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
