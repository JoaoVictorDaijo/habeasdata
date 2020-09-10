<?php

require __DIR__. '/system/app.php';

// This path should point to Composer's autoloader
require_once __DIR__ . '/vendor/autoload.php';

$app = new App();

$app->start();

?>
