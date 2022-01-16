<?php

namespace App\Http\Controllers;

use App\Models\MrpApp;
use App\Models\MrpList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AppMagController extends Controller
{
    // GET 应用列表
    public function appList(){
        $result = MrpList::paginate(20);

        $appList = $result->items();
        $currentpage = $result->currentPage();
        $total = $result->total();
        $lastPage = $result->lastpage();

        return Inertia::render('Dashboard/AppMag', [
            'appList' => $appList,
            'currentPage' => $currentpage,
            'total' => $total,
            'lastPage' => $lastPage
        ]);
    }

    // GET 应用详情
    public function getAppDetail($id){
        $mrp = MrpList::find($id);
        $listId = $mrp->id;
        $verList = MrpApp::where('list_id', $listId)->get();

        return Inertia::render('Dashboard/AppDetail', [
            'appDetail' => $mrp,
            'verList' => $verList,
        ]);
    }

    // DELETE 删除应用
    public function delApp($id){
        $result = DB::transaction(function () use ($id) {
            // 删除版本, 返回删除数量
            $result1 = MrpApp::where('list_id', $id)->delete();
            // 删除app信息，返回布尔值
            $result2 = MrpList::find($id)->delete();
            return [$result1, $result2];
        });
        return $result;
    }

    // GET 版本列表
    public function verList(){

    }

    // DELTETE 删除版本
    public function delVer(){

    }
}
