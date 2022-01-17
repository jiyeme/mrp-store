<?php

namespace App\Http\Controllers;

use App\Models\MrpRes;
use EasyHttp;
use Illuminate\Http\Request;
use Loader;
use Mrp\Server\Resource\ResourceHandle;
use Mrp\Server\Resource\ResourceResponse;

class ResServerController extends Controller
{
    // 下载
    public function simpleDownload(Request $request){
        Loader::library('resource', 'easyHttp');
        $data = file_get_contents ('php://input');
        $inputData = ResourceHandle::parse($data);
        $appid = $inputData['appid'];
        $resid = $inputData['resid'];

        $result = MrpRes::where('res_id', $resid)->first();

        $rr = new ResourceResponse();
        if($result == null){
            return hex2bin($rr->gen());
        }
        $rr->setResId($resid);
        $rr->setSize($result->size);
        $rr->setMd5($result->md5);
        $rr->setStoreFile($result->store_file);
        $rr->setStoreFolder($result->store_folder);

        $header = hex2bin($rr->gen());
        $http = new EasyHttp();
        $response = $http->request("http://mrp-cdn.jysafe.cn/{$result->path}", array(
            'method' => 'GET',		//	GET/POST
            'timeout' => 5,			//	超时的秒数
            'redirection' => 5,		//	最大重定向次数
            'httpversion' => '1.1',	//	1.0/1.1
            //'user-agent' => 'USER-AGENT',
            'blocking' => true,		//	是否阻塞
            'headers' => array(),	//	header信息
            'cookies' => array(),	//	关联数组形式的cookie信息
            'body' => null,
            'compress' => false,	//	是否压缩
            'decompress' => true,	//	是否自动解压缩结果
            'sslverify' => true,
            'stream' => false,
            'filename' => null		//	如果stream = true，则必须设定一个临时文件名
        ));
        file_put_contents(app_path('test'), $header.$response['body']);
        return $header.$response['body'];
    }

    // TODO:继续下载
    public function continueDownload(Request $request){
        return $this->simpleDownload($request);
    }
}
