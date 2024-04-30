<?php
/**
 * Created by PhpStorm.
 * User: wedevs-macbook-2
 * Date: 20/1/19
 * Time: 9:25 PM
 */

namespace SL\Tools\Library;


use ActiveCollab\SDK\Client;
use ActiveCollab\SDK\Token;

class SL_ActiveCol
{
    private $token;
    private $client;

    public function __construct($url, $token)
    {
        $this->token = new Token($token, $url);
        $this->client = new Client($this->token);
    }

    public function getProject($id){
        return $this->client->get('/projects/'.$id)->getJson();
    }

    public function getItem($item){
        return $this->client->get($item)->getJson();
    }

}
