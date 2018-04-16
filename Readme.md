# ucenter
# author
    wangben
# 服务说明
    1.提供login,register,logout,sendsms,checksms等服务api
    2.接口设计采用http restful风格，支持post, get方式调用
# 使用方法
    demo如下：

    1、配置Config: /usercenter/src/libs/Config.php
    return [
        'appid'          => '123456',//appID
        'appsecret'      => '654321',//app秘钥
        'url'            => '',//1、测试环境：http://ucenter.dadi01.net/api 2、本地环境(hosts[192.168.73.1 www.passport.com])：http://www.passport.com/api
        'debug'          => true,//是否开启Debug模式true|false
        'log_path'       => '',//日志路径，默认/usercenter/src/log/
        'log_file_name'  => '',//日志名称，默认date('Y-m-d')
    ];
     
    2、接口Route.php /usercenter/ser/libs/Routh.php（请勿改动）
    return [
        'getUserInfo'            => 'get',
        'setUserBaseInfo'        => 'post',
        'sendSms'                => 'post',
        'checkSmsCode'           => 'post',
        'register'               => 'post',
        'login'                  => 'post',
        'logout'                 => 'post',
        'editorMobileSendSms'    => 'post',
        'editorMobile'           => 'post',
        'lookIntegral'           => 'get',
        'changeIntegral'         => 'post',
        'avatar'                 => 'post',
        'rollBackRegister'       => 'get',
    ];
    
    3、怎么使用
    <?php

        $info = \usercenter\service\RpcService::getInstance();

        /*
         * 调取用户中心数据
         * @param String $route 路由名称，例：getUserInfo
         * @param Array  $param 业务参数，例：['aaa' => 1, 'bbb' => 2, 'ccc' => 3]
         */
        $res = $info->call($route, $param);
        print_r($res);
    ?>

    4、接口数据返回
    成功：{"code":0,"message":"success", "data":""}
    失败：{"code":10002,"message":"参数错误，请求异常"}

    5、code码说明
    0       成功
    10001   参数错误
    10002   签名错误，请求异常
    10003   redirect_uri域名与后台配置不一致
    10004   此公众号被封禁
    10005   此公众号并没有这些scope的权限
    10006   必须关注此测试号
    10009   操作太频繁了，请稍后重试
    10010   scope不能为空
    10011   redirect_uri不能为空
    10012   appid不能为空
    10013   state不能为空
    10015   公众号未授权第三方平台，请检查授权状态
    10016   不支持微信开放平台的Appid，请使用公众号Appid
    10017   微信code不能为空
    10018   获取微信access_token失败
    10019   access_token或openid不能为空
    10020   获取微信userinfo失败
    10021   生成用户userToken错误
    10022   错误的token
    10023   错误的appId
    10024   错误的appSecret
    10025   SMS验证码获取失败
    10026   验证码获取次数已达到上限
    10027   手机号码格式不正确
    10028   SMS验证码错误
    10029   登录失败
    10030   注册失败
    10031   Token无效需要登录
    10032   手机号码已被绑定
    10033   手机号码更换失败
    10034   UnionId也被绑定
    10035   Email已被绑定
    10036   注册超时（注册手机号码无效）
    10037   用户信息编辑失败
    10038   积分查看失败
    10039   积分变更失败
    10040   剩余积分不足以扣除
    10041   Unionid不合法，请重新授权
    10042   第三方请求token不合法
    10043   第三方请求token已过期
    10044   第三方Unionid未注册
    10045   Unionid或Openid不能为空
    10046   加密有误
    10047   IP地址不在白名单
    10048   商户账号被冻结

# license
    采用MIT