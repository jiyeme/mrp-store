<?php

namespace Mrp\Server\Resource;

class ResourceHandle
{

    private static $dict = [
        '00002a31' => 'appid',
        '00002a32' => 'name',
        '00002a33' => 'version1',
        '00002a34' => 'version2',
        '00002a36' => 'resid',

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

    static function parse($data)
    {
        $parseFunc = [
            'appid' => function ($arg) {
                return hexdec(bin2hex($arg));
            },
            'name' => function ($arg) {
                return mb_convert_encoding($arg, 'UTF-8', 'GBK');
            },
            'version1' => function ($arg) {
                return hexdec(bin2hex($arg));
            },
            'version2' => function ($arg) {
                return hexdec(bin2hex($arg));
            },
            'resid' => function ($arg) {
                return hexdec(bin2hex($arg));
            },
            'imei' => function ($arg) {
                return $arg;
            },
            'imsi' => function ($arg) {
                return $arg;
            },
            'brand' => function ($arg) {
                return $arg;
            },
            'model' => function ($arg) {
                return $arg;
            },
            'platform' => function ($arg) {
                return $arg;
            },
            'timestamp' => function ($arg) {
                return $arg;
            }
        ];
        $result = [];
        $len = strlen($data);
        for ($i = 0; $i < $len;) {
            $mark = bin2hex(substr($data, $i, 4));
            $i += 4;
            $temp_len = hexdec(bin2hex(substr($data, $i, 4)));
            $i += 4;
            $temp_data = substr($data, $i, $temp_len);
            $i += $temp_len;

            $key = isset(self::$dict[$mark]) ? self::$dict[$mark] : $mark;
            $temp_data = isset($parseFunc[$key]) ? $parseFunc[$key]($temp_data) : bin2hex($temp_data);
            $result[$key] = $temp_data;
        }

        if (isset($result['config'])) {
            $result['config'] = self::parse(hex2bin($result['config']));
        }
        return $result;
    }
}

class ResourceResponse
{
    private $head = "";
    private $resId = ['000002BE', '00000004', '00000000'];
    private $storeFile = ['000002C0', '00000000', ''];
    private $storeFolder = ['000002C1', '00000000', ''];
    private $size = ['000002C4', '00000004', '00000000'];
    private $md5 =  ['000002C5', '00000010', ''];
    private $downloadLength =  ['000002C6', '00000004', '00000000'];

    function __construct()
    {
        $this->head .= "00 00 00 64 00 00 00 04 00 00 00 C8";
        $this->head .= "00 00 00 65 00 00 00 04 00 00 00 04";
        $this->head .= "00 00 00 6D 00 00 00 14 31 35 38 33 37 31 30 32 37 31 37 30 31 36 30 31 30 32 35 32";
        $this->head .= "00 00 02 BC 00 00 00 02 00 01";
        $this->head = str_replace(' ', '', $this->head); // 清除空格
    }

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param int $resId 资源ID
     * @return type
     * @throws conditon
     **/
    public function setResId(int $resId)
    {
        $this->resId[2] = $this->exHex(dechex($resId), 8);
    }
    /**
     * 设置存储的文件名
     *
     * 文件下载完成后，将会被重命名为此名称
     *
     * @param string $var Description
     * @return void
     **/
    public function setStoreFile(string $filename = null)
    {
        if($filename == null)$filename = '';

        $len = strlen($filename);
        $this->storeFile[2] = $this->exHex(bin2hex($filename), $len * 2);
        $len = dechex($len);
        $this->storeFile[1] = $this->exHex($len, 8);
    }

    /**
     * 设置存储文件夹
     *
     * 文件下载完毕后，会被移动到此文件夹
     *
     * @param string $folder 文件夹
     * @return void
     **/
    public function setStoreFolder(string $folder = null)
    {
        if($folder == null)$folder = '';

        $len = strlen($folder);
        $this->storeFolder[2] = $this->exHex(bin2hex($folder), $len * 2);
        $len = dechex($len);
        $this->storeFolder[1] = $this->exHex($len, 8);
    }

    /**
     * 设置文件大小
     *
     * 设置文件大小
     *
     * @param int $size 文件大小
     * @return void
     **/
    public function setSize(int $size)
    {
        $size = dechex($size);
        $this->size[2] = $this->exHex($size, 8);
    }

    /**
     * 设置文件MD5
     *
     * 设置文件MD5
     *
     * @param string $md5 Description
     * @return type
     **/
    public function setMd5(string $md5)
    {
        if($md5 == null)$md5 = sprintf("%032s", '0');
        $this->md5[2] = $md5;
    }

    /**
     * 生成响应体头部
     *
     * 生成响应体头部
     *
     * @return string 十六进制头部
     **/
    public function gen()
    {
        $resp = $this->head;

        // 配置处理
        $config = "00 00 02 C7 00 00 00 04 00 00 00 01";
        $config .= join($this->resId);
        $config .= "00 00 02 BF 00 00 00 04 00 00 00 02";
        $config .= join($this->storeFile);
        $config .= join($this->storeFolder);
        $config .= "00 00 02 C2 00 00 00 02 00 00";
        $config .= "00 00 02 C3 00 00 00 04 00 00 00 00";
        $config .= join($this->size);
        $config .= join($this->md5);
        $config .= join($this->downloadLength);
        $config = str_replace(' ', '', $config);
        $config = ['000002BD', $this->exHex(dechex(strlen($config) / 2), 8), $config];

        $tail = "00 00 02 C3 00 00 00 04 00 00 00 00 ";
        $tail .= "00 00 02 D1" . $this->size[2];

        $resp = $resp . join($config) . $tail;
        return str_replace(' ', '', $resp);
    }

    /**
     * 在16进制字符串前补零
     *
     * @param string $hexStr 十六进制字符串
     * @param int $len 目标长度
     *
     * @return string 目标长度的前导为0的十六进制字符串
     */
    private function exHex(string $hexStr, int $len){
        return sprintf("%0{$len}s", $hexStr);
    }
}
