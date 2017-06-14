<?php

namespace ProjectPHP;
use GuzzleHttp\Client;


class callAPI
{
    public $client;

    public function __construct()
    {
        $config = include 'conf.local.php';
        $this->client = new Client([
            'base_uri' => $config['URL'],
            'headers' => ['apikey'=>$config['APIKEY']]
        ]); 
    }

    public function call(string $endPoint){
        $reponse=$this->client->request('POST',$endPoint);
        return $reponse->getBody()->getContents();
    }
}