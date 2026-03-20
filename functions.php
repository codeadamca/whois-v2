<?php
// functions.php
// Store reusable PHP functions here


// Load .env variables from a file
function loadEnv($path) {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = array_map('trim', explode('=', $line, 2));
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}


// Get WHOIS data for a domain
function getWhoisData($domain) {
    $domain = escapeshellarg($domain);
    $output = shell_exec("whois $domain");
    return $output ?: 'WHOIS lookup failed.';
}

// Example function
function exampleFunction($domain) {
    // Placeholder logic
    return "You entered: " . htmlspecialchars($domain);
}

// Get DNS records for a domain
function getDnsRecords($domain) {
    $records = dns_get_record($domain, DNS_ANY);
    return $records ?: [];
}
