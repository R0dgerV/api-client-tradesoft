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

    /**
     * @var int
     */
    protected $versionApi = 3;

    /**
     * @var int
     */
    protected $timeLimit = 10;

    /**
     * @var string
     */
    protected $service = 'provider';

    /**
     * @var bool
     */
    protected $returnArray = false;

    /**
     * @param string $username
     * @param string $password
     * @param string $baseUrl
     */
    public function __construct($username, $password, $baseUrl = 'https://service.tradesoft.ru/')
    {
        $this->username = $username;
        $this->password = $password;
        $this->baseUrl = $baseUrl;
    }

    /**
     * Запрос возвращает список подключенных к учетной записи поставщиков.
     * @return object|array
     */
    public function getProviderList()
    {
        $data = array_merge($this->getBaseData(),
            [
                'action' => 'GetProviderList',
            ]
        );

        return $this->getQuery($data);
    }

    /**
     * Запрос возвращает список опций доступных поставщику.
     * @param array $container
     * @return object|array
     */
    public function getOptionsList(array $container)
    {
        $data = array_merge($this->getBaseData(),
            [
                'action' => 'GetOptionsList',
                'container' => $container
            ]
        );

        return $this->getQuery($data);
    }

    /**
     * @param string $name
     * @param string $login
     * @param string $password
     * @return array
     */
    protected function generateBaseProviderContent($name, $login, $password)
    {
        return [
            'provider' => $name,
            'login' => $login,
            'password' => $password
        ];
    }

    /**
     * @param string $name
     * @param string $login
     * @param string $password
     * @return array
     */
    public function generateProviderContent($name, $login, $password)
    {
        return [$this->generateBaseProviderContent($name, $login, $password)];
    }

    /**
     * @param string $name
     * @param string $login
     * @param string $password
     * @param string $code
     * @return array
     */
    public function generateProviderContentForProducerList($name, $login, $password, $code)
    {
        return [array_merge($this->generateBaseProviderContent($name, $login, $password),
            [
                'code' => $code,
            ])];
    }

    /**
     * @param string $name
     * @param string $login
     * @param string $password
     * @param string $code
     * @param string $producer
     * @return array
     */
    public function generateProviderContentForPriceList($name, $login, $password, $code, $producer)
    {
        return [array_merge($this->generateBaseProviderContent($name, $login, $password),
            [
                'code' => $code,
                'producer' => $producer
            ])];
    }

    /**
     * Запрос возвращает список производителей по коду.
     * @return object|array
     */
    public function getProducerList(array $container)
    {
        $data = array_merge($this->getBaseData(),
            [
                'action' => 'getProducerList',
                'timeLimit' => $this->timeLimit,
                'container' => $container
            ]
        );
        return $this->getQuery($data);
    }

    /**
     * @param bool $var
     * @return $this
     */
    public function setReturnArray($var)
    {
        $this->returnArray = boolval($var);

        return $this;
    }

    /**
     * @return Client
     */
    protected function getClient()
    {
        if (!$this->client) {
            $this->client = new Client(['base_uri' => $this->baseUrl]);
        }
        return $this->client;
    }


    /**
     * @param array $data
     * @return object|array
     */
    protected function getQuery(array $data)
    {
        $response = $this->getClient()->request('POST', $this->getUrlQuery(), [
            'headers' => [
                'User-Agent' => 'rodger-api-client-tradesoft/1.0',
                'Accept'     => 'application/json',
            ],
            'body' => \GuzzleHttp\json_encode($data)
        ]);
        return \GuzzleHttp\json_decode($response->getBody(), $this->returnArray);
    }

    /**
     * @return string
     */
    protected function getUrlQuery()
    {
        return $this->versionApi . '/';
    }

    /**
     * @return array
     */
    protected function getBaseData()
    {
        return [
            'service' => $this->service,
            'user' => $this->username,
            'password' => $this->password
        ];
    }

}