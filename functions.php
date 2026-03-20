<?php
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


function getWhoisData($domain) {
    $domain = escapeshellarg($domain);
    $output = shell_exec("whois $domain");
    return $output ?: 'WHOIS lookup failed.';
}


function getDnsRecords($domain) {
    $records = dns_get_record($domain, DNS_ANY);
    return $records ?: [];
}

/*
function getExistingSubdomains($domain) {
    $common = ['www', 'mail', 'ftp', 'webmail', 'smtp', 'pop', 'imap', 'ns1', 'ns2', 'test', 'dev', 'api', 'blog', 'shop', 'admin', 'portal', 'vpn', 'm', 'mobile', 'beta', 'staging'];
    $found = [];
    foreach ($common as $sub) {
        $subdomain = "$sub.$domain";
        $records = dns_get_record($subdomain, DNS_ANY);
        if ($records && count($records) > 0) {
            $found[$subdomain] = $records;
        }
    }
    return $found;
}
*/

/**
 * Fetches subdomains from crt.sh certificate logs.
 * Returns an array of unique subdomains found.
 */
function getExistingSubdomains($domain) {
    // API endpoint for crt.sh (output in JSON format)
    $url = "https://crt.sh/?q=" . urlencode($domain) . "&output=json";
    
    $json = file_get_contents($url);
    if (!$json) return [];

    $data = json_decode($json, true);
    $subdomains = [];

    foreach ($data as $entry) {
        // Names can contain wildcards (*.) or multiple lines
        $names = explode("\n", $entry['name_value']);
        foreach ($names as $name) {
            $name = strtolower(trim($name));
            if (str_contains($name, $domain) && !str_contains($name, '*')) {
                $subdomains[] = $name;
            }
        }
    }

    return array_unique($subdomains);
}
