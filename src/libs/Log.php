<?php
namespace usercenter\libs;

/**
 * 日志处理类
 * @author wb
 */
class Log
{
    public static $config_list  = [];

    public static function init() 
    {
        if(!count(self::$config_list)) {
            self::$config_list = require dirname(__DIR__) . '/libs/Config.php';
        }
    }

    //普通日志记录
    public static function write($content = '', $describe = 'info', $class = 'info')
    {
        if(!$content) {
            return false;
        }
        return self::record($content, $class, $describe);
    }

    //Debug日志记录
    public static function debug($content = '', $describe = 'info', $class = 'debug') {
        self::init();

        if(self::$config_list['debug'] === false) {
            return true;
        }

        return self::record($content, $class, $describe);
    }

    //开始记录日志
    public static function record($content, $class, $describe) {
        self::init();

        $text = is_int($content) || is_string($content) ? $content : is_array($content) ? var_export($content, true) : json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $file_name = isset(self::$config_list['log_file_name']) && self::$config_list['log_file_name'] ? self::$config_list['log_file_name'] . '.log' : date('Y-m-d') . '.log';
        $file_path = isset(self::$config_list['log_path']) && self::$config_list['log_path'] ? rtrim(self::$config_list['log_path'], '/') . '/' . $class : dirname(__DIR__) . '/log/' . $class . '/';

        try {
            if(!file_exists($file_path)) {
                @mkdir($file_path, 0777, true);
            }
            $file = $file_path . '/' . $file_name;
            $handle = fopen($file, 'a+');
            fwrite($handle, '[' . $describe . '] [' . date('Y-m-d H:i:s').']' . "\r\n" . $text . "\r\n\r\n");
            fclose($handle);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
