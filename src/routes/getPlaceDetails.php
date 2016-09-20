<?php

$app->post('/getPlaceDetails', function ($request, $response, $args) {
    $settings =  $this->settings;
    
    $data = $request->getParsedBody();
    $post_data = [];
    $post_data['api_key'] = filter_var($data['api_key'], FILTER_SANITIZE_STRING);
    $post_data['place_id'] = filter_var($data['place_id'], FILTER_SANITIZE_STRING);
    
    $error = [];
    if(empty($post_data['api_key'])) {
        $error[] = 'api_key cannot be empty';
    }
    if(empty($post_data['place_id'])) {
        $error[] = 'place_id cannot be empty';
    }
    
    if(!empty($error)) {
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = $error;
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
    }
    
    
    
    $query['key'] = $post_data['api_key'];
    $query['placeid'] = $post_data['place_id'];
    
    
    $query_str = $settings['api_url'] . 'details/json';
    
    $client = $this->httpClient;

    try {

        $resp = $client->get( $query_str, 
            [
                'query' => $query,
                'verify' => false
            ]);
        $responseBody = $resp->getBody();
        if(!empty(json_decode($responseBody)->result) && json_decode($responseBody)->status == 'OK') {
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
