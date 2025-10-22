<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * This is the model class for table "event".
 *
 * @property int $id
 * @property string $type
 * @property string $page
 * @property string|null $cta_id
 * @property string|null $referrer
 * @property string|null $device
 * @property string|null $country_iso2
 * @property string|null $region
 * @property string|null $city
 * @property string|null $visit_id
 * @property string $ip_hash
 * @property string $ua_hash
 * @property string|null $meta
 * @property string $ts
 * @property string $created_at
 */
class Event extends ActiveRecord
{
    const TYPE_PAGE_VIEW = 'page_view';
    const TYPE_CTA_CLICK = 'cta_click';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%event}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            // No behaviors needed - using database defaults for timestamps
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'page', 'ip_hash', 'ua_hash', 'ts'], 'required'],
            [['type'], 'in', 'range' => [self::TYPE_PAGE_VIEW, self::TYPE_CTA_CLICK]],
            [['page'], 'string', 'max' => 255],
            [['cta_id'], 'string', 'max' => 100],
            [['referrer'], 'string', 'max' => 255],
            [['device'], 'string', 'max' => 50],
            [['country_iso2'], 'string', 'max' => 2],
            [['region'], 'string', 'max' => 80],
            [['city'], 'string', 'max' => 120],
            [['visit_id'], 'string', 'max' => 36],
            [['ip_hash', 'ua_hash'], 'string', 'max' => 64],
            [['meta'], 'string'],
            [['ts'], 'string'],
            [['ts'], 'validateTimestamp'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'page' => 'Page',
            'cta_id' => 'Cta ID',
            'referrer' => 'Referrer',
            'device' => 'Device',
            'country_iso2' => 'Country ISO2',
            'region' => 'Region',
            'city' => 'City',
            'visit_id' => 'Visit ID',
            'ip_hash' => 'IP Hash',
            'ua_hash' => 'User Agent Hash',
            'meta' => 'Meta',
            'ts' => 'Timestamp',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Get meta data as array
     * @return array|null
     */
    public function getMetaArray()
    {
        if (empty($this->meta)) {
            return null;
        }
        
        try {
            return Json::decode($this->meta);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Set meta data from array
     * @param array|null $meta
     */
    public function setMetaArray($meta)
    {
        if ($meta === null) {
            $this->meta = null;
        } else {
            $this->meta = Json::encode($meta);
        }
    }

    /**
     * Validate timestamp format
     * @param string $attribute
     * @param array $params
     */
    public function validateTimestamp($attribute, $params)
    {
        if (!empty($this->$attribute)) {
            $timestamp = \DateTime::createFromFormat('Y-m-d H:i:s', $this->$attribute);
            if (!$timestamp || $timestamp->format('Y-m-d H:i:s') !== $this->$attribute) {
                $this->addError($attribute, 'The format of Timestamp is invalid. Expected format: Y-m-d H:i:s');
            }
        }
    }

    /**
     * Get available event types
     * @return array
     */
    public static function getEventTypes()
    {
        return [
            self::TYPE_PAGE_VIEW => 'Page View',
            self::TYPE_CTA_CLICK => 'CTA Click',
        ];
    }

    /**
     * Create a new event with automatic data extraction
     * @param string $url
     * @param string $type
     * @param array $additionalData
     * @return static
     */
    public static function createFromUrl($url, $type = self::TYPE_PAGE_VIEW, $additionalData = [])
    {
        $event = new static();
        $event->type = $type;
        $event->page = $url;
        $event->ts = gmdate('Y-m-d H:i:s'); // UTC timestamp
        
        // Extract server-side data
        $event->extractServerData($additionalData);
        
        return $event;
    }

    /**
     * Extract server-side data from request and environment
     * @param array $additionalData
     */
    protected function extractServerData($additionalData = [])
    {
        $request = Yii::$app->request;
        
        // Hash IP address with salt
        $ip = $request->getUserIP();
        $this->ip_hash = hash('sha256', $ip . $this->getSalt());
        
        // Hash User Agent with salt
        $userAgent = $request->getUserAgent();
        $this->ua_hash = hash('sha256', $userAgent . $this->getSalt());
        
        // Extract referrer
        $this->referrer = $request->getReferrer();
        
        // Detect device type
        $this->device = $this->detectDevice($userAgent);
        
        // Extract geolocation (you might want to use a service like MaxMind GeoIP)
        $this->extractGeolocation($ip);
        
        // Generate visit ID if not provided
        if (empty($additionalData['visit_id'])) {
            $this->visit_id = $this->generateVisitId();
        } else {
            $this->visit_id = $additionalData['visit_id'];
        }
        
        // Set additional meta data
        $meta = array_merge([
            'screen_resolution' => $request->get('screen_resolution'),
            'language' => $request->getPreferredLanguage(),
            'timezone' => $request->get('timezone'),
        ], $additionalData);
        
        // Remove null values
        $meta = array_filter($meta, function($value) {
            return $value !== null && $value !== '';
        });
        
        if (!empty($meta)) {
            $this->setMetaArray($meta);
        }
    }

    /**
     * Get salt for hashing (you should implement a rotating salt mechanism)
     * @return string
     */
    protected function getSalt()
    {
        // In production, implement a rotating salt mechanism
        return Yii::$app->params['eventSalt'] ?? 'default-salt-change-in-production';
    }

    /**
     * Detect device type from user agent
     * @param string $userAgent
     * @return string
     */
    protected function detectDevice($userAgent)
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
     * Extract geolocation from IP (basic implementation)
     * @param string $ip
     */
    protected function extractGeolocation($ip)
    {
        // This is a basic implementation. In production, use a proper GeoIP service
        // like MaxMind GeoIP2 or similar
        
        // Skip private IPs
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return;
        }
        
        // You can implement actual GeoIP lookup here
        // For now, we'll leave these fields null
        $this->country_iso2 = null;
        $this->region = null;
        $this->city = null;
    }

    /**
     * Generate a unique visit ID
     * @return string
     */
    protected function generateVisitId()
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
