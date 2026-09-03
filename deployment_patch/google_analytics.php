<?php
/**
 * Google Analytics 4 configuration.
 */

function get_ga4_credentials_path(): string
{
    // Priority A: Environment variable
    if ($envPath = getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
        return $envPath;
    }
    
    // Priority B: Secure server-side file outside public_html (production strategy)
    // Assume project root is public_html (or htdocs), so we step one level up
    $projectRoot = dirname(__DIR__, 2);
    $securePath = dirname($projectRoot) . '/green-pyramids-credentials.json';
    if (is_file($securePath) && is_readable($securePath)) {
        return $securePath;
    }
    
    // Priority C: Local Windows development fallback
    return 'D:/programs/secure_credentials/green-pyramids-dd294-868e64d80653.json';
}

define('GA4_PROPERTY_ID', '552062847');
define('GOOGLE_APPLICATION_CREDENTIALS_PATH', get_ga4_credentials_path());

function ga4_credentials_available(): bool
{
    return is_file(GOOGLE_APPLICATION_CREDENTIALS_PATH)
        && is_readable(GOOGLE_APPLICATION_CREDENTIALS_PATH);
}

