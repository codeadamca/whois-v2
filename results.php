<?php
require_once __DIR__ . '/functions.php';
loadEnv(__DIR__ . '/.env');

$domain = $_POST['domain'] ?? '';
$result = '';
if ($domain) {
    $result = getWhoisData($domain);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WHOIS Results</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
    <div class="w3-container w3-margin-top">
        <div class="w3-card w3-padding" style="max-width:800px;margin:auto;">
            <h2 class="w3-center">WHOIS Results</h2>
            <?php if ($domain): ?>
                <h4>Domain: <?php echo htmlspecialchars($domain); ?></h4>
                <pre class="w3-code w3-light-grey"><?php echo htmlspecialchars($result); ?></pre>
            <?php else: ?>
                <p class="w3-center">No domain submitted.</p>
            <?php endif; ?>
            <div class="w3-center w3-margin-top">
                <a class="w3-button w3-blue" href="index.php">Back</a>
            </div>
        </div>
    </div>
</body>
</html>
