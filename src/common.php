<?php
use think\paginator\driver\Bootstrap;
use sanyc\ybservice\Http;

if (!function_exists('http')) {
    /**
     * [http description]
     * @param  [type] $type    [description]
     * @param  [type] $url     [description]
     * @param  array  $params  [description]
     * @param  array  $options [description]
     * @return [type]          [description]
     */
    function http($type, $url, $params = [], $options = [])
    {
        try {
            $http = new Http($options);
            return $http->api($type, $url, $params);
        } catch (\Exception $e) {
            //throw $e;
        }
        
    }
}

if (!function_exists('bootstrap')) {
    
    function bootstrap($results, $page = 1, $total = 0, $listRows = 50, $config)
    {
        $class    = '\think\paginator\driver\Bootstrap';

        $config['path'] = isset($config['path']) ? $config['path'] : call_user_func([
            $class, 
            'getCurrentPath'
        ]);

        $page  = isset($config['page']) ? (int) $config['page'] : call_user_func([
            $class,
            'getCurrentPage',
        ], 50);

        return Bootstrap::make($results, $listRows, $page, $total, false, $config);
    }
}

if (!function_exists('yb_get_list_page')) {
    
    function yb_get_list_page($api_name, $query = [])
    {
        return \sanyc\ybservice\YbService::getClientInstance()->getArray($api_name, $query);
    }
}

if (!function_exists('yb_get_list_json')) {
    
    function yb_get_list_json($api_name, $query = [])
    {
        return \sanyc\ybservice\YbService::getClientInstance()->getJson($api_name, $query);
    }
}

if (!function_exists('yb_get_list_array')) {
    
    function yb_get_list_array($api_name, $query = [])
    {
        return \sanyc\ybservice\YbService::getClientInstance()->getJson($api_name, $query, true);
    }
}

if (!function_exists('dd')) {
    
    function dd($value = 'TEST')
    {
        halt($value);
    }
}
