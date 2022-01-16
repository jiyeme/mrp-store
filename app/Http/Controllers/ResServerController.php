<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Loader;
use Mrp\Server\Resource\ResourceHandle;

class ResServerController extends Controller
{
    // TODO:下载
    public function simpleDownload(Request $request){
        Loader::library('resource');
        $data = file_get_contents ('php://input');
        $result = ResourceHandle::parse($data);
        $appid = $result['appid'];
        $resid = $result['resid'];
    }

    // 继续下载
    public function continueDownload(Request $request){
        echo 1;
    }
}
