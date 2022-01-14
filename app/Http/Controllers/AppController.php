<?php

namespace App\Http\Controllers;

use App\Models\AppModel;
use App\Models\MrpApp;
use App\Models\MrpList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AppController extends Controller
{
    //
    public function list(Request $request, $slug, $page = 1)
    {

        $mrpList = MrpList::paginate(20);

        $appList = $mrpList->items();

        foreach($appList as $app){
            if (file_exists(app_path('/public/assets/img/app/' . strtoupper(base_convert($app->appid, 10, 32)) . '.bmp')))
                $app->icon = '/assets/img/app/' . strtoupper(base_convert($app->appid, 10, 32)) . '.bmp';
            else
                $app->icon = '/assets/img/mrp-icon.png';
        }

        // Get App Info
        $title = "第{$mrpList->currentPage()}页";

        $pageData = [
            'current' => $mrpList->currentPage(),
            'total' => $mrpList->total(),
            'lastPage' => $mrpList->lastPage(),
            'pageSize' => $mrpList->perPage()
        ];
        return Inertia::render('App/list', [
            'tags' => [],
            'slug' => $slug,
            'appList' => $appList,
            'pageData' => $pageData,
            'title' =>  $title,
            'count' =>  $mrpList->count()
            ]
        );
    }

    public function info(Request $request, int $id){
        // header("content-type:application/json");

        $appInfo = MrpList::find($id);
        $appInfo->icon = $appInfo->appid;
        $icon = null;
        if(strlen($appInfo->icon)>10)
            // jar
            $icon = file_exists(app_path("public/{$appInfo->icon}"))? $appInfo->icon:"/assets/img/jar-icon.png";
        else if (file_exists(app_path('/public/assets/img/app/' . strtoupper(base_convert($appInfo->icon, 10, 32)) . '.bmp')))
            $icon = '/assets/img/app/' . strtoupper(base_convert($appInfo->icon, 10, 32)) . '.bmp';
        else
            $icon = '/assets/img/mrp-icon.png';

        $verInfo = MrpApp::where('list_id', $appInfo->id)->get();

        $verList = [];
        foreach($verInfo as $ver){
            $verList[$ver->id] = $ver->path;
        }

        header("content-type: application/json");
        $request->session()->put('dowload_info', array(
            'id' => $id,
            'verList' => $verList
        ));

        $title = "{$appInfo->name} - 下载";

        return Inertia::render('App/detail', [
            'term' => [
                'slug' => 'MRPAPP',
                'name' => 'MRP应用'
            ],
            'tags' => [],
            'title' => $title,
            'appInfo'=> $appInfo,
            'verList' => $verInfo,
            'id' => $id,
            'icon' => $icon,
            'captchaKey' => env('reCAPTCHA_site_key')
        ]);
    }
}
