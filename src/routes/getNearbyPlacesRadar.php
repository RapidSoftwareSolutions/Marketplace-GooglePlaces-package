<?php

$app->post('/getNearbyPlacesRadar', function ($request, $response, $args) {
    $settings =  $this->settings;
    
    $data = $request->getParsedBody();
    $post_data = [];
    $post_data['api_key'] = filter_var($data['api_key'], FILTER_SANITIZE_STRING);
    $post_data['latitude'] = filter_var($data['latitude'], FILTER_SANITIZE_STRING);
    $post_data['longitude'] = filter_var($data['longitude'], FILTER_SANITIZE_STRING);
    $post_data['radius'] = filter_var($data['radius'], FILTER_SANITIZE_STRING);
    $post_data['keyword'] = filter_var($data['keyword'], FILTER_SANITIZE_STRING);
    $post_data['name'] = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $post_data['minimum_price'] = filter_var($data['minimum_price'], FILTER_SANITIZE_STRING);
    $post_data['maximum_price'] = filter_var($data['maximum_price'], FILTER_SANITIZE_STRING);
    $post_data['open_now'] = filter_var($data['open_now'], FILTER_SANITIZE_STRING);
    $post_data['type'] = filter_var($data['type'], FILTER_SANITIZE_STRING);
    
    $error = [];
    if(empty($post_data['api_key'])) {
        $error[] = 'api_key cannot be empty';
    }
    if(empty($post_data['latitude'])) {
        $error[] = 'latitude cannot be empty';
    }
    if(empty($post_data['longitude'])) {
        $error[] = 'longitude cannot be empty';
    }
    if(empty($post_data['radius'])) {
        $error[] = 'radius cannot be empty';
    }
    
    if(!empty($error)) {
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = $error;
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
    }
    
    
    
    $query['key'] = $post_data['api_key'];
    $query['location'] = $post_data['latitude'] . ',' . $post_data['longitude'];
    $query['radius'] = $post_data['radius'];
    if(!empty($post_data['keyword'])) {
        $query['keyword'] = $post_data['keyword'];
    }
    if(!empty($post_data['name'])) {
        $query['name'] = $post_data['name'];
    }
    if(!empty($post_data['minimum_price'])) {
        $query['minprice'] = $post_data['minimum_price'];
    }
    if(!empty($post_data['maximum_price'])) {
        $query['maxprice'] = $post_data['maximum_price'];
    }
    if(!empty($post_data['open_now'])) {
        $query['opennow'] = $post_data['open_now'];
    }
    if(!empty($post_data['type'])) {
        $query['types'] = $post_data['type'];
    }
    
    
    $query_str = $settings['api_url'] . 'radarsearch/json';
    
    $client = $this->httpClient;

    try {

        $resp = $client->get( $query_str, 
            [
                'query' => $query,
                'verify' => false
            ]);
        $responseBody = $resp->getBody();
        if(!empty(json_decode($responseBody)->results) && json_decode($responseBody)->status == 'OK') {
            $result['callback'] = 'success';
            $result['contextWrites']['to'] = json_decode($responseBody);
        } else {
            $result['callback'] = 'error';
            $result['contextWrites']['to'] = json_decode($responseBody);
        }

    } catch (\GuzzleHttp\Exception\ClientException $exception) {

        $responseBody = $exception->getResponse()->getBody(true);
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = json_decode($responseBody);

    }
    
    

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});
