<?php

namespace R0dgerV\ApiClientTradesoft;

use GuzzleHttp\Client;

/**
 * Class ApiClient
 * @package R0dgerV\ApiClientTradesoft
 */
class ApiClient
{

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var
     */
    protected $baseUrl;

    /**
     * @var Client
     */
    protected $client;

    public function __construct()
    {
         echo 'Init';
    }

}