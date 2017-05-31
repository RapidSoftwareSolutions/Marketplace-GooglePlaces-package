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
        $error[] = 'apiKey';
    }
    if(empty($post_data['args']['accuracy'])) {
        $error[] = 'accuracy';
    }
    if(empty($post_data['args']['language'])) {
        $error[] = 'language';
    }

    if(empty($post_data['args']['name'])) {
        $error[] = 'name';
    }
    if(empty($post_data['args']['types'])) {
        $error[] = 'types';
    }
    
    if(!empty($error)) {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = "REQUIRED_FIELDS";
        $result['contextWrites']['to']['status_msg'] = "Please, check and fill in required fields.";
        $result['contextWrites']['to']['fields'] = $error;
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
    }
    
    
    
    $query['key'] = $post_data['args']['apiKey'];
    if (isset($post_data['args']['coordinate'])) {
        $params['location']['lat'] = explode(',',$post_data['args']['coordinate'])[0];
        $params['location']['lng'] = explode(',',$post_data['args']['coordinate'])[1];
    } else {
        $params['location']['lat'] = (float) $post_data['args']['latitude'];
        $params['location']['lng'] = (float) $post_data['args']['longitude'];
    }

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
            $result['contextWrites']['to'] = is_array($responseBody) ? $responseBody : json_decode($responseBody);
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
