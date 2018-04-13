<?php
/**
 * 并发调用api方法设置
 * @author:wb
 */
namespace usercenter\service;

use usercenter\libs\Sign;
use usercenter\libs\Log;

class ApiService
{
    //相关api服务的method列表
    //远程方法名和请求方式定义
    public static $method_list  = [];
    public static $config_list  = [];
    public static $serviceProxy = null;
    public static $_instances   = [];

    public function __construct()
    {
        //导入路由方法
        self::$method_list  = require_once dirname(__DIR__) . '/libs/Route.php';
        //导入配置文件
        self::$config_list  = require dirname(__DIR__) . '/libs/Config.php';
        //设置服务proxy代理
        self::$serviceProxy = HttpService::getInstance();
    }

    //通过单例模式调用服务
    public static function getInstance()
    {
        $class = get_called_class();
        if (!isset(self::$_instances[$class])) {
            self::$_instances[$class] = new $class();
        }

        return self::$_instances[$class];
    }

    public function __call($method, $args)
    {
        return isset(self::$method_list[$method]) ? self::callService($method, $args) : self::errorMethod($method);
    }

    public static function __callStatic($method, $args)
    {
        return isset(self::$method_list[$method]) ? self::callService($method, $args) : self::errorMethod($method);
    }

    public static function errorMethod($method)
    {
        return ['code' => 500, 'message' => '请求的' . $method . '方法不存在！', 'data' => null];
    }

    public static function getMethodType($method = '')
    {
        $method = array_key_exists($method, self::$method_list) ? self::$method_list[$method] : 'get';
        return in_array($method, ['post', 'get']) ? ucfirst($method) : 'Get';
    }

    /**
     * [callService 调用api基础服务]
     * @param  [type]  $method                                 [method_list列表中的方法]
     * @param  [type]  $params                                 [请求参数]
     * @return [array] [返回信息包含code,message,data]
     */
    public function callService($method, $params)
    {
        if (empty($method)) {
            return self::errorMethod();
        }

        if (!self::$config_list['url']) {
            return ['code' => 500, 'message' => '未定义请求api地址！', 'data' => null];
        }

        $url = rtrim(self::$config_list['url'], '/') . '/' . $method;

        $len = count($params); //方法接收的参数个数
        if ($len <= 0) {
            return ['code' => 500, 'message' => '缺少请求参数！', 'data' => null];
        }

        $params = $params[0];

        //应用的appId,appSecret必选参数
        if (empty(self::$config_list['appid']) || empty(self::$config_list['appsecret'])) {
            return ['code' => 500, 'message' => '缺少请求参数,appId和appSecret不能为空！'];
        }

        //curl初始化设置
        $method_type    = self::getMethodType($method); //$len = 1 //默认get方式请求

        //对请求参数进行签名
        $form_params = Sign::makeSign($params, self::$config_list['appid'], self::$config_list['appsecret']);

        Log::debug(['url' => $url, 'method' => $method_type, 'params' => $form_params]);

        //请求api服务
        $res = (self::$serviceProxy)->request($url, $method_type, $form_params);

        if (empty($res)) {
            return ['code' => 500, 'message' => '获取信息失败', 'data' => null];
        }

        if (isset($res['code']) && $res['code'] != 0) {
            return ['code' => $res['code'], 'message' => $res['message'] ? $res['message'] : '获取信息失败'];
        }

        return $res;
    }

    //判断路由方法是否存在
    public function __isset($method = '')
    {
        if (empty($method)) {
            return false;
        }

        return isset(self::$method_list[$method]);
    }

}
