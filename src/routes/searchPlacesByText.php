<?php

$app->post('/api/GooglePlaces/searchPlacesByText', function ($request, $response, $args) {
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
    if(empty($post_data['args']['query'])) {
        $error[] = 'query cannot be empty';
    }
    
    if(!empty($error)) {
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = implode(',', $error);
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
    }
    
    
    
    $query['key'] = $post_data['args']['apiKey'];
    $query['query'] = $post_data['args']['query'];
    if(!empty($post_data['args']['latitude']) && !empty($post_data['args']['longitude'])) {
        $query['location'] = $post_data['args']['latitude'] . ',' . $post_data['args']['longitude'];
    }
    if(!empty($post_data['args']['radius'])) {
        $query['radius'] = $post_data['args']['radius'];
    }
    if(!empty($post_data['args']['language'])) {
        $query['language'] = $post_data['args']['language'];
    }
    if(!empty($post_data['args']['minimum_price'])) {
        $query['minprice'] = $post_data['args']['minimum_price'];
    }
    if(!empty($post_data['args']['maximum_price'])) {
        $query['maxprice'] = $post_data['args']['maximum_price'];
    }
    if(!empty($post_data['args']['open_now'])) {
        $query['opennow'] = $post_data['args']['open_now'];
    }
    if(!empty($post_data['args']['type'])) {
        $query['types'] = $post_data['args']['type'];
    }
    
    
    $query_str = $settings['api_url'] . 'textsearch/json';
    
    $client = $this->httpClient;

    try {

        $resp = $client->get( $query_str, 
            [
                'query' => $query,
                'verify' => false
            ]);
        $responseBody = $resp->getBody()->getContents();
        $rawBody = json_decode($resp->getBody());
      
        $all_data[] = $rawBody;
        
        if(isset($rawBody->next_page_token)) {
            sleep(1);
            $pagin = $this->pager;
            $ret = $pagin->page($query_str, $rawBody->next_page_token, $query);
         
            $merge = array_merge($all_data[0]->results, $ret);
        
            $all_data[0]->results = $merge;
        } 
        if(!empty(json_decode($responseBody)->results) && json_decode($responseBody)->status == 'OK') {
            $result['callback'] = 'success';
            $result['contextWrites']['to'] = is_array($all_data) ? $all_data : json_decode($all_data);
        } else {
            $result['callback'] = 'error';
            $result['contextWrites']['to'] = is_array($responseBody) ? $responseBody : json_decode($responseBody);
        }

    } catch (\GuzzleHttp\Exception\ClientException $exception) {

        $responseBody = $exception->getResponse()->getBody(true);
        $result['callback'] = 'error';
        $result['contextWrites']['to'] = json_decode($responseBody);

    }
    
    

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});
