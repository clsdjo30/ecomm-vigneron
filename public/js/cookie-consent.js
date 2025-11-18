/**
 * Cookie Consent Manager - RGPD Compliant
 * Gestion du consentement aux cookies conforme RGPD
 */

const cookieConsent = {
    // Cookie name for storing consent
    cookieName: 'cookie_consent',

    // Cookie expiration (1 year)
    cookieExpiry: 365,

    // Default preferences
    defaultPreferences: {
        essential: true,
        analytics: false,
        marketing: false,
        preferences: false,
        timestamp: null
    },

    /**
     * Initialize the cookie consent manager
     */
    init: function() {
        const consent = this.getConsent();

        if (!consent || !consent.timestamp) {
            // No consent given yet, show banner
            this.showBanner();
        } else {
            // Consent already given, load cookies based on preferences
            this.loadCookies(consent);
            // Show the settings button
            this.showSettingsButton();
        }

        // Initialize modal event listeners
        this.initModalListeners();
    },

    /**
     * Show the cookie banner
     */
    showBanner: function() {
        const banner = document.getElementById('cookieConsent');
        if (banner) {
            banner.classList.remove('d-none');
            banner.classList.add('show');
        }
    },

    /**
     * Hide the cookie banner
     */
    hideBanner: function() {
        const banner = document.getElementById('cookieConsent');
        if (banner) {
            banner.classList.remove('show');
            setTimeout(() => {
                banner.classList.add('d-none');
            }, 300);
        }
        this.showSettingsButton();
    },

    /**
     * Show the settings button
     */
    showSettingsButton: function() {
        const btn = document.getElementById('cookieSettingsBtn');
        if (btn) {
            setTimeout(() => {
                btn.classList.add('show');
            }, 500);
        }
    },

    /**
     * Accept all cookies
     */
    acceptAll: function() {
        const preferences = {
            essential: true,
            analytics: true,
            marketing: true,
            preferences: true,
            timestamp: new Date().toISOString()
        };

        this.saveConsent(preferences);
        this.loadCookies(preferences);
        this.hideBanner();
        this.showSuccessMessage('Tous les cookies ont été acceptés');
    },

    /**
     * Reject all non-essential cookies
     */
    rejectAll: function() {
        const preferences = {
            essential: true,
            analytics: false,
            marketing: false,
            preferences: false,
            timestamp: new Date().toISOString()
        };

        this.saveConsent(preferences);
        this.loadCookies(preferences);
        this.hideBanner();
        this.showSuccessMessage('Seuls les cookies essentiels ont été acceptés');
    },

    /**
     * Open customization modal
     */
    customize: function() {
        this.hideBanner();

        // Load current preferences
        const consent = this.getConsent() || this.defaultPreferences;

        // Set checkbox states
        document.getElementById('cookieAnalytics').checked = consent.analytics || false;
        document.getElementById('cookieMarketing').checked = consent.marketing || false;
        document.getElementById('cookiePreferences').checked = consent.preferences || false;

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('cookiePreferencesModal'));
        modal.show();
    },

    /**
     * Save custom preferences
     */
    savePreferences: function() {
        const preferences = {
            essential: true, // Always true
            analytics: document.getElementById('cookieAnalytics').checked,
            marketing: document.getElementById('cookieMarketing').checked,
            preferences: document.getElementById('cookiePreferences').checked,
            timestamp: new Date().toISOString()
        };

        this.saveConsent(preferences);
        this.loadCookies(preferences);

        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('cookiePreferencesModal'));
        modal.hide();

        this.showSuccessMessage('Vos préférences ont été enregistrées');
    },

    /**
     * Save consent to cookie
     */
    saveConsent: function(preferences) {
        const consent = JSON.stringify(preferences);
        this.setCookie(this.cookieName, consent, this.cookieExpiry);
    },

    /**
     * Get consent from cookie
     */
    getConsent: function() {
        const consent = this.getCookie(this.cookieName);
        return consent ? JSON.parse(consent) : null;
    },

    /**
     * Load cookies based on user preferences
     */
    loadCookies: function(preferences) {
        // Essential cookies are always loaded
        this.loadEssentialCookies();

        // Load analytics if accepted
        if (preferences.analytics) {
            this.loadAnalyticsCookies();
        } else {
            this.removeAnalyticsCookies();
        }

        // Load marketing if accepted
        if (preferences.marketing) {
            this.loadMarketingCookies();
        } else {
            this.removeMarketingCookies();
        }

        // Load preferences if accepted
        if (preferences.preferences) {
            this.loadPreferencesCookies();
        }
    },

    /**
     * Load essential cookies (always active)
     */
    loadEssentialCookies: function() {
        // Session cookies, CSRF tokens, etc.
        console.log('Essential cookies loaded');
    },

    /**
     * Load analytics cookies (Google Analytics, etc.)
     */
    loadAnalyticsCookies: function() {
        // Example: Load Google Analytics
        // You can add your Google Analytics code here
        console.log('Analytics cookies loaded');

        // Example for Google Analytics 4
        /*
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'GA_MEASUREMENT_ID');
        */
    },

    /**
     * Remove analytics cookies
     */
    removeAnalyticsCookies: function() {
        // Remove Google Analytics cookies
        this.deleteCookie('_ga');
        this.deleteCookie('_gid');
        this.deleteCookie('_gat');
        console.log('Analytics cookies removed');
    },

    /**
     * Load marketing cookies
     */
    loadMarketingCookies: function() {
        // Example: Load Facebook Pixel, Google Ads, etc.
        console.log('Marketing cookies loaded');
    },

    /**
     * Remove marketing cookies
     */
    removeMarketingCookies: function() {
        // Remove marketing cookies
        this.deleteCookie('_fbp');
        console.log('Marketing cookies removed');
    },

    /**
     * Load preferences cookies
     */
    loadPreferencesCookies: function() {
        // Load user preferences cookies
        console.log('Preferences cookies loaded');
    },

    /**
     * Set a cookie
     */
    setCookie: function(name, value, days) {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/; SameSite=Lax";
    },

    /**
     * Get a cookie value
     */
    getCookie: function(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    },

    /**
     * Delete a cookie
     */
    deleteCookie: function(name) {
        document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    },

    /**
     * Initialize modal event listeners
     */
    initModalListeners: function() {
        const modal = document.getElementById('cookiePreferencesModal');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', () => {
                // If no consent yet, show banner again
                const consent = this.getConsent();
                if (!consent || !consent.timestamp) {
                    this.showBanner();
                }
            });
        }
    },

    /**
     * Show success message
     */
    showSuccessMessage: function(message) {
        // Create a temporary toast notification
        const toast = document.createElement('div');
        toast.className = 'cookie-toast';
        toast.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            ${message}
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('show');
        }, 100);

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    cookieConsent.init();
});
