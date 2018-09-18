<?php

namespace think;

class Http
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

    public function __construct($options = [])
    {
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }

        $this->url = $this->options['api_url'];

        $this->headers['Authorization'] = $this->options['api_token'];
    }

    /**
     * 合并默认参数和额外参数
     * @param array $params 默认参数
     * @param array /string $param 额外参数
     * @return array:
     */
    protected function param($params, $param)
    {
        if (is_string($param))
            parse_str($param, $param);
        return array_merge($params, $param);
    }

    public function api($type, $url, $params = [])
    {
        $url = "{$this->url}$url";

        if (null == $this->curl) {
            $this->curl = curl_init();
        }

        $params = http_build_query($params);

        switch ($type = strtoupper($type)) {
            case 'DELETE':
                curl_setopt($this->curl, CURLOPT_URL, $url);
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $type);
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($params));
                break;
            case 'PUT':
                curl_setopt($this->curl, CURLOPT_URL, $url);
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $type);
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($params));
                break;
            case 'POST':
                curl_setopt($this->curl, CURLOPT_URL, $url);
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $type);
                curl_setopt($this->curl, CURLOPT_POST, true);
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
                break;
            case 'GET':
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, null);
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $type);
                curl_setopt($this->curl, CURLOPT_HTTPGET, true);
                $url .= '?' . $params;
                curl_setopt($this->curl, CURLOPT_URL, $url);
                break;
        }

        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

        $headers = [];
        foreach ($this->headers as $k => $v) {
            $headers[] = "$k: $v";
        }

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 10);

        $out = curl_exec($this->curl);
        if (false === $out) {
            exception(curl_error($this->curl), 0);
        }
        $this->last_status = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        if ($this->last_status >= 200 && $this->last_status < 300) {
            return $out;
        }
        return $out;
    }
    
}