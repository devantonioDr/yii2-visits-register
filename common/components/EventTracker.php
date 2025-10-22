<?php

namespace common\components;

use common\models\Event;
use Yii;
use yii\base\Component;

/**
 * Event Tracker Component
 * 
 * Provides advanced event tracking capabilities
 */
class EventTracker extends Component
{
    /**
     * Track a page view event
     * @param string $url
     * @param array $additionalData
     * @return Event|null
     */
    public function trackPageView($url, $additionalData = [])
    {
        return $this->trackEvent($url, Event::TYPE_PAGE_VIEW, $additionalData);
    }

    /**
     * Track a CTA click event
     * @param string $url
     * @param string $ctaId
     * @param array $additionalData
     * @return Event|null
     */
    public function trackCtaClick($url, $ctaId, $additionalData = [])
    {
        $additionalData['cta_id'] = $ctaId;
        return $this->trackEvent($url, Event::TYPE_CTA_CLICK, $additionalData);
    }

    /**
     * Track a generic event
     * @param string $url
     * @param string $type
     * @param array $additionalData
     * @return Event|null
     */
    public function trackEvent($url, $type = Event::TYPE_PAGE_VIEW, $additionalData = [])
    {
        try {
            $event = Event::createFromUrl($url, $type, $additionalData);
            
            if ($event->save()) {
                Yii::info("Event tracked: {$type} for {$url}", __METHOD__);
                return $event;
            } else {
                Yii::error("Failed to save event: " . json_encode($event->getErrors()), __METHOD__);
                return null;
            }
        } catch (\Exception $e) {
            Yii::error("Event tracking error: " . $e->getMessage(), __METHOD__);
            return null;
        }
    }

    /**
     * Get visitor session ID (creates one if doesn't exist)
     * @return string
     */
    public function getSessionId()
    {
        $session = Yii::$app->session;
        
        if (!$session->has('visitor_id')) {
            $session->set('visitor_id', $this->generateUuid());
        }
        
        return $session->get('visitor_id');
    }

    /**
     * Get or create visit ID for current session
     * @return string
     */
    public function getVisitId()
    {
        $session = Yii::$app->session;
        
        if (!$session->has('visit_id')) {
            $session->set('visit_id', $this->generateUuid());
        }
        
        return $session->get('visit_id');
    }

    /**
     * Start a new visit (useful for session management)
     * @return string
     */
    public function startNewVisit()
    {
        $visitId = $this->generateUuid();
        Yii::$app->session->set('visit_id', $visitId);
        return $visitId;
    }

    /**
     * Extract UTM parameters from URL
     * @param string $url
     * @return array
     */
    public function extractUtmParameters($url)
    {
        $parsedUrl = parse_url($url);
        $utmParams = [];
        
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
            
            $utmKeys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
            
            foreach ($utmKeys as $key) {
                if (isset($queryParams[$key])) {
                    $utmParams[$key] = $queryParams[$key];
                }
            }
        }
        
        return $utmParams;
    }

    /**
     * Get device information from user agent
     * @return array
     */
    public function getDeviceInfo()
    {
        $request = Yii::$app->request;
        $userAgent = $request->getUserAgent();
        
        return [
            'user_agent' => $userAgent,
            'device_type' => $this->detectDeviceType($userAgent),
            'browser' => $this->detectBrowser($userAgent),
            'os' => $this->detectOperatingSystem($userAgent),
        ];
    }

    /**
     * Generate a UUID v4
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

    /**
     * Detect device type from user agent
     * @param string $userAgent
     * @return string
     */
    protected function detectDeviceType($userAgent)
    {
        $userAgent = strtolower($userAgent);
        
        if (preg_match('/mobile|android|iphone|ipod|blackberry|iemobile|opera mini/i', $userAgent)) {
            return 'mobile';
        } elseif (preg_match('/tablet|ipad|playbook|silk/i', $userAgent)) {
            return 'tablet';
        } else {
            return 'desktop';
        }
    }

    /**
     * Detect browser from user agent
     * @param string $userAgent
     * @return string
     */
    protected function detectBrowser($userAgent)
    {
        $userAgent = strtolower($userAgent);
        
        if (strpos($userAgent, 'chrome') !== false) {
            return 'chrome';
        } elseif (strpos($userAgent, 'firefox') !== false) {
            return 'firefox';
        } elseif (strpos($userAgent, 'safari') !== false) {
            return 'safari';
        } elseif (strpos($userAgent, 'edge') !== false) {
            return 'edge';
        } elseif (strpos($userAgent, 'opera') !== false) {
            return 'opera';
        } else {
            return 'unknown';
        }
    }

    /**
     * Detect operating system from user agent
     * @param string $userAgent
     * @return string
     */
    protected function detectOperatingSystem($userAgent)
    {
        $userAgent = strtolower($userAgent);
        
        if (strpos($userAgent, 'windows') !== false) {
            return 'windows';
        } elseif (strpos($userAgent, 'mac') !== false) {
            return 'macos';
        } elseif (strpos($userAgent, 'linux') !== false) {
            return 'linux';
        } elseif (strpos($userAgent, 'android') !== false) {
            return 'android';
        } elseif (strpos($userAgent, 'ios') !== false || strpos($userAgent, 'iphone') !== false || strpos($userAgent, 'ipad') !== false) {
            return 'ios';
        } else {
            return 'unknown';
        }
    }
}
