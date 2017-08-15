<?php

$app->post('/api/GooglePlaces/getImageURL', function ($request, $response, $args) {
    $settings = $this->settings;


    $data = $request->getBody();
    $post_data = json_decode($data, true);
    if (!isset($post_data['args'])) {
        $data = $request->getParsedBody();
        $post_data = $data;
    }

    $error = [];
    if (empty($post_data['args']['apiKey'])) {
        $error[] = 'apiKey';
    }
    if (empty($post_data['args']['image_id'])) {
        $error[] = 'image_id';
    }
    if (empty($post_data['args']['max_width']) && empty($post_data['args']['max_height'])) {
        $error[] = 'indicate either max_width or max_height';
    }

    if (!empty($error)) {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = "REQUIRED_FIELDS";
        $result['contextWrites']['to']['status_msg'] = "Please, check and fill in required fields.";
        $result['contextWrites']['to']['fields'] = $error;
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
    }


    $query['key'] = $post_data['args']['apiKey'];
    $query['photoreference'] = $post_data['args']['image_id'];
    if (!empty($post_data['args']['max_width'])) {
        $query['maxwidth'] = $post_data['args']['max_width'];
    }
    if (!empty($post_data['args']['max_height'])) {
        $query['maxheight'] = $post_data['args']['max_height'];
    }


    $query_str = $settings['api_url'] . 'photo?' . http_build_query($query);

    $client = $this->httpClient;

    try {

        $resp = $client->get($query_str,
            [
                'verify' => false,
                'allow_redirects' => false
            ]);
        $responseBody = $resp->getBody()->getContents();
        preg_match_all('#<.*?href=["\'](.*?)["\'].*?>#uims', $responseBody, $matches);

        if (!empty($matches[1][0])) {
            $result['callback'] = 'success';
            $result['contextWrites']['to'] = ["imageUrl" => $matches[1][0]];
        } else {
            $result['callback'] = 'error';
            $result['contextWrites']['to']['status_code'] = 'API_ERROR';
            $result['contextWrites']['to']['status_msg'] = is_array($responseBody) ? $responseBody : json_decode($responseBody);
        }

    } catch (\GuzzleHttp\Exception\ClientException $exception) {

        $responseBody = $exception->getResponse()->getBody()->getContents();
        if (empty(json_decode($responseBody))) {
            $out = $responseBody;
        } else {
            $out = json_decode($responseBody);
        }
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = $out;

    } catch (GuzzleHttp\Exception\ServerException $exception) {

        $responseBody = $exception->getResponse()->getBody()->getContents();
        if (empty(json_decode($responseBody))) {
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