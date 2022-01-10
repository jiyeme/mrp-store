<?php
class Loader
{
    // Load library classes
    public static function library()
    {
        foreach(func_get_args() as $v)
            include_once app_path('Libraries') . "/{$v}.class.php";
    }

    // loader helper functions. Naming conversion is xxx_helper.php;
    public static function helper($helper)
    {
        include_once app_path('Helpers') . "/{$helper}_helper.php";
    }
}
