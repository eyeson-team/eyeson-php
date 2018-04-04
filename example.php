<?php

/**
 * Test script, run with `$   php example.php <api-key`. Keep your cli history
 * clean, use extra spaces or clear afterwards ;)
 **/
define('__ROOT__', dirname(__FILE__));
require_once(__ROOT__.'/vendor/autoload.php');

use EyesonTeam\Eyeson\Eyeson;

$eyeson = new Eyeson($argv[0]);
$room = $eyeson->join('test from eyeson-php');
// var_dump($room);
echo 'URL to eyeson GUI ' . $room->getUrl();
