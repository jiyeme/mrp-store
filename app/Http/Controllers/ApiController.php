<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
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
                'msg' => "http://mrp-cdn.jysafe.cn/{$downloadinfo['path']}"
            );

            if(!env('STORAGE_QINIU_ENABLE')){
                exit(json_encode($out));
            }

            // 启用七牛
            Loader::library('QiniuStorage');
            $qn = new QiniuStorage(env('STORAGE_QINIU_AK'), env('STORAGE_QINIU_SK'));
            $out['msg'] = $qn->genDownloadUrl($out['msg']);

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
}
