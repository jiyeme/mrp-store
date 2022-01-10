<?php


use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
use \Qiniu\Cdn\CdnManager;

class QiniuStorage
{
    private $accessKey;
    private $secretKey;

    public function __construct($accessKey, $secretKey)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }

    static function get_upload_token()
    {
        // 初始化签权对象
        $auth = new Auth(env('STORAGE_QINIU_AK'), env('STORAGE_QINIU_SK'));
        //上传
        $bucket = env('STORAGE_QINIU_BUCKET');
        // 生成上传Token
        return $auth->uploadToken($bucket);
    }

    static function qiniu_upload($up_token, $file_name, $file_path)
    {
        // 构建 UploadManager 对象
        $uploadMgr = new UploadManager();

        $up_result = $uploadMgr->putFile($up_token, $file_name, $file_path);

        return $up_result;
    }

    static function is_file_exists($file_name)
    {

        // 初始化签权对象
        $auth = new Auth(env('STORAGE_QINIU_AK'), env('STORAGE_QINIU_SK'));
        $bucketManager = new BucketManager($auth);
        // 要列取文件的公共前缀
        $prefix = substr($file_name, 0, strlen($file_name) - 4);
        // $prefix = '08鼠年民俗日历_08鼠年民俗日历.mrp1';

        // 上次列举返回的位置标记，作为本次列举的起点信息。
        $marker = 0;

        // 本次列举的条目数
        $limit = 1;

        $delimiter = '';

        list($ret, $err) = $bucketManager->listFilesv2(env('STORAGE_QINIU_BUCKET'), $prefix, $marker, $limit, $delimiter, true);


        if ($err) {
            return $err;
        } else {
            // //记录
            // $logFile = __DIR__ . '/../log/mrp.log';
            // date_default_timezone_set('Asia/Shanghai');
            // file_put_contents($logFile, json_encode($ret), FILE_APPEND);
            //var_dump($ret);
            if (empty($ret))
                return false;
            else
                return true;
        }
    }

    // 获取对象存储域名
    static function get_bucket_domains()
    {
        //初始化签权对象
        $auth = new Auth(env('STORAGE_QINIU_AK'), env('STORAGE_QINIU_SK'));
        $config = new \Qiniu\Config();
        $bucketManager = new \Qiniu\Storage\BucketManager($auth, $config);
        list($domains, $err) = $bucketManager->domains(env('STORAGE_QINIU_BUCKET'));
        // var_dump($domains);
        if ($err) {
            if (is_object($err))
                $err = json_encode($err);
            // echo '下载域名获取失败，请稍后再试！！' . $err;
            return '#';
        } else {
            if (isset($domains[0]) && !empty($domains[0]))
                return $domains[0];
            else
                return null;
        }
    }

    // 获取已用流量
    static function get_cdn_flux()
    {
        $auth = new Qiniu\Auth(env('STORAGE_QINIU_AK'), env('STORAGE_QINIU_SK'));
        $cdnManager = new CdnManager($auth);

        //获取流量和带宽数据
        //参考文档：http://developer.qiniu.com/article/fusion/api/traffic-bandwidth.html

        $domains = array(
            "mrp-cdn.jysafe.cn"
        );

        $startDate = date('Y-m')."-01";
        $endDate = date('Y-m-d');

        //5min or hour or day
        $granularity = "day";

        //获取流量数据
        list($fluxData, $getFluxErr) = $cdnManager->getFluxData($domains, $startDate, $endDate, $granularity);

        if ($fluxData == null) {
            return $getFluxErr;
        } else if(!empty($fluxData['data'])){
            $fluxData['data'][$domains[0]]['china'];
            $use = 0;
            foreach ($fluxData['data'][$domains[0]]['china'] as $key => $value) {
                $use += $value;
            }
            return $use / 1024 / 1024 / 1024;
        }else{
            // mrp_loginfo(json_encode($fluxData));
            return '';
        }
    }

    // 域名下线操作
    static function offline_domains()
    {
        //初始化签权对象
        $auth = new Auth(env('STORAGE_QINIU_AK'), env('STORAGE_QINIU_SK'));
        $config = new \Qiniu\Config();
        $bucketManager = new \Qiniu\Storage\BucketManager($auth, $config);
        list($res, $err) = $bucketManager->offlineDomain('mrp-cdn.jysafe.cn');
        if($err != null){

        }else{

        }
    }

    // 域名上线操作
    static function online_domains()
    {
        //初始化签权对象
        $auth = new Auth(env('STORAGE_QINIU_AK'), env('STORAGE_QINIU_SK'));
        $config = new \Qiniu\Config();
        $bucketManager = new \Qiniu\Storage\BucketManager($auth, $config);
        list($res, $err) = $bucketManager->onlineDomain('mrp-cdn.jysafe.cn');
        if($err != null){

        }else{

        }
    }

    // 删除资源
    static function deleteRes($file)
    {
        //初始化签权对象
        $auth = new Auth(env('STORAGE_QINIU_AK'), env('STORAGE_QINIU_SK'));
        $config = new \Qiniu\Config();
        $bucketManager = new \Qiniu\Storage\BucketManager($auth, $config);
        list($ret, $msg) = $bucketManager->delete(env('STORAGE_QINIU_BUCKET'), $file);

        if($msg != NULL){
            if(612 === $msg->code())
                return true;
            return $msg;
        }else
            return true;
    }

    // 重命名资源
    static function reNameRes($oldName, $newName)
    {
        //初始化签权对象
        $auth = new Auth(env('STORAGE_QINIU_AK'), env('STORAGE_QINIU_SK'));
        $config = new \Qiniu\Config();
        $bucketManager = new \Qiniu\Storage\BucketManager($auth, $config);
        $ret = $bucketManager->rename(env('STORAGE_QINIU_BUCKET'), $oldName, $newName);
        if($ret != NULL)
            return $ret;
        else
            return true;
    }

    // 生成资源下载链接
    public function genDownloadUrl($baseUrl)
    {
        //初始化签权对象
        $auth = new Auth($this->accessKey, $this->secretKey);
        return $auth->privateDownloadUrl($baseUrl, 60);
    }
}
