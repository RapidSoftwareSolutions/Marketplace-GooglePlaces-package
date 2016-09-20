<?php

$app->post('/searchPlacesByText', function ($request, $response, $args) {
    $settings =  $this->settings;
    
    $data = $request->getParsedBody();
    $post_data = [];
    $post_data['api_key'] = filter_var($data['api_key'], FILTER_SANITIZE_STRING);
    $post_data['query'] = filter_var($data['query'], FILTER_SANITIZE_STRING);
    $post_data['latitude'] = filter_var($data['latitude'], FILTER_SANITIZE_STRING);
    $post_data['longitude'] = filter_var($data['longitude'], FILTER_SANITIZE_STRING);
    $post_data['radius'] = filter_var($data['radius'], FILTER_SANITIZE_STRING);
    $post_data['language'] = filter_var($data['language'], FILTER_SANITIZE_STRING);
    $post_data['minimum_price'] = filter_var($data['minimum_price'], FILTER_SANITIZE_STRING);
    $post_data['maximum_price'] = filter_var($data['maximum_price'], FILTER_SANITIZE_STRING);
    $post_data['open_now'] = filter_var($data['open_now'], FILTER_SANITIZE_STRING);
    $post_data['type'] = filter_var($data['type'], FILTER_SANITIZE_STRING);
    
    $error = [];
    if(empty($post_data['api_key'])) {
        $error[] = 'api_key cannot be empty';
    }
    if(empty($post_data['query'])) {
        $error[] = 'query cannot be empty';
    }
    
    if(!empty($error)) {
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = $error;
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
    }
    
    
    
    $query['key'] = $post_data['api_key'];
    $query['query'] = $post_data['query'];
    if(!empty($post_data['latitude']) && !empty($post_data['longitude'])) {
        $query['location'] = $post_data['latitude'] . ',' . $post_data['longitude'];
    }
    if(!empty($post_data['radius'])) {
        $query['radius'] = $post_data['radius'];
    }
    if(!empty($post_data['language'])) {
        $query['language'] = $post_data['language'];
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
    
    
    $query_str = $settings['api_url'] . 'textsearch/json';
    
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
