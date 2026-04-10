<?php
/**
 * Main router for the static WordPress mirror
 * This file handles clean URLs and serves the appropriate HTML content
 */

// Get the request URI and clean it up
$request_uri = $_SERVER['REQUEST_URI'];
$request_path = parse_url($request_uri, PHP_URL_PATH);
$query_string = parse_url($request_uri, PHP_URL_QUERY);

// Remove leading slash and trailing slash
$request_path = trim($request_path, '/');

// Handle old WordPress query parameter style URLs (?p=123)
if (isset($_GET['p']) && is_numeric($_GET['p'])) {
    $post_id = $_GET['p'];
    $redirect_url = getUrlFromPostId($post_id);
    if ($redirect_url) {
        header('Location: /' . $redirect_url, true, 301);
        exit;
    }
}

// If empty, it's the homepage
if (empty($request_path)) {
    serveFile(__DIR__ . '/index.html');
    exit;
}

// Try to find the appropriate HTML file
$possible_paths = [
    __DIR__ . '/' . $request_path . '/index.html',  // e.g., /company -> /company/index.html
    __DIR__ . '/' . $request_path . '.html',         // e.g., /page -> /page.html
    __DIR__ . '/' . $request_path,                   // Direct file path
];

foreach ($possible_paths as $file_path) {
    if (file_exists($file_path) && is_file($file_path)) {
        serveFile($file_path);
        exit;
    }
}

// Check if it's a static asset (CSS, JS, images, etc.)
if (preg_match('/\.(css|js|jpg|jpeg|png|gif|svg|woff|woff2|ttf|eot|ico|pdf|zip)$/i', $request_path)) {
    $asset_path = __DIR__ . '/' . $request_path;
    if (file_exists($asset_path) && is_file($asset_path)) {
        serveStaticAsset($asset_path);
        exit;
    }
}

// Handle special WordPress URLs like feeds, wp-json, etc.
if (preg_match('#^(feed|wp-json|wp-includes|wp-content)#', $request_path)) {
    $special_path = __DIR__ . '/' . $request_path . '/index.html';
    if (file_exists($special_path)) {
        serveFile($special_path);
        exit;
    }
}

// 404 - File not found
http_response_code(404);
echo '<!DOCTYPE html>
<html>
<head>
    <title>404 - Page Not Found</title>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        h1 { color: #333; }
        p { color: #666; }
        a { color: #0073aa; }
    </style>
</head>
<body>
    <h1>404 - Page Not Found</h1>
    <p>The requested page could not be found.</p>
    <p><a href="/">Return to homepage</a></p>
</body>
</html>';
exit;

/**
 * Serve an HTML file with proper content type
 */
function serveFile($file_path) {
    $content = file_get_contents($file_path);
    
    // Fix relative paths based on the current request depth
    $request_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $depth = empty($request_path) ? 0 : substr_count($request_path, '/');
    
    // Adjust relative paths in the HTML
    if ($depth > 0) {
        $prefix = str_repeat('../', $depth);
        
        // Fix common relative paths
        $content = preg_replace(
            '/(href|src)="(?!http|\/\/|#|mailto:|tel:)([^"]+)"/',
            '$1="' . $prefix . '$2"',
            $content
        );
    }
    
    header('Content-Type: text/html; charset=UTF-8');
    echo $content;
}

/**
 * Get the clean URL from a WordPress post ID
 */
function getUrlFromPostId($post_id) {
    $mapping_file = __DIR__ . '/url-mapping.json';
    if (!file_exists($mapping_file)) {
        return null;
    }

    $mapping = json_decode(file_get_contents($mapping_file), true);
    if (isset($mapping[$post_id])) {
        return $mapping[$post_id]['slug'];
    }

    return null;
}

/**
 * Serve a static asset with the appropriate MIME type
 */
function serveStaticAsset($file_path) {
    $mime_types = [
        'css'   => 'text/css',
        'js'    => 'application/javascript',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'png'   => 'image/png',
        'gif'   => 'image/gif',
        'svg'   => 'image/svg+xml',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
        'eot'   => 'application/vnd.ms-fontobject',
        'ico'   => 'image/x-icon',
        'pdf'   => 'application/pdf',
        'zip'   => 'application/zip',
    ];
    
    $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    $mime_type = $mime_types[$extension] ?? 'application/octet-stream';
    
    header('Content-Type: ' . $mime_type);
    header('Content-Length: ' . filesize($file_path));
    readfile($file_path);
}
