<?php
require __DIR__.'/../src/Client.php';
$client=new UptimeScout\Client(getenv('UPTIMESCOUT_LICENSE_KEY'));
print_r($client->status('github'));
