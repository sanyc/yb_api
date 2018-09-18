<?php

namespace think;

class YbService
{
    protected $options = [
        'api_version' => 1.0,
        'api_token'   => NULL,
        'api_url'     => 'https://open.youbailife.net/'
    ];

    protected $url;

    static protected $client = null;

    protected $curl = null;

    protected $last_status;

    protected $headers = [];

    public static function getClientInstance()
    {
        if (!self::$client instanceof self) {         
            if (!cache('?access_token')) {
                $_config = config('ybapi');
                $token   = json_decode(http('get', 'user/token', $_config), true);
                cache('access_token', $token['data']['authorization'], $token['data']['exp'] - 300);
            }
            $access_token = cache('access_token');
            self::$client = new static(['api_token' => $access_token]);
        }
        return self::$client;
    }

    protected function __construct($options = [])
    {
        $this->headers['api_token'] = $options['api_token'];
    }

    public function getJson($url, $params = [], $to_array = false)
    {
        return json_decode(http('get', $url, $params, $this->headers), $to_array);
    }

    public function getArray($url, $params = [])
    {
        $result = $this->getJson($url, $params, true);
        $result = $result['data'];
        if (empty($result)) {
            $result = [
                'page'  => 1,
                'total' => 0,
                'data'  => []
            ];
        }
        $res = bootstrap($result['data'], $result['page'], $result['total'], $params['page_size'], array_merge($params, [
            'query' => $params
        ]));
        return $res;
    }

    public function post($url, $params = [])
    {
        return json_decode(http('post', $url, $params, $this->headers));
    }
    
}