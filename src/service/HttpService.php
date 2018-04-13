<?php
/**
 * http请求服务
 * @author:wb
 */
namespace usercenter\service;

use usercenter\libs\Log;

class HttpService
{
    protected static $_instances = [];
    //通过单例模式调用服务
    public static function getInstance()
    {
        $class = get_called_class();
        if (!isset(self::$_instances[$class])) {
            self::$_instances[$class] = new $class();
        }

        return self::$_instances[$class];
    }

    //请求
    public function request($url, $method, $params = [], $headers = '')
    {
        $type = strtoupper($method);

        if ('GET' == $type || 'DELETE' == $type) {
            $url = $url . '?' . http_build_query($params);
        }

        $curl = curl_init();
        $timeout = 10;
        curl_setopt($curl, CURLOPT_URL, $url); //地址
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);

        if ($headers != '') {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        switch ($type) {
            case 'GET':
                curl_setopt($curl, CURLOPT_HTTPGET, 1);
                break;
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            default://POST
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        }

        if(substr($url, 0, 5) == 'https') {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
        }

        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $file_contents = curl_exec($curl);//获得返回值

        if (curl_errno($curl)) {
            Log::debug(curl_error($curl), 'requestError');
            curl_close($curl);
            return false;
        }

        curl_close($curl);
        Log::debug($file_contents, '接口返回');

        return $file_contents;
    }
}