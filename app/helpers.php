<?php

/**
 * Generate a dynamic URL considering the base path (XAMPP subfolder support)
 */
function url($path = '') {
    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $basePath = ($basePath === '/' ? '' : $basePath);
    
    // If the path already starts with the basePath, don't add it again
    if ($basePath !== '' && strpos($path, $basePath) === 0) {
        return $path;
    }
    
    return $basePath . '/' . ltrim($path, '/');
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
