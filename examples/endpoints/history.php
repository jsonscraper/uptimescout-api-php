<?php
declare(strict_types=1);

$options = getopt('', ['service:', 'hours:', 'limit:', 'offset:', 'region:', 'lang:', 'help']);
if (isset($options['help'])) {
    echo 'Read observation history\nUsage: php examples/endpoints/history.php --region=GB --lang=en\nOptions: --service=, --hours=, --limit=, --offset=, --region=, --lang=' . PHP_EOL;
    exit(0);
}
$key = getenv('UPTIMESCOUT_LICENSE_KEY') ?: '';
if ($key === '') {
    fwrite(STDERR, "Set UPTIMESCOUT_LICENSE_KEY in your environment.\n");
    exit(1);
}
require dirname(__DIR__, 2) . '/vendor/autoload.php';
$parameters = ['service' => 'github', 'hours' => 24, 'limit' => 50, 'offset' => 0, 'region' => 'GB', 'lang' => 'en'];
foreach ($parameters as $name => $default) {
    if (isset($options[$name])) $parameters[$name] = $options[$name];
}
try {
    $client = new UptimeScout\Client($key, getenv('UPTIMESCOUT_BASE_URL') ?: 'https://api.uptimescout.com');
    $response = $client->request('history', $parameters);
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) . PHP_EOL;
} catch (Throwable $error) {
    fwrite(STDERR, "UptimeScout request failed; check your key, plan, parameters and network.\n");
    exit(1);
}
