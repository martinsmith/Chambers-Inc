<?php
/**
 * Router for the Chambers Inc static site
 * Handles clean URLs and serves HTML pages and static assets
 */

$request_uri = $_SERVER['REQUEST_URI'];
$request_path = parse_url($request_uri, PHP_URL_PATH);
$request_path = trim($request_path, '/');

// Homepage
if (empty($request_path)) {
    serveFile(__DIR__ . '/index.html');
    exit;
}

// Static assets (css/, js/, assets/)
if (preg_match('/\.(css|js|jpg|jpeg|png|gif|svg|woff|woff2|ttf|eot|ico|pdf|zip)$/i', $request_path)) {
    $asset_path = __DIR__ . '/' . $request_path;
    if (file_exists($asset_path) && is_file($asset_path)) {
        serveStaticAsset($asset_path);
        exit;
    }
}

// Try to find the appropriate HTML page
$possible_paths = [
    __DIR__ . '/' . $request_path . '/index.html',
    __DIR__ . '/' . $request_path . '.html',
    __DIR__ . '/' . $request_path,
];

foreach ($possible_paths as $file_path) {
    if (file_exists($file_path) && is_file($file_path)) {
        serveFile($file_path);
        exit;
    }
}

// 404 - Page not found
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
        a { color: #c27948; }
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
 * Serve an HTML file
 */
function serveFile($file_path) {
    header('Content-Type: text/html; charset=UTF-8');
    readfile($file_path);
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
