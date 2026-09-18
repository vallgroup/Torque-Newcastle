/**
 * Newcastle Cookie Consent Controller
 * Manages visitor cookie preferences, localStorage + cookie persistence (with 6-month expiration),
 * Google Consent Mode, and conditional script injection for Google Analytics, Spectra, and AudioEye.
 */
(function($) {
  const STORAGE_KEY = 'newcastle_cookie_consent';
  const CONSENT_VERSION = '1.0';
  const MAX_AGE_MS = 180 * 24 * 60 * 60 * 1000; // 6 months in milliseconds
  const GA4_MEASUREMENT_ID = 'G-VHTQ17SD50';
  const UA_MEASUREMENT_ID = 'UA-20619206-1';
  const AUDIOEYE_HASH = '49383a81a7ae4052d3616701a02e5ed3';

  /**
   * Helper to read cookie by name
   */
  function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
  }

  /**
   * Helper to write cookie
   */
  function setCookie(name, value, maxAgeMs) {
    const maxAgeSec = Math.floor(maxAgeMs / 1000);
    document.cookie = `${name}=${value}; path=/; max-age=${maxAgeSec}; SameSite=Lax`;
  }

  /**
   * Safely read consent from localStorage (fallback to document.cookie)
   */
  function getStoredConsent() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      if (raw) {
        const data = JSON.parse(raw);
        if (data && typeof data === 'object') {
          if (data.version === CONSENT_VERSION && (data.consent === 'granted' || data.consent === 'denied')) {
            const now = Date.now();
            if (data.timestamp && now - data.timestamp <= MAX_AGE_MS) {
              return data;
            }
          }
        }
        localStorage.removeItem(STORAGE_KEY);
      }

      // Fallback check against cookie
      const cookieVal = getCookie(STORAGE_KEY);
      if (cookieVal === 'granted' || cookieVal === 'denied') {
        const fallback = {
          consent: cookieVal,
          timestamp: Date.now(),
          version: CONSENT_VERSION
        };
        localStorage.setItem(STORAGE_KEY, JSON.stringify(fallback));
        return fallback;
      }

      return null;
    } catch (e) {
      console.warn('Cookie consent storage error:', e);
      return null;
    }
  }

  /**
   * Safely save consent to both localStorage and document.cookie
   */
  function saveConsent(status) {
    try {
      const data = {
        consent: status,
        timestamp: Date.now(),
        version: CONSENT_VERSION
      };
      localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
      setCookie(STORAGE_KEY, status, MAX_AGE_MS);
    } catch (e) {
      console.warn('Could not save cookie consent:', e);
      setCookie(STORAGE_KEY, status, MAX_AGE_MS);
    }
  }

  /**
   * Update Google Consent Mode state
   */
  function updateGoogleConsentMode(status) {
    if (typeof window.gtag === 'function') {
      window.gtag('consent', 'update', {
        analytics_storage: status,
        ad_storage: status,
        ad_user_data: status,
        ad_personalization: status
      });
    }
  }

  /**
   * Dynamically inject Google Analytics (gtag.js)
   */
  function loadGoogleAnalytics() {
    if (document.getElementById('newcastle-gtag-script')) return;

    window.dataLayer = window.dataLayer || [];
    if (typeof window.gtag !== 'function') {
      window.gtag = function() {
        window.dataLayer.push(arguments);
      };
    }

    const script = document.createElement('script');
    script.id = 'newcastle-gtag-script';
    script.async = true;
    script.src = `https://www.googletagmanager.com/gtag/js?id=${GA4_MEASUREMENT_ID}`;
    document.head.appendChild(script);

    window.gtag('js', new Date());
    window.gtag('config', GA4_MEASUREMENT_ID);
    window.gtag('config', UA_MEASUREMENT_ID);
  }

  /**
   * Dynamically inject Spectra tracking
   */
  function loadSpectra() {
    if (document.getElementById('newcastle-spectra-script')) return;

    (function(r, o, y, g, b, i, v) {
      r.__spectraBaseUrl = y;
      r.__spectraConfig = b;
      i = o.createElement('script');
      i.id = 'newcastle-spectra-script';
      i.src = y + g;
      i.async = 1;
      v = o.getElementsByTagName('head')[0];
      v.appendChild(i);
    })(window, document, 'https://spectrajs.com', '/stats/', {
      apiKey: 'AIzaSyBX1-5Ymt7mJm4eupDAgV0ngaw51CsYXUM',
      authDomain: 'newcastle-limited-313615.firebaseapp.com',
      projectId: 'newcastle-limited-313615',
      storageBucket: 'newcastle-limited-313615.appspot.com',
      messagingSenderId: '185146548468',
      appId: '1:185146548468:web:09efafe6116335c05dfea1',
      measurementId: GA4_MEASUREMENT_ID
    });

    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('/firebase-messaging-sw.js').catch(function(err) {
        console.warn('Spectra ServiceWorker registration failed:', err);
      });
    }
  }

  /**
   * Dynamically inject AudioEye accessibility script
   */
  function loadAudioEye() {
    if (document.getElementById('newcastle-audioeye-script')) return;

    window.__AudioEyeInstallSource = 'wordpress';
    window.__AudioEyeSiteHash = AUDIOEYE_HASH;

    const a = document.createElement('script');
    a.id = 'newcastle-audioeye-script';
    a.type = 'text/javascript';
    a.src = 'https://wsmcdn.audioeye.com/aem.js';
    a.setAttribute('async', '');
    document.body.appendChild(a);
  }

  /**
   * Activate all non-essential trackers
   */
  function activateTrackers() {
    updateGoogleConsentMode('granted');
    loadGoogleAnalytics();
    loadSpectra();
    loadAudioEye();
  }
  

  /**
   * Remove stored first-party tracking cookies when consent is denied or withdrawn
   */
  function deleteTrackingCookies() {
    try {
      const trackingPrefixes = ['_ga', '_gid', '_gat', '_gcl', '__utm', 'spectra'];
      const cookies = document.cookie.split(';');
      const hostname = window.location.hostname;
      const domainParts = hostname.split('.');
      const rootDomain = domainParts.length > 1 ? '.' + domainParts.slice(-2).join('.') : '.' + hostname;

      for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i].trim();
        const eqPos = cookie.indexOf('=');
        const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;

        const isTrackingCookie = trackingPrefixes.some(prefix => name.startsWith(prefix));

        if (isTrackingCookie) {
          // Expire cookie on current path and variations of domain
          document.cookie = `${name}=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT;`;
          document.cookie = `${name}=; path=/; domain=${hostname}; expires=Thu, 01 Jan 1970 00:00:00 GMT;`;
          document.cookie = `${name}=; path=/; domain=${rootDomain}; expires=Thu, 01 Jan 1970 00:00:00 GMT;`;
        }
      }
    } catch (e) {
      console.warn('Error clearing tracking cookies:', e);
    }
  }

  /**
   * Deactivate non-essential trackers
   */
  function deactivateTrackers() {
    updateGoogleConsentMode('denied');
    deleteTrackingCookies();
  }

  /**
   * Apply consent preferences
   */
  function applyConsent(status) {
    if (status === 'granted') {
      activateTrackers();
    } else {
      deactivateTrackers();
    }
  }

  /**
   * Initialize on DOM ready
   */
  $(document).ready(function() {
    const $banner = $('#newcastle-cookie-banner');
    const $acceptBtn = $('#cookie-banner-accept');
    const $declineBtn = $('#cookie-banner-decline');
    const $openPreferences = $('#open-cookie-preferences, [data-toggle-cookie-banner]');

    const stored = getStoredConsent();

    if (!stored) {
      // No valid consent found -> display banner
      $banner.fadeIn(300);
    } else {
      // Valid consent exists -> apply preferences
      applyConsent(stored.consent);
    }

    // Accept All
    $acceptBtn.on('click', function(e) {
      e.preventDefault();
      saveConsent('granted');
      applyConsent('granted');
      $banner.fadeOut(300);
    });

    // Decline Non-Essential
    $declineBtn.on('click', function(e) {
      e.preventDefault();
      saveConsent('denied');
      applyConsent('denied');
      $banner.fadeOut(300);
    });

    // Open Preferences (from footer link or any trigger)
    $openPreferences.on('click', function(e) {
      e.preventDefault();
      $banner.fadeIn(300);
    });
  });
})(jQuery);
