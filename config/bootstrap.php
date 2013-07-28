<?php

$loader = require dirname(__FILE__) . '/../vendor/autoload.php';

// Register the directory to your include files
$loader->add('', __DIR__ . '/../src');
$loader->add('', __DIR__ . '/../tests');
