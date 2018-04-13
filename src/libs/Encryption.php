<?php
namespace usercenter\libs;

use usercenter\libs\Log;

/**
 * 数据加解密
 * @author wb
 */

class Encryption
{
    /**
     * 加密
     * @param $content Array|Int|String|Object [要加密的内容]
     * @return String
     */
    public static function encode($content)
    {
        $str = is_string($content) || is_int($content) ?: json_encode($content);
        return base64_encode($str);
    }

    /**
     * 解密
     * @param $content String [要解密的内容]
     * @return Array|Int|String|Object
     */
    public static function decode($content)
    {
        //
    }
}
