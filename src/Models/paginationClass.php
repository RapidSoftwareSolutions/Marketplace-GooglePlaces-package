<?php
namespace Models;

Class NextPage {
    protected $all_data = [];
    
    public function page($url, $pageToken, $query) {
        
        $client = new \GuzzleHttp\Client();
        if($pageToken) { 
            $query_new['pagetoken'] = $pageToken;
            $query_new['key'] = $query['key'];
            $resp = $client->get( $url, 
            [
                'query' => $query_new,
                'verify' => false
            ]);  

            $rawBody = json_decode($resp->getBody());
            
            $this->all_data = array_merge($this->all_data, $rawBody->results);
            
            if(isset($rawBody->next_page_token) && !empty($rawBody->next_page_token)) {
                sleep(1);
                $this->page($url, $rawBody->next_page_token, $query);
            }
        }

        return $this->all_data;
    }    
    
}

