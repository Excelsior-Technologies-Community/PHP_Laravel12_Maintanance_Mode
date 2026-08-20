<?php
$cookieValue = 'eyJpdiI6IjB4b1BsMzFabUhCdTh6cnZCQVU0Y2c9PSIsInZhbHVlIjoiczA3RjJEK2syUEFnZDhwNXByOTJwTUtFWnpPb25SMUJ6L3o0aHUyRk4wUjFiQ2YwOWg5cTNFMGpDdkRsVThOdzFveXJHMGRpRnY5TVIrVytpbnArRkI3MHJvNXR4WG8wWUtDdG8zbFJwWGdSQ25rTFZYa1FXaE5jOHNPV2JXV0JadEE5bkc5cmNpL2orQjR0Q0Y4MWpzZ25RSEtPdUUyVWc0RldjUUJ1RWdQalhWY0U3d2FqdzdGK1h0T2FIak1OY3pVZDNXeExoNVhuQW8yZVRRMHhmdWczSGxOVFVHRjJGUnViYXJzUHhQOD0iLCJtYWMiOiI5YTRlMGNlMWVkMzA2ZDc3ZWM1Yjg3NGM3MDAyM2NiZjU1NzU3NDhiZWIwOTU1MTlmNzkxYzNjZWIyYTMwMGFkIiwidGFnIjoiIn0%3D';

// URL decode first (browser does this automatically)
$cookieValue = urldecode($cookieValue);
echo "URL decoded: $cookieValue\n";

$decoded = base64_decode($cookieValue);
echo "Base64 decoded: $decoded\n";
echo "JSON valid: " . (json_validate($decoded) ? 'yes' : 'no') . "\n";
$payload = json_decode($decoded, true);
echo "Payload: "; print_r($payload);