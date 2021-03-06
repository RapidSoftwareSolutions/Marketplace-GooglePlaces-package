<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Register models
require __DIR__ . '/../src/Models/paginationClass.php';
require __DIR__ . '/../src/Models/normalizeJson.php';

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes/getNearbyPlaces.php';
require __DIR__ . '/../src/routes/searchPlacesByText.php';
require __DIR__ . '/../src/routes/getNearbyPlacesRadar.php';
require __DIR__ . '/../src/routes/getNearbyPlacesByName.php';
require __DIR__ . '/../src/routes/getNearbyPlacesByType.php';
require __DIR__ . '/../src/routes/getPlaceDetails.php';
require __DIR__ . '/../src/routes/getImageURL.php';
require __DIR__ . '/../src/routes/addPlace.php';
require __DIR__ . '/../src/routes/metadata.php';

// Run app
$app->run();
