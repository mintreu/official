<?php

/**
 * License & Server Info Checker (Enterprise Grade)
 * Securely returns system info and optional license data with optional encryption.
 */

// ---------------------- CONFIG ---------------------- //

$licenseFilePath = __DIR__ . '/../storage/app/license.json';
$secretKey = 'your_very_strong_secret_key_here!123456'; // 32-char AES-256-CBC key
$allowedDomains = [
    'http://localhost:8000',
    'https://yourdashboarddomain.com',
];

// ---------------------- HELPERS ---------------------- //

function encryptPayload(string $payload, string $key): string
{
    $iv = substr(hash('sha256', $key), 0, 16);
    return base64_encode(openssl_encrypt($payload, 'AES-256-CBC', $key, 0, $iv));
}

function sendResponse(array $data, int $code = 200): never
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function validateOrigin(array $allowed): bool
{
    $origin = $_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? '';
    foreach ($allowed as $allowedDomain) {
        if (str_starts_with($origin, $allowedDomain)) {
            return true;
        }
    }
    return empty($origin); // Allow CLI / direct hit with no origin
}

function getMacAddress(): string
{
    $mac = PHP_OS_FAMILY === 'Windows'
        ? exec("getmac | findstr /v 'Disconnected' | findstr ':'")
        : exec("ip link show | awk '/ether/ {print $2}'");

    return $mac ? strtoupper(trim($mac)) : 'N/A';
}

function formatBytes($bytes, $precision = 2): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
}

function getProjectSize(string $path): string
{
    $size = 0;
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $size += $file->getSize();
        }
    }
    return formatBytes($size);
}

function getDatabaseSize(): string
{
    try {
        $connection = new PDO('mysql:host=localhost;dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
        $stmt = $connection->query("SELECT SUM(data_length + index_length) AS size FROM information_schema.tables WHERE table_schema = DATABASE()");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($result['size']) ? formatBytes($result['size']) : 'N/A';
    } catch (Throwable $e) {
        return 'N/A';
    }
}

// ---------------------- VALIDATION ---------------------- //

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(['error' => 'Invalid request method. Only GET is allowed.'], 405);
}

if (!validateOrigin($allowedDomains)) {
    sendResponse(['error' => 'Unauthorized origin.'], 403);
}

// ---------------------- LICENSE ---------------------- //

$license = [
    'encrypted' => false,
    'expire_on' => null,
];

if (file_exists($licenseFilePath)) {
    $content = file_get_contents($licenseFilePath);
    $decoded = json_decode($content, true);
    if (is_array($decoded)) {
        $license = array_merge($license, $decoded);
    } else {
        $license['raw'] = $content;
    }
}

// ---------------------- SERVER INFO ---------------------- //

$projectPath = realpath(__DIR__ . '/../');
$fullUrl = ($_SERVER['HTTPS'] ?? 'off') === 'on' ? 'https://' : 'http://';
$fullUrl .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$serverInfo = [
    'hostname'        => gethostname(),
    'ip_address'      => $_SERVER['SERVER_ADDR'] ?? gethostbyname(gethostname()),
    'mac_address'     => getMacAddress(),
    'php_version'     => phpversion(),
    'server_api'      => php_sapi_name(),
    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
    'document_root'   => $_SERVER['DOCUMENT_ROOT'] ?? 'N/A',
    'project_path'    => $projectPath,
    'project_size'    => getProjectSize($projectPath),
    'db_size'         => getDatabaseSize(),
    'domain'          => $_SERVER['HTTP_HOST'] ?? 'N/A',
    'request_url'     => $fullUrl,
    'current_time'    => date('c'),
    'license'         => $license,
];

// ---------------------- RESPONSE ---------------------- //

$payloadJson = json_encode($serverInfo);

if ($license['encrypted'] === true) {
    sendResponse([
        'status'  => 'ok',
        'payload' => encryptPayload($payloadJson, $secretKey),
    ]);
} else {
    sendResponse([
        'status' => 'ok',
        'data'   => $serverInfo,
    ]);
}
