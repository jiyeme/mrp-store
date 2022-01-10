<?php

namespace App\Http\Controllers;

use App\Models\Md5Check;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use JarInfo;
use Loader;
use MRP;
use PharIo\Manifest\Library;
use STORAGE;

class UploadController extends Controller
{
    //
    // private $uploadError = [
    //     "没有错误",
    //     "文件过大，不能超过" . ini_get('upload_max_filesize'),
    //     "UPLOAD_ERR_FORM_SIZE",
    //     "文件上传不完整",
    //     "文件没有被上传",
    //     "找不到临时文件夹",
    //     "文件写入失败"
    // ];

    // MD5检查
    public function md5Check(Request $request)
    {

        $md5 = $request->input('md5');
        if ($md5) {
            $result = DB::selectOne('SELECT `id` FROM `store_app` WHERE `md5` LIKE ?', [$md5]);
            if ($result) {
                //存在
                echo json_encode(array(
                    'errCode' => 2101,
                    'errMsg' => '已存在'
                ));
            } else {
                echo json_encode(array(
                    'errCode' => 2000,
                    'errMsg' => '不存在'
                ));
            }
        }
    }


    // MRP应用上传操作
    public function mrp()
    {
        try {

            // 上传状态检测
            if ($_FILES["file"]["error"] > 0)
                throw new Exception("错误：{$this->uploadError[$_FILES["file"]["error"]]}<br>", 20401);

            if (!isset($_FILES['file'])) throw new Exception("No file uploaded.", 20401);

            if ($_FILES["file"]["type"] !== "application/octet-stream") throw new Exception("文件流类型异常", 20401);

            // 允许上传的文件后缀
            $allowedExts = array("mrp");

            /**
             * 多个文件
             * ['file']['name'][0, 1, 2, ...]
             * ['file']['type'][0, 1, 2, ...]
             * ['file']['tmp_name'][0, 1, 2, ...]
             * ['file']['error'][0, 1, 2, ...]
             * ['file']['size'][0, 1, 2, ...]
             */

            $temp = explode(".", $_FILES["file"]["name"]);
            $temp_path = $_FILES["file"]["tmp_name"];
            if (empty($temp_path)) throw new Exception("File is not found.", 20401);

            $temp_md5 = md5_file($temp_path);

            // 获取文件后缀名
            $extension = end($temp);

            if (!in_array($extension, $allowedExts)) throw new Exception("File format is incorrect", 20401);

            // $this->loader->library('mrplib', 'QiniuStorage', 'storage', 'easyHttp');
            Loader::library('mrplib', 'QiniuStorage', 'storage', 'easyHttp');
            Loader::helper('upload');

            //获取文件信息
            $info = MRP::get($temp_path);

            if ($info == false) throw new Exception("Can`t get info of MRP file.", 20401);

            //存储名称：显示名 _ MD5 .mrp
            $file_name = "mrpApp/{$info['display_name']}_{$temp_md5}.mrp";
            //去空格
            $file_name = str_replace(' ', '', $file_name);
            //对介绍内容单引号进行纠正处理
            if (strpos($info['display_name'], "'") !== false) {
                $info['display_name'] = correct($info['display_name']);
            }
            if (strpos($info['version'], "'") !== false) {
                $info['version'] = correct($info['version']);
            }
            if (strpos($info['description'], "'") !== false) {
                $info['description'] = correct($info['description']);
            }

            $app_info = array(
                'icon' => $info['appId'],
                'name' => $info['display_name'],
                'author' => $info['author'],
                'description' => $info['description'],
                'version' => $info['version'],
                'file_path' => $file_name,
                'md5' => $temp_md5,
                'size' => $_FILES["file"]["size"],
                'addTime' => time()
            );

            $result = DB::selectOne('SELECT `id` FROM `store_app` WHERE `md5` LIKE ?', [$temp_md5]);
            if ($result)
                throw new Exception("File already exists", 20401);

            // 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
            // 判断当期目录下的 upload 目录是否存在该文件
            // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
            //move_uploaded_file($temp_path, "upload/" . $file_name);

            list($upload_result, $msg) = STORAGE::upload($temp_path, array(
                'storage_path' => $app_info['file_path'],
                'md5' => $app_info['md5']
            ));

            if (!$upload_result)  throw new Exception("文件上传至云端失败 {$msg}", 20401);

            //文件上传成功，写入数据库
            if (false === ($ret = DB::table('store_app')->insertGetId($app_info)))throw new Exception("Add App to Database Failed" , 20401);

            $q = "INSERT INTO `store_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (?, ?, '0')";
            if (false === DB::insert($q, [$ret, 1]))throw new Exception("bindAppTerm Failed" , 20401);
            $q = "UPDATE `store_term_taxonomy` SET `count`=`count`+1 WHERE `term_id`=?";
            if (false === ($ret = DB::update($q, [1]))) throw new Exception("addTermTaxonomyCount Failed", 20401);

            //delete
            unlink($temp_path);

            //输出
            echo json_encode(array(
                'errCode' => 2000
            ));
        } catch (\Throwable $th) {
            //throw $th;
            // print_r($th);
            echo json_encode(array(
                'errCode' => $th->getCode(),
                'errMsg' => $th->getMessage()
            ));
            exit;
        }
    }

