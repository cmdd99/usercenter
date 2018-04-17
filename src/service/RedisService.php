<?php
/**
 * 获取用户中心redis缓存数据
 * @author:wb
 */
namespace usercenter\service;

use usercenter\libs\Log;

class RedisService
{
    private $redis;
    private static $_instances  = [];
    private static $config_list = [];

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
        //导入配置文件
        self::$config_list  = require_once dirname(__DIR__) . '/libs/Config.php';
        //Redis配置
        $host     = self::$config_list['redis_host'] ? : '127.0.0.1';
        $port     = self::$config_list['redis_port'] ? : 6379;
        $password = self::$config_list['redis_password'] ? : '';
        $database = self::$config_list['redis_database'] ? : 0;
        //Redis连接
        $this->redis = new \Redis();
        try {
            $this->redis->connect($host, $port);
            if ($password) {
                $this->redis->auth($password);
            }
            $this->redis->ping();

            //设置redis database哪个库,默认最大不超过16个库,默认采用第0个库
            if ($database <= 16 && $database >= 0) {
                $this->redis->select($database);
            }

        } catch (\Exception $e) {
            Log::debug('connect redis ' . $host . ':' . $port . ' ' . $e->getMessage(), 'Redis');
            return false;
        }
        return $this->redis;
    }

    /**
     * 透明地调用redis其它操作方法
     * 用来支持redis命令调用
     * 命令参考：http://redisdoc.com/
     * @param  string  $name
     * @param  array   $params
     * @return mixed
     */
    public function __call($name, $params)
    {
        if (strtolower($name) == 'get') {
            $res = $this->redis->keys('login.token:' . $params[0] . ':*');
            if ($res) {
                return $this->redis->get($res[0]);
            } else {
                return false;
            }
        } else if (strtolower($name) == 'hgetall') {
            return $this->redis->hGetAll('get.user.info.by.uid:' . $params[0]);
        } else {
            return false;
        }
    }
}