<?php

class BssLoyalty
{
    private $prefix = "/loyalty";
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function get()
    {
        $result = $this->client->get($this->prefix);
        return $result;
    }

    public function __destruct()
    {
        unset($this->prefix);
    }
}
