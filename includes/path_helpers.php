<?php
// Centered utility for resolving root-relative and database paths to browser-correct URLs.

if (!function_exists('get_project_relative_path')) {
    /**
     * Dynamically determines the relative prefix to go from the current script directory
     * to the project root directory.
     * 
     * @return string E.g. "../", "../../", or ""
     */
    function get_project_relative_path(): string
    {
        // dirname(__DIR__) is the project root on disk because this file is in includes/
        $projectRoot = str_replace('\\', '/', dirname(__DIR__));
        
        // Cwd of the entry point script
        if (isset($_SERVER['SCRIPT_FILENAME'])) {
            $scriptPath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME']));
            
            // Normalize case for Windows
            $projectRootLower = strtolower($projectRoot);
            $scriptPathLower = strtolower($scriptPath);
            
            if (strpos($scriptPathLower, $projectRootLower) === 0) {
                $subPath = substr($scriptPath, strlen($projectRoot));
                $subPath = ltrim($subPath, '/');
                if ($subPath === '') {
                    return '';
                }
                $depth = count(explode('/', $subPath));
                return str_repeat('../', $depth);
            }
        }
        
        // Fallback using request URI if script path is not available or outside root
        if (isset($_SERVER['SCRIPT_NAME'])) {
            if (strpos($_SERVER['SCRIPT_NAME'], '/admin/api/') !== false) {
                return '../../';
            }
            if (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false) {
                return '../';
            }
        }
        
        return '';
    }
}

if (!function_exists('asset_url')) {
    /**
     * Resolves a database or relative path to a browser-accessible URL.
     * If the file does not exist, it falls back to the default placeholder.
     * 
     * @param string|null $path The input database image path.
     * @param string $default The default placeholder relative path (from project root).
     * @return string The resolved browser-accessible URL.
     */
    function asset_url(?string $path, string $default = 'assets/images/default-product.png'): string
    {
        $cleanPath = trim((string)$path);
        
        // If it's already a full URL
        if (strpos($cleanPath, 'http://') === 0 || strpos($cleanPath, 'https://') === 0 || strpos($cleanPath, '//') === 0) {
            return $cleanPath;
        }
        
        // Normalize paths: strip leading slash, change backslashes to slashes
        $cleanPath = str_replace('\\', '/', $cleanPath);
        
        // If the path starts with '../assets/', strip the '../' so it is relative to project root
        if (strpos($cleanPath, '../') === 0) {
            $cleanPath = substr($cleanPath, 3);
        }
        $cleanPath = ltrim($cleanPath, '/');
        
        $projectRoot = str_replace('\\', '/', dirname(__DIR__));
        
        $exists = false;
        if ($cleanPath !== '') {
            $diskPath = $projectRoot . '/' . $cleanPath;
            if (file_exists($diskPath) && is_file($diskPath)) {
                $exists = true;
            }
        }
        
        if (!$exists) {
            // Fallback to default
            $defaultClean = ltrim(str_replace('\\', '/', $default), '/');
            if (strpos($defaultClean, '../') === 0) {
                $defaultClean = substr($defaultClean, 3);
            }
            
            $defaultDiskPath = $projectRoot . '/' . $defaultClean;
            if (file_exists($defaultDiskPath) && is_file($defaultDiskPath)) {
                $cleanPath = $defaultClean;
            } else {
                // Hard fallback to default product image
                $fallbackDefault = 'assets/images/default-product.png';
                $fallbackDiskPath = $projectRoot . '/' . $fallbackDefault;
                if (file_exists($fallbackDiskPath) && is_file($fallbackDiskPath)) {
                    $cleanPath = $fallbackDefault;
                } else {
                    $cleanPath = $defaultClean; // dynamic fallback even if file not found
                }
            }
        }
        
        $prefix = get_project_relative_path();
        return $prefix . $cleanPath;
    }
}
