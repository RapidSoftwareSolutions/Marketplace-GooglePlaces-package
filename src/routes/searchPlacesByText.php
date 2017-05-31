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
        $error[] = 'apiKey';
    }
    if(empty($post_data['args']['query'])) {
        $error[] = 'query';
    }
    
    if(!empty($error)) {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = "REQUIRED_FIELDS";
        $result['contextWrites']['to']['status_msg'] = "Please, check and fill in required fields.";
        $result['contextWrites']['to']['fields'] = $error;
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
    }
    
    
    
    $query['key'] = $post_data['args']['apiKey'];
    $query['query'] = $post_data['args']['query'];
    if (isset($post_data['args']['coordinate'])) {
        $query['location'] = str_replace(' ', '',$post_data['args']['coordinate']);
    } else {
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
        $query['types'] = is_array($post_data['args']['type']) ? implode('|', $post_data['args']['type']) : $post_data['args']['type'];
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
            $result['contextWrites']['to']['status_code'] = 'API_ERROR';
            $result['contextWrites']['to']['status_msg'] = is_array($responseBody) ? $responseBody : json_decode($responseBody);
        }

    } catch (\GuzzleHttp\Exception\ClientException $exception) {

        $responseBody = $exception->getResponse()->getBody()->getContents();
        if(empty(json_decode($responseBody))) {
            $out = $responseBody;
        } else {
            $out = json_decode($responseBody);
        }
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = $out;

    } catch (GuzzleHttp\Exception\ServerException $exception) {

        $responseBody = $exception->getResponse()->getBody()->getContents();
        if(empty(json_decode($responseBody))) {
            $out = $responseBody;
        } else {
            $out = json_decode($responseBody);
        }
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = $out;

    } catch (GuzzleHttp\Exception\ConnectException $exception) {

        $responseBody = $exception->getResponse()->getBody(true);
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'INTERNAL_PACKAGE_ERROR';
        $result['contextWrites']['to']['status_msg'] = 'Something went wrong inside the package.';

    }

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});
