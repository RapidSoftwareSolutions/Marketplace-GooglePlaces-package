<?php

$app->post('/api/GooglePlaces/addPlace', function ($request, $response, $args) {
    $settings =  $this->settings;
    
    $data = $request->getBody();
    $post_data = json_decode($data, true);
    if(!isset($post_data['args'])) {
        $data = $request->getParsedBody();
        $post_data = $data;
    }
    
    $error = [];
    if(empty($post_data['args']['apiKey'])) {
        $error[] = 'apiKey cannot be empty';
    }
    if(empty($post_data['args']['accuracy'])) {
        $error[] = 'accuracy cannot be empty';
    }
    if(empty($post_data['args']['language'])) {
        $error[] = 'language cannot be empty';
    }
    if(empty($post_data['args']['latitude'])) {
        $error[] = 'latitude cannot be empty';
    }
    if(empty($post_data['args']['longitude'])) {
        $error[] = 'longitude cannot be empty';
    }
    if(empty($post_data['args']['name'])) {
        $error[] = 'name cannot be empty';
    }
    if(empty($post_data['args']['types'])) {
        $error[] = 'types cannot be empty';
    }
    
    if(!empty($error)) {
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = implode(',', $error);
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
    }
    
    
    
    $query['key'] = $post_data['args']['apiKey'];
    
    $params['location']['lat'] = (float) $post_data['args']['latitude'];
    $params['location']['lng'] = (float) $post_data['args']['longitude'];
    $params['accuracy'] = (int) $post_data['args']['accuracy'];
    $params['name'] = $post_data['args']['name'];
    if(!empty($post_data['args']['address'])) {
        $params['address'] = $post_data['args']['address'];
    }
    $params['language'] = $post_data['args']['language'];
    if(!empty($post_data['args']['phoneNumber'])) {
        $params['phone_number'] = $post_data['args']['phoneNumber'];
    }
    $params['types'][] = $post_data['args']['types'];
    
    if(!empty($post_data['args']['website'])) {
        $params['website'] = $post_data['args']['website'];
    }
    //echo json_encode($params); die;
    
    $query_str = $settings['api_url'] . 'add/json?key='.$query['key'];
    
    $headers['Content-Type'] = 'application/json';
    
    $client = $this->httpClient;

    try {

        $resp = $client->post( $query_str, 
            [
                'headers' => $headers,
                'body' => json_encode($params)
            ]);
        $responseBody = $resp->getBody()->getContents();
        if(!empty(json_decode($responseBody)) && json_decode($responseBody)->status == 'OK') {
            $result['callback'] = 'success';
            $result['contextWrites']['to'] = $responseBody;
        } else {
            $result['callback'] = 'error';
            $result['contextWrites']['to'] = $responseBody;
        }

    } catch (\GuzzleHttp\Exception\ClientException $exception) {

        $responseBody = $exception->getResponse()->getBody(true);
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = json_decode($responseBody);

    }
    
    

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});
