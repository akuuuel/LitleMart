<?php

/**
 * Generate a dynamic URL considering the base path (XAMPP subfolder support)
 */
function url($path = '') {
    $path = ltrim($path, '/');
    
    // Detect the subfolder base path (e.g., /LitleMart/public)
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $basePath = str_replace('\\', '/', dirname($scriptName));
    $basePath = ($basePath === '/' ? '' : $basePath);
    
    // Prevent duplication: if the path already starts with the base path, 
    // we strip it so we can re-add it cleanly with the host.
    $cleanBasePath = ltrim($basePath, '/');
    if (!empty($cleanBasePath) && strpos($path, $cleanBasePath) === 0) {
        $path = ltrim(substr($path, strlen($cleanBasePath)), '/');
    }
    
    // If we have a host, build a full absolute URL
    if (isset($_SERVER['HTTP_HOST'])) {
        $protocol = "http";
        if (
            (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
        ) {
            $protocol = "https";
        }
        
        return $protocol . "://" . $_SERVER['HTTP_HOST'] . $basePath . '/' . $path;
    }
    
    // Fallback to APP_URL from .env or default for terminal/CLI
    $baseUrl = $_ENV['APP_URL'] ?? 'http://localhost/LitleMart/public';
    return rtrim($baseUrl, '/') . '/' . $path;
}

/**
 * Helper to display alert messages
 */
function flash($key) {
    return \App\Core\Session::getFlash($key);
}

/**
 * Check if user has a specific role
 */
function hasRole($role) {
    $roles = \App\Core\Session::get('roles', []);
    return in_array($role, $roles);
}
