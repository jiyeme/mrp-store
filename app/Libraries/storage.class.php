<?php
class STORAGE{

	/**
	 *
	 *
	 * @param string $tempPath 当前文件所在临时路径
	 * @param array  $fileInfo ['storage_path'=>存储目标路径, 'md5'=>md5信息]
	 */
    public static function upload(string $tempPath, array $fileInfo){

		if(env('STORAGE_QINIU_ENABLE'))
		{
			// 七牛上传
			$ret = self::upload_qiniu($fileInfo['storage_path'], $tempPath);
			if ($ret[1] || $ret[0]['key'] != $fileInfo['storage_path'])return [false, 'SERVER[Qiniu] error'];
		}
		if(env('STORAGE_SERVER_ENABLE'))
		{
			// 服务器上传
            // host1|key1,host2|key2
			$temp = env('STORAGE_SERVER_REMOTE');
            $servers = array();
            $temp = explode(',', $temp);
            foreach($temp as $server){
                $temp = explode('|', $server);
                $servers[] = array(
                    'host' => $temp[0],
                    'key' => $temp[1]
                );
            }
			$info = array(
				'path' => $fileInfo['storage_path'],
				'tmp_path' => $tempPath,
				'md5' => $fileInfo['md5']
			);
			foreach($servers as $server){
				$upload = self::upload_server($server['host'], $server['key'], array(
					'md5' => $info['md5'],
					'path' => $info['path']
				), $info['tmp_path']);
				if(true !== $upload)return [false, "server[{$server['host']}]error" . json_encode($upload)];
			}
		}
		return [true, 'success'];
	}

    public static function upload_qiniu($file_name, $temp_path){

		// 七牛云
		$up_token = QiniuStorage::get_upload_token();
		$upload_result = QiniuStorage::qiniu_upload($up_token, $file_name, $temp_path);
		return $upload_result;
	}

	/**
	 *
	 * @param string $host 		 上传服务器域名
	 * @param string $key  		 上传服务器密钥
	 * @param array  $info     	 上传文件信息[md5, path-目标路径]
	 * @param string $temp_path  文件临时路径
	 */
    public static function upload_server($host, $key, array $info, $temp_path)
    {
        try{
            // file_name   name_md5.mrp   /tmp/***

// 			EasyHttp_Proxy::$host = "192.168.1.232";
// 			EasyHttp_Proxy::$port = "8866";
			$id = substr(self::getUniqid(), 10);
            $http = new EasyHttp();
            $response = $http->request("http://{$host}/API/upload", array(
        		'method' => 'POST',		//	GET/POST
        		'timeout' => 120,			//	超时的秒数
        		'redirection' => 0,		//	最大重定向次数
        		'httpversion' => '1.1',	//	1.0/1.1
        		'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.57',
        		'blocking' => true,		//	是否阻塞
        		'headers' => array(
        		    'X-MD5' => $info['md5'],
        		    'X-path' => $info['path'],
        		    'X-key' => $key,
        		    "Content-Type" => "multipart/form-data; boundary=----{$id}",
        		    "Cookie" => "__test=7864d351567a18e3082fde17f77ac74e"
        		    ),	//	header信息
        		'cookies' => null,	//	关联数组形式的cookie信息
        		'body' => "------{$id}\r\nContent-Disposition: form-data; name=\"file\"; filename=\"app.mrp\"\r\nContent-Type: application/octet-stream\r\n\r\n" . file_get_contents($temp_path) . "\r\n------{$id}--",
        		'compress' => false,	//	是否压缩
        		'decompress' => true,	//	是否自动解压缩结果
        		'sslverify' => true,
        		'stream' => false,
        		'filename' => null		//	如果stream = true，则必须设定一个临时文件名
        	));
            if(is_object($response))throw new Exception("Network Error.");
            // exit($response['body']);
            $ret = json_decode($response['body'], true);

            if($ret['errCode'] == 2000)
                return true;
			else
				return $ret;
        }catch(Exception $e)
        {
            return [$e->getMessage()];
        }
    }

	public static function delete(array $info){

		if($GLOBALS['config']['storage']['qiniu']['enable'])
		{
			// 七牛删除
			$ret = QiniuStorage::deleteRes($info['file_path']);
			if($ret !== true)
				return [false, '七牛云删除失败' . $ret->code() . $ret->message()];
		}
		if($GLOBALS['config']['storage']['server']['enable'])
		{
			// 服务器删除
			$servers = $GLOBALS['config']['storage']['server']['data'];

			$info = array(
				'path' => $info['file_path'],
				'md5' => $info['md5']
			);
			foreach($servers as $server){
				$delete = self::delete_server($server['host'], $server['key'], array(
					'md5' => $info['md5'],
					'path' => $info['path']
				));
				if($delete !== true)return [false, "服务器[{$server['host']}]删除失败" . json_encode($delete)];
			}
		}
		return [true, 'success'];
	}

	/**
	 *
	 * @param string $host 		 上传服务器域名
	 * @param string $key  		 上传服务器密钥
	 * @param array  $info     	 上传文件信息[md5, path-目标路径]
	 */
    public static function delete_server($host, $key, array $info)
    {
        try{
            // file_name   name_md5.mrp   /tmp/***
            $http = new EasyHttp();
            $response = $http->request("http://{$host}/API/delete", array(
        		'method' => 'POST',		//	GET/POST
        		'timeout' => 10,			//	超时的秒数
        		'redirection' => 0,		//	最大重定向次数
        		'httpversion' => '1.1',	//	1.0/1.1
        		//'user-agent' => 'USER-AGENT',
        		'blocking' => true,		//	是否阻塞
        		'headers' => array(
        		    'X-MD5' => $info['md5'],
        		    'X-path' => $info['path'],
        		    'X-key' => $key,
        		    "Cookie" => "__test=7864d351567a18e3082fde17f77ac74e"
        		    ),	//	header信息
        		'cookies' => null,	//	关联数组形式的cookie信息
        		'body' => null,
        		'compress' => false,	//	是否压缩
        		'decompress' => true,	//	是否自动解压缩结果
        		'sslverify' => true,
        		'stream' => false,
        		'filename' => null		//	如果stream = true，则必须设定一个临时文件名
        	));
            if(is_object($response))throw new Exception("Network Error");
            $ret = json_decode($response['body'], true);
            if($ret['errCode'] == 2000 || $ret['errCode'] == 2404)
                return true;
			else{
				return $ret;
			}
        }catch(Exception $e)
        {
            return false;
            $ret = array(
        	    'errCode' => 2004,
        	    'errMsg' => $e->getMessage(),
        	    );
        	echo json_encode($ret);
        }
    }

    public static function getUniqid()
    {
        return md5(uniqid(microtime(true),true));
    }
}
