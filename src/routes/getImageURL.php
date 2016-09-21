<?php

$app->post('/getImageURL', function ($request, $response, $args) {
    $settings =  $this->settings;
    
    $data = $request->getParsedBody();
    $post_data = [];
    $post_data['api_key'] = filter_var($data['api_key'], FILTER_SANITIZE_STRING);
    $post_data['image_id'] = filter_var($data['image_id'], FILTER_SANITIZE_STRING);
    $post_data['max_width'] = filter_var($data['max_width'], FILTER_SANITIZE_STRING);
    $post_data['max_height'] = filter_var($data['max_height'], FILTER_SANITIZE_STRING);
    
    $error = [];
    if(empty($post_data['api_key'])) {
        $error[] = 'api_key cannot be empty';
    }
    if(empty($post_data['image_id'])) {
        $error[] = 'image_id cannot be empty';
    }
    if(empty($post_data['max_width']) && empty($post_data['max_height'])) {
        $error[] = 'max_width or max_height cannot be empty';
    }
    
    if(!empty($error)) {
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = $error;
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
    }
    
    
    
    $query['key'] = $post_data['api_key'];
    $query['photoreference'] = $post_data['image_id'];
    if(!empty($post_data['max_width'])) {
        $query['maxwidth'] = $post_data['max_width'];
    }
    if(!empty($post_data['max_height'])) {
        $query['maxheight'] = $post_data['max_height'];
    }
    
    
    $query_str = $settings['api_url'] . 'photo?'. http_build_query($query);
    
    $client = $this->httpClient;

    try {

        $resp = $client->get( $query_str, 
            [
                'verify' => false,
                'allow_redirects' => false
            ]);        
        $responseBody = $resp->getBody();
        preg_match_all('#<.*?href=["\'](.*?)["\'].*?>#uims', $responseBody, $matches);
                
        if(!empty($matches[1][0])) {
            $result['callback'] = 'success';
            $result['contextWrites']['to'] = $matches[1][0];
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