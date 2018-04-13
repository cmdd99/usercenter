<?php
namespace usercenter\libs;

use usercenter\libs\Log;
use usercenter\libs\Encryption;

/**
 * 签名相关的函数
 * @author wb
 */

class Sign
{
    //随机生成字符串
    public static function createNonceStr($length)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str   = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        return $str;
    }

    /**
     * [makeSign 客户端生成签名，返回签名后的参数]
     * $params 参数中必选参数 timeStamp,nonceStr
     * @param  integer $len                   [随机字符串的长度]
     * @return [array] [签名后的参数]
     */
    public static function makeSign($param = [], $appid, $appsecret, $length = 43)
    {
        Log::debug($param, '业务参数');

        //业务参数Key排序
        ksort($param, SORT_STRING);

        $time        = time();
        $str         = self::createNonceStr($length);
        $param_json  = json_encode($param);

        $string      = 'param=' . $param_json . '&time=' . $time . '&str=' .$str;
        $str_sign    = md5(sha1(md5(sha1(md5(base64_encode($string))))));
        $sign        = strtoupper($str_sign);

        $param_encode = Encryption::encode(['param' => $param, 'appId' => $appid, 'appSecret' => $appsecret]);

        $form_params = [
            'sign'           => $sign,
            'time'           => $time,
            'str'            => $str,
            'param'         => $param_encode
        ];

        return $form_params;
    }
}
