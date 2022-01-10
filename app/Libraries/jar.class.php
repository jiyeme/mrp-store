<?php
/////////////////////////////////////
// Класс для получения инфы из jar //
//        从jar获取信息的类         //
/////////////////////////////////////
require 'PclZip/pclzip.lib.php';

class JarInfo
{
    private $mimetype;
    private $manifest = array();
    private $info = array();
    private $file;

    // Констуктор...
    // 构造函数...
    public function __construct($file)
    {
        // $file - путь до файла.
        // $ file是文件的路径。
        is_file($file) or die("文件: $file 不存在.");

        $archive = new PclZip($file);
        $this->file = $file;
        $this->manifest = $archive->extract(PCLZIP_OPT_BY_NAME, 'META-INF/MANIFEST.MF', PCLZIP_OPT_EXTRACT_AS_STRING);
        $this->parse();
    }

    // Разбираем манифест
    private function parse()
    {

        $man = explode("\n", trim($this->manifest[0]['content']));

        // 处理简介有换行的情况
        foreach($man as $k => $v){
            if(false === strpos($v, ':')){
                $man[$k - 1] .= trim($v);
                unset($man[$k]);
            }
        }

        foreach ($man as $mani) {
            $ar = explode(': ', trim($mani));
            $this->info[$ar[0]] = $ar[1];
        }
    }

    // Версия
    // 版本
    public function getVersion()
    {
        if (isset($this->info['MIDlet-Version']))
            return $this->info['MIDlet-Version'];
        else
            return false;
    }

    // Имя
    // 名称
    public function getName()
    {
        if (isset($this->info['MIDlet-Name']))
            return $this->info['MIDlet-Name'];
        else
            return false;
    }

    // Производитель
    // 制造商
    public function getVendor()
    {
        if (isset($this->info['MIDlet-Vendor']))
            return $this->info['MIDlet-Vendor'];
        else
            return false;
    }

    // Профиль
    // ??
    public function getProfile()
    {
        if (isset($this->info['MicroEdition-Profile']))
            return $this->info['MicroEdition-Profile'];
        else
            return false;
    }

    // 简介
    public function getDescription()
    {
        if (isset($this->info['MIDlet-Description']))
            return $this->info['MIDlet-Description'];
        else
            return false;
    }

    // URL
    public function getUrl()
    {
        if (isset($this->info['MIDlet-Info-URL']))
            return $this->info['MIDlet-Info-URL'];
        else
            return false;
    }

    // Получить иконку
    // 获取图标
    public function getIcon($save_as)
    {
        $icon = trim(substr($this->info['MIDlet-1'], $pos = strpos($this->info['MIDlet-1'], ',') + 1, strrpos($this->info['MIDlet-1'], ',') - $pos));
        $icon = preg_replace('#^/#', '', $icon);
        if(0 === strlen($icon))
            $icon = isset($this->info['MIDlet-Icon'])?$this->info['MIDlet-Icon']:$icon;

        $archive = new PclZip($this->file);
        $list = $archive->extract(PCLZIP_OPT_BY_NAME, $icon, PCLZIP_OPT_EXTRACT_AS_STRING);
        if (pathinfo($icon, PATHINFO_EXTENSION) == 'png' && $list[0]['content'] !== '' && @$image = imagecreatefromstring($list[0]['content'])) {
            $width = imagesx($image);
            $height = imagesy($image);
            $x_ratio = 16 / $width;
            $y_ratio = 16 / $height;
            if (($width <= 16) && ($height <= 16)) {
                $tn_width = $width;
                $tn_height = $height;
            } elseif (($x_ratio * $height) < 16) {
                $tn_height = ceil($x_ratio * $height);
                $tn_width = 16;
            } else {
                $tn_width = ceil($y_ratio * $width);
                $tn_height = 16;
            }
            $image_two = ImageCreate($tn_width, $tn_height);
            imagecopyresampled($image_two, $image, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
            imagepng($image_two, $save_as);
            imagedestroy($image);
            return true;
        } else {
            return false;
        }
    }

    // Получить JAD
    // 获取JAD
    public function getJad($url)
    {
        $siz = filesize($this->file);
        $jad = str_ireplace('.jar', '.jad', $this->file);
        $f = fopen($jad, 'w+');
        fputs($f, $this->manifest[0]['content'] . "\n" . 'MIDlet-Jar-Size: ' . $siz . "\n" . 'MIDlet-Jar-URL: ' . $url);
        fclose($f);
    }

    // Установка мини описания
    // 设置迷你说明
    public function setDescription($value)
    {
        $this->info['MIDlet-Description'] = $value;
    }

    // Установка инфы при удалении
    // 设置卸载时的信息
    public function setDeleteConfirm($value)
    {
        $this->info['MIDlet-Delete-Confirm'] = $value;
    }

    // Установка имени
    // 设置名称
    public function setName($value)
    {
        $this->info['MIDlet-Name'] = $value;
    }

    // Установка URL
    // 设定网址
    public function setUrl($value)
    {
        $this->info['MIDlet-Info-URL'] = $value;
    }

    // Сохранение манифеста и упаковка в приложение
    // 保存清单并将其打包到应用程序中
    public function saveManifest()
    {
        //TODO:Попытаться разобраться почему ява получается хреновой после перепаковки
        // 尝试弄清楚为什么重新打包后Java变得很糟糕
        $man_string = '';
        foreach ($this->info as $key => $val) {
            $man_string = $man_string . $key . ': ' . $val . '
';
        }
        $man_string = iconv("UTF-8", "windows-1251", $man_string);

        //$zip = new ZipArchive;
        //$zip->open($this->file) or die('无法打开该应用程序！');
        //$zip->addFile('java/META-INF/MANIFEST.MF', 'META-INF/MANIFEST.MF');
        //$zip->close();

        //$archive = new PclZip($this->file);
        //$archive->extract(PCLZIP_OPT_PATH, 'java') or die ('无法解压应用程序！');

        //$manfile = fopen('java/META-INF/MANIFEST.MF', "w") or die ('无法打开清单！');
        //fwrite($manfile, $man_string);
        //fclose($manfile);

        //$archive->create('java', PCLZIP_OPT_REMOVE_PATH, 'java');
        //unlink('cache/META-INF/MANIFEST.MF');

    }
}
