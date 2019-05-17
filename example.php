<?php

/**
 * Test script, run with `$   php example.php <api-key>`. Keep your cli history
 * clean, use extra spaces or clear afterwards ;)
 **/
define('__ROOT__', dirname(__FILE__));
require_once(__ROOT__.'/vendor/autoload.php');

use EyesonTeam\Eyeson\Eyeson;

$eyeson = new Eyeson($argv[1]);
$room = $eyeson->join('tester from eyeson-php', 'testroom');

echo 'URL to eyeson GUI ' . $room->getUrl() . "\n";
