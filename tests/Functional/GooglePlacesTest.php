<?php

namespace Tests\Functional;

class GooglePlacesTest extends BaseTestCase
{
    protected $api_key = 'AIzaSyByjKV-D8YjrjAc_8aC_6h0R7AKvSyyJis';
    
    public function testGetNearbyPlaces() {
        
        $post_data['api_key'] = $this->api_key;
        $post_data['latitude'] = '-33.8670522';
        $post_data['longitude'] = '151.19573622';
        $post_data['radius'] = 1;
        
        $response = $this->runApp('POST', '/getNearbyPlaces', $post_data);
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertEquals('success', json_decode($response->getBody())->callback);
        
    }
    
    public function testSearchPlacesByText() {
        
        $post_data['api_key'] = $this->api_key;
        $post_data['query'] = 'pizza';
        $post_data['latitude'] = '-33.8670522';
        $post_data['longitude'] = '151.19573622';
        $post_data['radius'] = 100;
        
        $response = $this->runApp('POST', '/searchPlacesByText', $post_data);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertEquals('success', json_decode($response->getBody())->callback);
        
    }
    
    public function testGetNearbyPlacesRadar() {
        
        $post_data['api_key'] = $this->api_key;
        $post_data['latitude'] = '-33.8670522';
        $post_data['longitude'] = '151.19573622';
        $post_data['radius'] = 100;
        $post_data['keyword'] = 'museum';
        
        $response = $this->runApp('POST', '/getNearbyPlacesRadar', $post_data);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertEquals('success', json_decode($response->getBody())->callback);
        
    }
    
    public function testGetPlaceDetails() {
        
        $post_data['api_key'] = $this->api_key;
        $post_data['place_id'] = 'ChIJAWLZAzSuEmsRkMcyFmh9AQU';
        
        $response = $this->runApp('POST', '/getPlaceDetails', $post_data);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertEquals('success', json_decode($response->getBody())->callback);
        
    }
    
    public function testGetImageURL() {
        
        $post_data['api_key'] = $this->api_key;
        $post_data['image_id'] = 'CoQBdwAAAMI0mesGjb8QOUwu6JppTH9-JNioNvK-ZDazyqyL4b6EOz1V5mQn08KMl0apo9BNEpFaKnVZ-_h21GMMxAi3x7y6V0o4s1oqNOhJegzhw40wsJ0M_TWtFGPiTxHN2_P6yhJVBTyTnd7XdmR3XQWV7JcT_YJ-cNCuMIGs98PKzTOvEhDspZ1-Lm-PWpqaU9qFrWKwGhT6EQXggGKGKt29gu1ArKY8zClxDA';
        $post_data['max_width'] = 100;
        $post_data['max_height'] = 100;
        
        $response = $this->runApp('POST', '/getImageURL', $post_data);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertEquals('success', json_decode($response->getBody())->callback);
        
    }
    
    public function testAddPlace() {
        
        $post_data['api_key'] = $this->api_key;
        $post_data['accuracy'] = 50;
        $post_data['language'] = 'en-Au';
        $post_data['latitude'] = '-33.8670522';
        $post_data['longitude'] = '151.19573622';
        $post_data['name'] = 'Google Shoes!';
        $post_data['types'] = 'shoe_store';
        $post_data['address'] = '48 Pirrama Road, Pyrmont, NSW 2009, Australia';
        
        $response = $this->runApp('POST', '/addPlace', $post_data);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getBody());
        $this->assertEquals('success', json_decode($response->getBody())->callback);
        
    }

}