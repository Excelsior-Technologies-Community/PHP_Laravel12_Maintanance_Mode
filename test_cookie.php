<?php
// Test the maintenance.php cookie validation logic with current cookie
$cookieValue = 'eyJpdiI6IjB4b1BsMzFabUhCdTh6cnZCQVU0Y2c9PSIsInZhbHVlIjoiczA3RjJEK2syUEFnZDhwNXByOTJwTUtFWnpPb25SMUJ6L3o0aHUyRk4wUjFiQ2YwOWg5cTNFMGpDdkRsVThOdzFveXJHMGRpRnY5TVIrVytpbnArRkI3MHJvNXR4WG8wWUtDdG8zbFJwWGdSQ25rTFZYa1FXaE5jOHNPV2JXV0JadEE5bkc5cmNpL2orQjR0Q0Y4MWpzZ25RSEtPdUUyVWc0RldjUUJ1RWdQalhWY0U3d2FqdzdGK1h0T2FIak1OY3pVZDNXeExoNVhuQW8yZVRRMHhmdWczSGxOVFVHRjJGUnViYXJzUHhQOD0iLCJtYWMiOiI5YTRlMGNlMWVkMzA2ZDc3ZWM1Yjg3NGM3MDAyM2NiZjU1NzU3NDhiZWIwOTU1MTlmNzkxYzNjZWIyYTMwMGFkIiwidGFnIjoiIn0%3D';

$down = __DIR__ . '/storage/framework/down';
$data = json_decode(file_get_contents($down), true);

echo "Secret: " . $data['secret'] . "\n";

$payload = json_decode(base64_decode($cookieValue), true);
echo "Payload: "; print_r($payload);

if (is_array($payload) &&
    is_numeric($payload['expires_at'] ?? null) &&
    isset($payload['mac']) &&
    hash_equals(hash_hmac('sha256', $payload['expires_at'], $data['secret']), $payload['mac']) &&
    (int) $payload['expires_at'] >= time()) {
    echo "VALID COOKIE!\n";
} else {
    echo "INVALID COOKIE\n";
    echo "is_array: " . (is_array($payload) ? 'yes' : 'no') . "\n";
    echo "is_numeric expires_at: " . (is_numeric($payload['expires_at'] ?? null) ? 'yes' : 'no') . "\n";
    echo "has mac: " . (isset($payload['mac']) ? 'yes' : 'no') . "\n";
    if (isset($payload['expires_at'])) {
        echo "expires_at: " . $payload['expires_at'] . "\n";
        echo "time: " . time() . "\n";
        echo "expires_at >= time: " . ((int) $payload['expires_at'] >= time() ? 'yes' : 'no') . "\n";
    }
    if (isset($payload['mac'])) {
        echo "expected mac: " . hash_hmac('sha256', $payload['expires_at'], $data['secret']) . "\n";
        echo "actual mac: " . $payload['mac'] . "\n";
        echo "mac match: " . (hash_equals(hash_hmac('sha256', $payload['expires_at'], $data['secret']), $payload['mac']) ? 'yes' : 'no') . "\n";
    }
}