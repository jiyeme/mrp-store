<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Loader;
use QiniuStorage;

class ApiController extends Controller
{
    //
    function download(Request $request){

        try {
            //code...
            $token = $request->input('token');
            $id = $request->input('id');
            $password = $request->input('password');

            if (!$token || !$id || !$password) {
                throw new Exception("非法请求！", 403);
            }
            $downloadinfo = $request->session()->get('dowload_info');
            // var_dump($request->session()->all());

            if (null === $downloadinfo) {
                throw new Exception("数据丢失！请刷新页面！", 404);
            }
            if ($downloadinfo['id'] != $id) {
                throw new Exception("操作异常，请刷新页面重试！", 403);
            }

            $secret = env('reCAPTCHA_server_key');

            $value = $this->http_post("https://www.recaptcha.net/recaptcha/api/siteverify", "secret={$secret}&response={$token}");
            //参数建议用与符号拼接

            /* {
            "success": true,
            "challenge_ts": "2020-03-06T15:18:45Z",
            "hostname": "mrp.jysafe.cn",
            "score": 0.9
            }
            */
            // echo $value;
            $ret = json_decode($value, true);
            if ($ret['success'] != true || $ret['score'] < 0.5) {
                throw new Exception("你可能是机器人！<span style=\"color:red;\">如果误判请重试！</span>", 403);
            }

            if (env('DL_PASS_STATUS'))
                if ($password !== env('DL_PASS')) {
                    throw new Exception("密码错误！请在群公告中查看最新密码。", 105);
                }

            // 生成密钥
            $key = $this->getUniqid();
            // $km = new KeyModel();
            // if(false === $km->storeKey($key))throw new Exception("密钥存储失败", 101);

            $out = array(
                'code' => 200,
                'msg' => str_replace('//', '/', "/{$downloadinfo['path']}")
            );

            if(!env('STORAGE_QINIU_ENABLE')){
                $out['msg'] = 'http://' . env('STORAGE_DOWNLOAD_DOMAIN') . $out['msg'];
                exit(json_encode($out));
            }

            // 启用七牛
            Loader::library('QiniuStorage');
            $qn = new QiniuStorage(env('STORAGE_QINIU_AK'), env('STORAGE_QINIU_SK'));
            $out['msg'] = str_replace(env('STORAGE_QINIU_HOST'), env('STORAGE_DOWNLOAD_DOMAIN'), $qn->genDownloadUrl('http://' . env('STORAGE_QINIU_HOST') .$out['msg']));

            exit(json_encode($out));
        } catch (\Throwable $th) {
            //throw $th;
            echo json_encode(array(
                'code' => $th->getCode(),
                'msg' => $th->getMessage()
            ));
        }
    }

