<?php

$app->post('/addPlace', function ($request, $response, $args) {
    $settings =  $this->settings;
    
    $data = $request->getParsedBody();
    $post_data = [];
    $post_data['api_key'] = filter_var($data['api_key'], FILTER_SANITIZE_STRING);
    $post_data['accuracy'] = filter_var($data['accuracy'], FILTER_SANITIZE_STRING);
    $post_data['address'] = filter_var($data['address'], FILTER_SANITIZE_STRING);
    $post_data['language'] = filter_var($data['language'], FILTER_SANITIZE_STRING);
    $post_data['latitude'] = filter_var($data['latitude'], FILTER_SANITIZE_STRING);
    $post_data['longitude'] = filter_var($data['longitude'], FILTER_SANITIZE_STRING);
    $post_data['name'] = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $post_data['phoneNumber'] = filter_var($data['phoneNumber'], FILTER_SANITIZE_STRING);
    $post_data['types'] = filter_var($data['types'], FILTER_SANITIZE_STRING);
    $post_data['website'] = filter_var($data['website'], FILTER_SANITIZE_STRING);
    
    $error = [];
    if(empty($post_data['api_key'])) {
        $error[] = 'api_key cannot be empty';
    }
    if(empty($post_data['accuracy'])) {
        $error[] = 'accuracy cannot be empty';
    }
    if(empty($post_data['language'])) {
        $error[] = 'language cannot be empty';
    }
    if(empty($post_data['latitude'])) {
        $error[] = 'latitude cannot be empty';
    }
    if(empty($post_data['longitude'])) {
        $error[] = 'longitude cannot be empty';
    }
    if(empty($post_data['name'])) {
        $error[] = 'name cannot be empty';
    }
    if(empty($post_data['types'])) {
        $error[] = 'types cannot be empty';
    }
    
    if(!empty($error)) {
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = $error;
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
    }
    
    
    
    $query['key'] = $post_data['api_key'];
    
    $params['location']['lat'] = $post_data['latitude'];
    $params['location']['lng'] = $post_data['longitude'];
    $params['accuracy'] = $post_data['accuracy'];
    $params['name'] = $post_data['name'];
    if(!empty($post_data['address'])) {
        $params['address'] = $post_data['address'];
    }
    $params['language'] = $post_data['language'];
    if(!empty($post_data['phoneNumber'])) {
        $params['phone_number'] = $post_data['phoneNumber'];
    }
    $params['types'][] = $post_data['types'];
    if(!empty($post_data['website'])) {
        $params['website'] = $post_data['website'];
    }
    
    //print_r(json_encode($params)); die;
    $query_str = $settings['api_url'] . 'add/json';
    
    $headers['Host'] = 'maps.googleapis.com';
    
    $client = $this->httpClient;

    try {

        $resp = $client->post( $query_str, 
            [
                'query' => $query,
                'headers' => $headers,
                'body' => json_encode($params)
            ]);
        $responseBody = $resp->getBody();
        if(!empty(json_decode($responseBody)) && json_decode($responseBody)->status == 'OK') {
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
