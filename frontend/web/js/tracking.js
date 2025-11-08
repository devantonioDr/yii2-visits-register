/**
 * Analytics Tracking Module
 * 
 * Handles page view tracking and CTA click tracking
 * Configuration is loaded from PHP config
 */

// ========================================
// Analytics Tracker Class
// ========================================
class AnalyticsTracker {
    constructor(config = {}) {
        this.config = {
            enabled: config.enabled ?? true,
            apiEndpoint: config.apiEndpoint ?? '',
            debugMode: config.debugMode ?? false,
            trackPageViews: config.trackPageViews ?? true,
            trackCTAClicks: config.trackCTAClicks ?? true,
        };
        
        // CTA button IDs to track (passed from config or empty array)
        this.ctaButtons = config.ctaButtons ?? [];
        
        this.initialized = false;
    }
    
    // ========================================
    // Initialize tracking
    // ========================================
    init() {
        if (this.initialized) {
            this.log('⚠️ Analytics already initialized');
            return;
        }
        
        if (!this.config.enabled) {
            this.log('⚠️ Analytics tracking is disabled');
            return;
        }
        
        if (!this.config.apiEndpoint) {
            console.error('❌ Analytics API endpoint not configured');
            return;
        }
        
        // Track page view
        if (this.config.trackPageViews) {
            this.trackPageView();
        }
        
        // Setup CTA tracking
        if (this.config.trackCTAClicks) {
            this.setupCTATracking();
        }
        
        this.initialized = true;
        this.log('✅ Analytics tracker initialized successfully');
    }
    
    // ========================================
    // Track page view with UTM parameters
    // ========================================
    trackPageView() {
        if (!this.config.enabled) return;
        
        // Get or create visit_id
        let visitId = localStorage.getItem('visit_id');
        
        // Extract UTM parameters from URL
        const urlParams = new URLSearchParams(window.location.search);
        
        const eventData = {
            url: window.location.href,
            type: 'page_view',
            screen_resolution: `${window.screen.width}x${window.screen.height}`,
            viewport_size: `${window.innerWidth}x${window.innerHeight}`,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            lang: navigator.language || navigator.userLanguage,
            user_agent: navigator.userAgent,
            timestamp: new Date().toISOString(),
        };
        
        // Add visit_id if exists
        if (visitId) {
            eventData.visit_id = visitId;
        }
        
        // Add UTM parameters if they exist
        const utmParams = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
        utmParams.forEach(param => {
            if (urlParams.has(param)) {
                eventData[param] = urlParams.get(param);
            }
        });
        
        // Add referrer if exists
        if (document.referrer) {
            eventData.referrer_override = document.referrer;
        }
        
        // Send event
        this.sendEvent(eventData)
            .then(data => {
                if (data && data.visit_id) {
                    localStorage.setItem('visit_id', data.visit_id);
                    this.log('📊 Page tracked with visit_id:', data.visit_id);
                }
            })
            .catch(error => {
                console.error('❌ Error tracking page view:', error);
            });
    }
    
    // ========================================
    // Track CTA click
    // ========================================
    trackCTAClick(buttonId, buttonText = '', additionalData = {}) {
        if (!this.config.enabled) return;
        
        const visitId = localStorage.getItem('visit_id');
        
        const eventData = {
            url: window.location.href,
            type: 'cta_click',
            cta_id: buttonId,
            visit_id: visitId,
            // timestamp: new Date().toISOString(),
            ...additionalData
        };
        
        // Add button text if available
        if (buttonText) {
            eventData.cta_text = buttonText;
        }
        
        // Send event
        this.sendEvent(eventData)
            .then(data => {
                this.log('🎯 CTA click tracked:', buttonId, data);
            })
            .catch(error => {
                console.error('❌ Error tracking CTA click:', error);
            });
    }
    
    // ========================================
    // Setup CTA tracking listeners
    // ========================================
    setupCTATracking() {
        let trackedCount = 0;
        
        this.ctaButtons.forEach(buttonId => {
            const button = document.getElementById(buttonId);
            if (button) {
                button.addEventListener('click', (e) => {
                    const buttonText = e.currentTarget.textContent.trim();
                    const buttonHref = e.currentTarget.getAttribute('href');
                    
                    this.trackCTAClick(buttonId, buttonText, {
                        button_href: buttonHref
                    });
                    
                    this.log('🔘 CTA click detected:', buttonId);
                });
                trackedCount++;
            } else {
                console.warn(`⚠️ CTA button not found: ${buttonId}`);
            }
        });
        
        this.log(`✅ CTA tracking setup complete: ${trackedCount}/${this.ctaButtons.length} buttons`);
    }
    
    // ========================================
    // Send event to API
    // ========================================
    async sendEvent(eventData) {
        if (!this.config.apiEndpoint) {
            throw new Error('API endpoint not configured');
        }
        
        try {
            const response = await fetch(this.config.apiEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(eventData)
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            return data;
        } catch (error) {
            // Provide helpful error messages for common issues
            if (error.message.includes('Failed to fetch') || error.name === 'TypeError') {
                console.error('❌ CORS Error: Your backend needs to allow cross-origin requests.');
                console.error('   The server must respond to OPTIONS requests with proper CORS headers.');
                console.error('   Required headers:');
                console.error('   - Access-Control-Allow-Origin: *');
                console.error('   - Access-Control-Allow-Methods: POST, OPTIONS');
                console.error('   - Access-Control-Allow-Headers: Content-Type');
                console.error('   Endpoint:', this.config.apiEndpoint);
            }
            // Re-throw to be handled by caller
            throw error;
        }
    }
    
    // ========================================
    // Add custom CTA button to track
    // ========================================
    addCTAButton(buttonId) {
        if (!this.ctaButtons.includes(buttonId)) {
            this.ctaButtons.push(buttonId);
            
            // If already initialized, setup tracking for this button
            if (this.initialized) {
                const button = document.getElementById(buttonId);
                if (button) {
                    button.addEventListener('click', (e) => {
                        const buttonText = e.currentTarget.textContent.trim();
                        const buttonHref = e.currentTarget.getAttribute('href');
                        
                        this.trackCTAClick(buttonId, buttonText, {
                            button_href: buttonHref
                        });
                    });
                    this.log(`✅ Added tracking for button: ${buttonId}`);
                }
            }
        }
    }
    
    // ========================================
    // Get current visit ID
    // ========================================
    getVisitId() {
        return localStorage.getItem('visit_id');
    }
    
    // ========================================
    // Clear visit ID (useful for testing)
    // ========================================
    clearVisitId() {
        localStorage.removeItem('visit_id');
        this.log('🗑️ Visit ID cleared');
    }
    
    // ========================================
    // Update configuration
    // ========================================
    updateConfig(newConfig) {
        this.config = { ...this.config, ...newConfig };
        this.log('⚙️ Configuration updated:', this.config);
    }
    
    // ========================================
    // Enable/disable tracking
    // ========================================
    enable() {
        this.config.enabled = true;
        this.log('✅ Tracking enabled');
    }
    
    disable() {
        this.config.enabled = false;
        this.log('⚠️ Tracking disabled');
    }
    
    // ========================================
    // Debug logging
    // ========================================
    log(...args) {
        if (this.config.debugMode) {
            console.log('[Analytics]', ...args);
        }
    }
    
    // ========================================
    // Get configuration (read-only)
    // ========================================
    getConfig() {
        return { ...this.config };
    }
}

// ========================================
// Create global tracker instance
// ========================================
window.AnalyticsTracker = AnalyticsTracker;

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AnalyticsTracker;
}

