<?php

namespace App\Http\Controllers;

use App\Models\AppModel;
use App\Models\MrpApp;
use App\Models\MrpList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{
    //
    public function list($slug, $page = 1)
    {

        $mrpList = MrpList::paginate(20);

        $appList = $mrpList->items();

        // Get App Info
        $title = "第{$mrpList->currentPage()}页";

        return view('list', [
            'tags' => [],
            'slug' => $slug,
            'appList' => $appList,
            'total_page' => $mrpList->total(),
            'page' => $page,
            'term' =>  [],
            'title' =>  $title,
            'count' =>  $mrpList->count()
            ]
        );
    }

    public function info(Request $request, $id){
        // header("content-type:application/json");

        $mainInfo = MrpList::find($id);
        $mainInfo->icon = $mainInfo->appid;
        $verInfo = MrpApp::where('list_id', $mainInfo->id);
        /*
        Array
        (
            [id] => 11474
            [version] => 101
            [name] => 计算器老虎版
            [author] => 老虎会游泳
            [description] => 计算器老虎会游泳开发，编译于2020.10.26
            [appId] => 30043
            [ch] => e6c1ec341
            [file_path] => mrpApp/计算器老虎版_52828a308400ea223128acc523e9b429.mrp
            [md5] => 52828a308400ea223128acc523e9b429
            [size] => 252666
            [addTime] => 1603815891
            [tags] => Array
                (
                )

        )
        */

        $request->session()->put('dowload_info', array(
            'id' => $id
        ));
        // var_dump($request->session()->all());

        $title = "{$mainInfo->name} - 下载";

        return view('info', [
            'term' => [
                'slug' => 'MRPAPP',
                'name' => 'MRP应用'
            ],
            'tags' => [],
            'title' => $title,
            'ret'=> $mainInfo,
            'verList' => $verInfo,
            'id' => $id
        ]);
    }
}