    private function http_post($url, $postbody)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        // CURLOPT_RETURNTRANSFER  设置是否有返回值
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //这个是重点，如果是https请求一定要加这句话。
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postbody);
        //执行完以后的返回值
        $response = curl_exec($curl);
        //释放curl
        curl_close($curl);
        return $response;
    }

    public function getUniqid()
    {
        return md5(uniqid(microtime(true),true));
    }

    public function api(Request $request){
        // $download_domain = mrp_storage::get_bucket_domains();
        // $fluxUse = mrp_storage::get_cdn_flux();

        // if(!is_numeric($fluxUse))
        // {
        //     mrp_loginfo(json_encode($fluxUse));
        //     header('content-type:application/json');
        //     echo json_encode($ERROR);
        //     die;
        // }else{
        //     // 防止流量超标
        //     if($fluxUse > 14){
        //         $fluxEnd = 1;
        //         if($download_domain != null)
        //             mrp_storage::offline_domains();
        //     }else{
        //         $fluxEnd = 0;
        //         if($download_domain == null)
        //             mrp_storage::online_domains();
        //     }
        // }

        $fluxEnd = 0;
        $download_domain = 'mrp-cdn.jysafe.cn';

        $action = $request->get('action');
        $appid = $request->get('appid');

        $type = $request->get('type');
        $slug = 'MRPAPP';


        if ($action == null)
            die;

        $term = DB::selectOne("SELECT b.count, a.name FROM `store_terms` a, `store_term_taxonomy` b WHERE a.term_id=b.term_id AND a.slug=?", [$slug]);
        $outPut = array();

        switch ($action) {
            case 'searchApp':
                $outPut = $this->API_searchApp($request);
                break;
            case 'lsApp':
                $res = $this->API_lsApp($request);
                $outPut = array(
                    'total' => $term->count,
                    'rows' => $res
                );
                break;
            case 'lsMrp':
                if ($appid == 0) {
                    $outPut = array(
                        'total' => 0,
                        'rows' => null
                    );
                    break;
                }
                $res = $this->API_lsMrp($request);
                $outPut = array(
                    'total' => count($res),
                    'rows' => $res
                );
                break;
            case 'change':
                echo '<title>更新日志</title>';
                echo '3.2.20 由小蟀修复冒泡社区、网游登录<br>';
                echo '3.2.18 由祭夜修复商城<br><br>';
                echo '更早？不知道啊~';
                exit;
                break;
            default:
                // header("Location:http://mrp.jysafe.cn/mrpApp");
                break;
        }

        // $page = isset($_GET['page']) ? $_GET['page'] : 1;
        // $num_mrp = mrp_count();

        // if (($page - 1) * 20 > $num_mrp)
        //     $page = 0.9 + $num_mrp / 20;
        // $total_page = (int) (0.9 + $num_mrp / 20);

        // $download_domain = mrp_storage::get_bucket_domains();

        // $res = get_mrp_by_page($page);

        header('content-type:application/json');
        echo json_encode($outPut);
    }

    // ===============API=======================
    //搜索
    public function API_searchApp(Request $request) {
        // GET--->{"action":"searchApp","start":"0","count":"10"}POST--->{"key":"qq","type":"lebel"}
        $start = $request->get('start', 0);
        $count = $request->get('count', 10);
        $key = $request->get('key', '');
        $type = $request->get('type');
        $sql = "SELECT * FROM `store_app` WHERE `name` LIKE ? AND length(icon)<10 LIMIT ?, ?";

        $query = DB::select($sql, ["%$key%", $start, $count]);
        $i = 0;
        $rows = array();
        foreach ($query as $row) {
            $rows[$i++] = array(
                '_id' => $row->id,
                'appid' => $row->icon,
                'label' => $row->name,
                'name' => 'name',
                'vendor' => $row->author,
                'desc' => $row->description,
                'flag' => '1',
                'addTime' => date('Y-m-d', $row->addTime)
            );
        }

        $total = 0;
        if(empty($rows))
        {
            $sql = "SELECT * FROM `store_app` WHERE `author` LIKE ? AND length(icon)<10 LIMIT ?, ?";
            $query = DB::select($sql, ["%$key%", $start, $count]);
            $i = 0;
            $rows = array();
            foreach ($query as $row) {
                if(strlen($row['icon'])<10)
                    $rows[$i++] = array(
                        '_id' => $row->id,
                        'appid' => $row->icon,
                        'label' => $row->name,
                        'name' => 'name',
                        'vendor' => $row->author,
                        'desc' => $row->description,
                        'flag' => '1',
                        'addTime' => date('Y-m-d', $row->addTime)
                    );
            }
            //计算数目
            $sql = "SELECT COUNT(*) FROM `store_app` WHERE `author` LIKE ? AND length(icon) < 10";
            $query = DB::selectOne($sql, ["'%$key%'"]);

            $total = $query->{'COUNT(*)'};
        }

        if($total === 0){
            //计算数目
            $sql = "SELECT COUNT(*) FROM `store_app` WHERE `name` LIKE ? AND length(icon) < 10";
            $query = DB::selectOne($sql, ["'%$key%'"]);
            $total = $query->{'COUNT(*)'};
        }

        return array('total' => $total, 'rows' => $rows);
    }

    //MRP应用列表
    public function API_lsApp(Request $request) {

        $array_content = array();
        /////////////////////流量耗尽////////////////
        // global $fluxEnd;
        // if($fluxEnd){
        //     $array_content[0] = array(
        //         '_id' => '0',
        //         'appid' => '0',
        //         'label' => '公告-流量耗尽',
        //         'name' => '公告-流量耗尽',
        //         'vendor' => '祭夜 于 ' . date('Y-m-d') . ' 发布',
        //         'desc' => '云存储流量耗尽，停止下载服务，下月初恢复',
        //         'flag' => '1',
        //         'addTime' => '2020-02-27 13:35'
        //     );
        //     return $array_content;
        // }
        /////////////////////流量耗尽END////////////////

        //GET--->{"action":"lsApp","start":"0","count":"10","type":"0"}
        $start = $request->get('start', 0);
        $count = $request->get('count', 10);

        /**
         * @type 0 必备|1 最新|2 软件|3 游戏
         * */
        $type = $request->get('type', 0);
        $slugs = ['MRPAPP'];
        if($type == 0)$slugs=['comm'];
        else if($type == 2)$slugs=['software'];
        else if($type == 3)$slugs=['game'];

        $list = $this->getAppByPage($start / $count + 1, $count + 1, $slugs);
        $id = 0;
        if($start == 0)
        {
            $array_content[0] = array(
                '_id' => '0',
                'appid' => '0',
                'label' => '公告-搜索已修复',
                'name' => '公告-搜索已修复',
                'vendor' => '祭夜',
                'desc' => "搜索已修复\nmrp.jysafe.cn现已支持JAVA软游",
                'flag' => '1',
                'addTime' => '2021-03-21 16:00'
            );
            // $array_content[1] = array(
            //     '_id' => '0',
            //     'appid' => '0',
            //     'label' => '公告-祝攻击者没有后代',
            //     'name' => '公告-还有一件事',
            //     'vendor' => '祭夜',
            //     'desc' => "仅此而已",
            //     'flag' => '1',
            //     'addTime' => '2020-10-06 09:37'
            // );
            $id = 1;

        }
        if($list)
        foreach ($list as $row) {
            $resolution = $this->getTermNameByTaxonomy($row->id, 'app_resolution');
            $prefix = $resolution?"[{$resolution->name}]":"";
            if(strlen($row->icon)<10)
            $array_content[$id++] = array(
                '_id' => $row->id,
                'appid' => $row->icon,
                'label' => $prefix.$row->name,
                'name' => 'name',
                'vendor' => $row->author,
                'desc' => $row->description,
                'flag' => '1',
                'addTime' => date('Y-m-d H:i:s', $row->addTime)
            );
        }
        return $array_content;
    }

    //MRP应用详情
    public function API_lsMrp(Request $request) {

        //GET--->{"action":"lsMrp","appid":"830090"}
        $appid = $request->get('appid', 0);

        $sql = "SELECT * FROM `store_app` WHERE `id` = ? ORDER BY `version` DESC";

        $result = DB::select($sql, [$appid]);
        if (!$result){
            return false;
        }

        $array_content = array();
        $i = 0;
        foreach ($result as $row) {
            $array_content[$i++] = array(
                //应用界面不显示，用于点击后显示版本信息
                'appid' => $row->id,
                'mr_appid' => $row->icon,
                '_id' => $row->id,
                'md5' => $row->md5,
                'ver' => $row->version,
                'url' => 'http://mrp-cdn.jysafe.cn/' . $row->file_path,
                'desc' => '00000',
                'addTime' => date('Y-m-d H:i:s', $row->addTime),
                'fsize' => $row->size / 1024 . 'kb',
            );
        }
        return $array_content;
    }

    public function API_get_mrp_by_page(Request $request, $page) {
        $temp = $this->getTotalCount() - 20 * $page;
        if ($temp < 0)
            $temp = 0;
        $sql = "SELECT * FROM `store_app` WHERE length(icon)<10 LIMIT ?, 20";

        $result = DB::select($sql, [$temp]);
        if (!$result){
            return false;
        }
        $array_content = array();
        $id = 0;
        foreach ($result as $row) {
            $array_content[$id++] = $row;
        }
        return $array_content;
    }
    // ===============API END================

    /**
     * 获取MRP个数
     *
     * @return int
     */
    public function getTotalCount()
    {
        $sql = "select count(1) from `store_app`";
        $count = DB::selectOne($sql);
        return (int)$count;
    }

    /**
     * 根据slug获取指定页面应用数据
     *
     */
    public function getAppByPage(int $page, int $pageSize, array $slug)
    {
        if($page<1)$page=1;
        $sql = "SELECT a.id, a.name, a.icon, a.version, a.size, a.author, a.description, a.addTime FROM store_app a
        WHERE id in
            (SELECT a.object_id FROM
                (SELECT `object_id` FROM store_terms b, store_term_relationships c, store_term_taxonomy d
                    WHERE c.term_taxonomy_id=d.term_taxonomy_id AND d.term_id=b.term_id AND b.slug='{$slug[0]}' ";
        for($i=1; $i<count($slug); $i++)
            $sql .= "AND object_id in (SELECT a.object_id FROM
                        (SELECT `object_id` FROM store_terms b, store_term_relationships c, store_term_taxonomy d WHERE c.term_taxonomy_id=d.term_taxonomy_id AND d.term_id=b.term_id AND b.slug='{$slug[$i]}' ORDER BY `object_id` DESC LIMIT 0, 11 ) a
                        )";
        $sql .= "ORDER BY `object_id` DESC LIMIT " . (($page - 1) * $pageSize) . ", {$pageSize}
                ) a
            )";
        $result =  DB::select($sql);
        if (!$result) {
            return false;
        }
        $array_content = array();
        $id = 0;
        foreach ($result as $row) {
            $array_content[$id++] = $row;
        }

        return $array_content;
    }

    /**
     * 获取应用指定类型最高分标签名称
     *
     * @param int $id 应用id
     * @param string $taxonomy 标签分类[category|app_resolution|app_tag]
     *
     * @return object
     */
    public function getTermNameByTaxonomy(int $id, string $taxonomy)
    {
        $sql = "SELECT a.name FROM `store_terms` a, `store_term_relationships` b, `store_term_taxonomy` c
        WHERE a.term_id=c.term_id AND b.term_taxonomy_id=c.term_taxonomy_id AND c.taxonomy='{$taxonomy}'
        AND b.object_id={$id} ORDER BY b.term_score DESC LIMIT 1";
        return DB::selectOne($sql);
    }
}
