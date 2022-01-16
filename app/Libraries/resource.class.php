<?php

namespace Mrp\Server\Resource;

class ResourceHandle{

    private static $dict = [
        '00002a31' => 'appid',
        '00002a32' => 'name',
        '00002a33' => 'version1',
        '00002a34' => 'version2',

        '00002a3a' => 'brand',
        '00002a3b' => 'model',
        '00002a3c' => 'platform',

        '00002a41' => 'imei',
        '00002a42' => 'imsi',

        '0000277c' => 'timestamp',

        '000029ce' => 'resid',

        '000029d5' => 'md5',
        '000029cd' => 'config',

    ];

    // private static $parseFunc = [
    //     'appid' => function($arg){

    //     }
    // ];

    static function parse($data)
    {
        $parseFunc = [
            'appid' => function($arg){
                return hexdec(bin2hex($arg));
            },
            'name' => function($arg){
                return mb_convert_encoding($arg, 'UTF-8', 'GBK');
            },
            'version1' => function($arg){
                return hexdec(bin2hex($arg));
            },
            'version2' => function($arg){
                return hexdec(bin2hex($arg));
            },
            'resid' => function($arg){
                return hexdec(bin2hex($arg));
            },
            'imei' => function($arg){
                return $arg;
            },
            'imsi' => function($arg){
                return $arg;
            },
            'brand' => function($arg){
                return $arg;
            },
            'model' => function($arg){
                return $arg;
            },
            'platform' => function($arg){
                return $arg;
            },
            'timestamp' => function($arg){
                return $arg;
            }
        ];
        $result = [];
        $len = strlen($data);
        for ($i=0; $i < $len; ) {
            $mark = bin2hex(substr($data, $i, 4));
            $i += 4;
            $temp_len = hexdec(bin2hex(substr($data, $i, 4)));
            $i += 4;
            $temp_data = substr($data, $i, $temp_len);
            $i += $temp_len;

            $key = isset(self::$dict[$mark])?self::$dict[$mark]:$mark;
            $temp_data = isset($parseFunc[$key])?$parseFunc[$key]($temp_data):bin2hex($temp_data);
            $result[$key] = $temp_data;
        }

        if(isset($result['config'])){
            $result['config'] = self::parse(hex2bin($result['config']));
        }
        return $result;
    }
}
