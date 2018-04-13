<?php
/**
 * call api远程调用方法
 * @author:wb
 */
namespace usercenter\service;

class RpcService
{
    protected $proxy;
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

    public function __construct()
    {
        $this->proxy = ApiService::getInstance();
    }

    /**
     * [call api远程调用方法]
     * @param  [type]  $method             [调用的路由方法，参考method_list]
     * @param  array   $params             [请求参数]
     * @return [array] [code,message,data] code !=0时，返回code,message
     */
    public function call($method, $params = [])
    {
        if (empty($method) || !isset($this->proxy->$method)) {
            return ['code' => 500, 'message' => '请求的' . $method . '方法不存在！', 'data' => null];
        }

        return $this->proxy->$method($params);
    }

    public function test() {
        return $this->proxy->test();
    }
}
