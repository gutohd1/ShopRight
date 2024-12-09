<?php
use App\Routes\App;
use Predis\Client;

session_start();

const BASE_PATH = __DIR__;

require BASE_PATH.'/vendor/autoload.php';

$redis = new Client([
    'scheme' => 'tcp',
    'host'   => 'redis',
    'port'   => 6379,
]);

new App();

