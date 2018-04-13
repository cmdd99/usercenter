# ddauth-ucenter
# author
    wangben
# 服务说明
    1.提供login,register,logout,sendsms,checksms等服务api
    2.接口设计采用http restful风格，支持post,get方式调用
# 使用方法
    demo如下
    <?php
        //请求demo
        defined('ROOT_PATH') or define('ROOT_PATH', dirname(__DIR__));
        defined('VENDOR_PATH') or define('VENDOR_PATH', ROOT_PATH . '/vendor');
        defined('RUNTIME_PATH') or define('RUNTIME_PATH', ROOT_PATH . '/runtime');

        //调用api必选常量定义
        defined('LOG_PATH') || define('LOG_PATH', RUNTIME_PATH . '/logs');
        //api服务域名前缀
        defined('DD_API_SERVICE_URI') || define('DD_API_SERVICE_URI', 'http://www.dadi.com/api');
        defined('DD_API_APPID') or define('DD_API_APPID', '234');
        defined('DD_API_APPSECRET') or define('DD_API_APPSECRET', '234');
        //接口是否记录日志
        define('DD_API_APPDEBUG') or define('DD_API_APPDEBUG', is_file('/etc/php.env.production'));

        //加载composer
        require_once VENDOR_PATH . '/autoload.php';

        var_dump(class_exists("dduc\api\service\RpcService"));
        $params = ['uid' => 12, 'name' => 'heige'];

        //调用方式1：(采用透明的方式调用远程方法)
        $res = dduc\api\service\RpcService::getInstance(); //返回调用的proxy代理，采用单例模式
        //不解析json原样返回
        var_dump($res->call('getUserInfo', $params, false));die;

        //调用方式2:(采用php魔术方法实现调用)
        // $res = dduc\api\service\ApiService::getInstance();
        // 解析json
        // var_dump($res->getUserInfo($params,true));die;
        // 两种方式调用，都会判断调用的方法是否存在

        // *各项目调用方式
        //   appId,appSecret在dduc这边申请
        // $res    = \dduc\api\service\RpcService::getInstance();
        // $params = ['uid' => 12, 'name' => 'heige'];
        // var_dump($res->call('getUserInfo', $params, ['header' => 1], true));die;
# license
    采用MIT


# Code