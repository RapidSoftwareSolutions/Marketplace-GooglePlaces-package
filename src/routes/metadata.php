<?php

$app->post('/metadata', function ($request, $response, $args) {
    $result = file_get_contents(__DIR__ . '/../../src/metadata/metadata.json');
    return $result;
});
