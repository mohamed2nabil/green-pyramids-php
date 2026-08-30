<?php
/**
 * Google Analytics 4 configuration. The service-account key is intentionally
 * stored outside the public web root.
 */
define('GA4_PROPERTY_ID', '552062847');
define('GOOGLE_APPLICATION_CREDENTIALS_PATH', 'D:/programs/secure_credentials/green-pyramids-dd294-868e64d80653.json');

function ga4_credentials_available(): bool
{
    return is_file(GOOGLE_APPLICATION_CREDENTIALS_PATH)
        && is_readable(GOOGLE_APPLICATION_CREDENTIALS_PATH);
}