    // JAVA应用上传操作
    public function jar()
    {
        try {
            //code...
            Loader::library('jar', 'QiniuStorage', 'storage', 'easyHttp');

            // 允许上传的文件后缀
            $allowedExts = array("jar");

            if ($_FILES["file"]["error"] > 0)
                throw new Exception("错误：{$this->uploadError[$_FILES["file"]["error"]]}<br>", 20401);

            if (!isset($_FILES['file']))
                throw new Exception("No File Uploaded.", 20401);

            $temp_path = $_FILES["file"]["tmp_name"];
            if (empty($temp_path))
                throw new Exception("File is lost.", 20401);

            // 文件MD5
            $temp_md5 = md5_file($temp_path);

            // 获取文件后缀名
            $temp = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);

            if (
                !(in_array($extension, $allowedExts))
            ) {
                throw new Exception("File format is incorrect.[{$_FILES['file']['type']}][{$extension}]", 20401);
            }

            //获取文件信息
            $jarInfo = new JarInfo($temp_path);
            $split = strripos($_FILES["file"]["name"], '.');
            $upload_file_name = str_replace('.', '', substr($_FILES["file"]["name"], 0, $split));
            if(!is_numeric($temp[0]))
                $jarInfo->setDescription($jarInfo->getDescription() . "\r\n". $upload_file_name);
            // echo $jarInfo->getDescription();
            // exit;
            // 构造存储路径
            $file_name = "jarApp/{$jarInfo->getName()}_{$temp_md5}.jar";
            $file_path = str_replace(' ', '', $file_name);

            // 图标处理
            $iconPath = "public/assets/img/jar/" . $jarInfo->getName() . "_{$temp_md5}.png";
            $iconPath_real = "/assets/img/jar/" . $jarInfo->getName() . "_{$temp_md5}.png";
            $jarInfo->getIcon($iconPath);

            // 构造数据表对应内容
            $tableInfo = array(
                'name' => $jarInfo->getName(),
                'version' => $jarInfo->getVersion(),
                'author' => $jarInfo->getVendor(),
                'icon' => $iconPath_real,
                'description' => $jarInfo->getDescription(),
                'md5' => $temp_md5,
                'addTime' => time(),
                'file_path' => $file_path,
                'size' => $_FILES["file"]["size"]
            );

            $sql = "SELECT `id` FROM `store_app` WHERE `md5` LIKE ?";
            if (DB::selectOne($sql, [$temp_md5])) {
                // ===================已存在===============
                throw new Exception("File already exists.", 20401);
            }

            // ===========不存在=============
            // 上传
            list($upload_result, $msg) = STORAGE::upload($temp_path, array(
                'storage_path' => $file_path,
                'md5' => $temp_md5
            ));

            if (!$upload_result) {
                //文件上传失败
                throw new Exception("文件上传至云端失败." . json_encode($msg), 20401);
            }

            //文件上传成功，写入数据库
            $q = "INSERT INTO `store_app`";
            $q .= " (`" . implode("`,`", array_keys($tableInfo)) . "`) ";
            $q .= " VALUES ('" . implode("','", array_values($tableInfo)) . "') ";
            if (false === ($ret = DB::insert($q)))throw new Exception("Add App to Database Failed" , 20401);

            $q = "INSERT INTO `store_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES ('?', '?', '0')";
            if (false === DB::insert($q, [$ret, 2]))throw new Exception("bindAppTerm Failed" , 20401);
            $q = "UPDATE `store_term_taxonomy` SET `count`=`count`+1 WHERE `term_id`=?";
            if (false === ($ret = DB::update($q, [2]))) throw new Exception("addTermTaxonomyCount Failed", 20401);

            //输出
            echo json_encode(['errCode' => 2000]);
        } catch (\Throwable $th) {
            //throw $th;
            echo json_encode(array(
                'errCode' => $th->getCode(),
                'errMsg' => $th->getMessage()
            ));
            exit;
        }
    }
}
