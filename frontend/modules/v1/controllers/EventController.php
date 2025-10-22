<?php

namespace frontend\modules\v1\controllers;

use common\models\Event;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * Event Controller
 * 
 * Handles event tracking API endpoints
 */
class EventController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // Allow only POST for event creation
        $behaviors['verbFilter'] = [
            'class' => \yii\filters\VerbFilter::class,
            'actions' => [
                'track' => ['POST'],
            ],
        ];
        
        return $behaviors;
    }

    /**
     * Track a new event
     * 
     * POST /v1/event/track
     * 
     * Expected payload:
     * {
     *   "url": "https://example.com/page"
     * }
     * 
     * Optional additional fields:
     * {
     *   "url": "https://example.com/page",
     *   "type": "page_view", // or "cta_click"
     *   "cta_id": "button-123",
     *   "visit_id": "uuid-here",
     *   "screen_resolution": "1920x1080",
     *   "timezone": "America/New_York",
     *   "utm_source": "google",
     *   "utm_medium": "cpc",
     *   "utm_campaign": "summer-sale"
     * }
     * 
     * @return array
     * Success: {"visit_id": "uuid"}
     * Error: {"error": "message"}
     * @throws BadRequestHttpException
     */
    public function actionTrack()
    {
        $request = Yii::$app->request;
        
        // Get JSON payload
        $data = $request->getBodyParams();
        
        // Validate required fields
        if (empty($data['url'])) {
            throw new BadRequestHttpException('URL required');
        }
        
        // Validate URL format
        if (!filter_var($data['url'], FILTER_VALIDATE_URL)) {
            throw new BadRequestHttpException('Invalid URL');
        }
        
        try {
            // Extract event type (default to page_view)
            $type = $data['type'] ?? Event::TYPE_PAGE_VIEW;
            
            // Validate event type
            if (!in_array($type, [Event::TYPE_PAGE_VIEW, Event::TYPE_CTA_CLICK])) {
                throw new BadRequestHttpException('Invalid type');
            }
            
            // Prepare additional data
            $additionalData = [];
            
            // Extract CTA ID if provided
            if (!empty($data['cta_id'])) {
                $additionalData['cta_id'] = $data['cta_id'];
            }
            
            // Extract visit ID if provided
            if (!empty($data['visit_id'])) {
                $additionalData['visit_id'] = $data['visit_id'];
            }
            
            // Extract UTM parameters and other tracking data
            $allowedMetaFields = [
                'screen_resolution',
                'timezone',
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_term',
                'utm_content',
                'ab_variant',
                'lang',
                'referrer_override'
            ];
            
            foreach ($allowedMetaFields as $field) {
                if (isset($data[$field]) && !empty($data[$field])) {
                    $additionalData[$field] = $data[$field];
                }
            }
            
            // Create event
            $event = Event::createFromUrl($data['url'], $type, $additionalData);
            
            // Override CTA ID if provided separately
            if (!empty($data['cta_id'])) {
                $event->cta_id = $data['cta_id'];
            }
            
            // Override referrer if provided
            if (!empty($data['referrer_override'])) {
                $event->referrer = $data['referrer_override'];
            }
            
            // Save event
            if ($event->save()) {
                Yii::$app->response->statusCode = 201;
                return [
                    'visit_id' => $event->visit_id
                ];
            } else {
                Yii::$app->response->statusCode = 422;
                return [
                    'error' => 'Save failed'
                ];
            }
            
        } catch (\Exception $e) {
            Yii::error('Event tracking error: ' . $e->getMessage(), __METHOD__);
            
            Yii::$app->response->statusCode = 500;
            return [
                'error' => 'Server error'
            ];
        }
    }

    /**
     * Get event statistics (optional endpoint for analytics)
     * 
     * GET /v1/event/stats
     * 
     * Query parameters:
     * - from: Start date (Y-m-d format)
     * - to: End date (Y-m-d format)
     * - type: Event type filter
     * - page: Page filter
     * 
     * @return array
     */
    public function actionStats()
    {
        $request = Yii::$app->request;
        
        $from = $request->get('from');
        $to = $request->get('to');
        $type = $request->get('type');
        $page = $request->get('page');
        
        $query = Event::find();
        
        // Apply filters
        if ($from) {
            $query->andWhere(['>=', 'ts', $from . ' 00:00:00']);
        }
        
        if ($to) {
            $query->andWhere(['<=', 'ts', $to . ' 23:59:59']);
        }
        
        if ($type) {
            $query->andWhere(['type' => $type]);
        }
        
        if ($page) {
            $query->andWhere(['like', 'page', $page]);
        }
        
        // Get basic statistics
        $totalEvents = $query->count();
        $uniqueVisits = $query->select('visit_id')->distinct()->count();
        
        // Get events by type
        $eventsByType = $query->select(['type', 'COUNT(*) as count'])
            ->groupBy('type')
            ->asArray()
            ->all();
        
        // Get top pages
        $topPages = $query->select(['page', 'COUNT(*) as count'])
            ->groupBy('page')
            ->orderBy(['count' => SORT_DESC])
            ->limit(10)
            ->asArray()
            ->all();
        
        return [
            'success' => true,
            'data' => [
                'total_events' => $totalEvents,
                'unique_visits' => $uniqueVisits,
                'events_by_type' => $eventsByType,
                'top_pages' => $topPages,
                'period' => [
                    'from' => $from,
                    'to' => $to,
                ]
            ]
        ];
    }
}
