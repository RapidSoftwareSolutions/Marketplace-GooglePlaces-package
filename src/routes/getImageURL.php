<?php

$app->post('/api/GooglePlaces/getImageURL', function ($request, $response, $args) {
    $settings =  $this->settings;

    
    $data = $request->getBody();
    $post_data = json_decode($data, true);
    if(!isset($post_data['args'])) {
        $data = $request->getParsedBody();
        $post_data = $data;
    }
    
    $error = [];
    if(empty($post_data['args']['api_key'])) {
        $error[] = 'api_key cannot be empty';
    }
    if(empty($post_data['args']['image_id'])) {
        $error[] = 'image_id cannot be empty';
    }
    if(empty($post_data['args']['max_width']) && empty($post_data['args']['max_height'])) {
        $error[] = 'max_width or max_height cannot be empty';
    }
    
    if(!empty($error)) {
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = implode(',', $error);
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
    }
    
    
    
    $query['key'] = $post_data['args']['api_key'];
    $query['photoreference'] = $post_data['args']['image_id'];
    if(!empty($post_data['args']['max_width'])) {
        $query['maxwidth'] = $post_data['args']['max_width'];
    }
    if(!empty($post_data['args']['max_height'])) {
        $query['maxheight'] = $post_data['args']['max_height'];
    }
    
    
    $query_str = $settings['api_url'] . 'photo?'. http_build_query($query);
    
    $client = $this->httpClient;

    try {

        $resp = $client->get( $query_str, 
            [
                'verify' => false,
                'allow_redirects' => false
            ]);        
        $responseBody = $resp->getBody()->getContents();
        preg_match_all('#<.*?href=["\'](.*?)["\'].*?>#uims', $responseBody, $matches);
                
        if(!empty($matches[1][0])) {
            $result['callback'] = 'success';
            $result['contextWrites']['to'] = $matches[1][0];
        } else {
            $result['callback'] = 'error';
            $result['contextWrites']['to'] =$responseBody;
        }

    } catch (\GuzzleHttp\Exception\ClientException $exception) {

        $responseBody = $exception->getResponse()->getBody(true);
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = json_decode($responseBody);

    }
    
    

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});