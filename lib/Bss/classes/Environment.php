<?php

class BssEnvironment
{
    private $prefix = "/environment";
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function post($fields)
    {
        $result = $this->client->post($this->prefix, $fields);
        return $result;
    }

    public function __destruct()
    {
        unset($this->prefix);
    }
}
