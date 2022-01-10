@include('header')
<?php

// 管理员
if (isset($_SESSION['admin']) && $_SESSION['admin'] === 1)
    echo "<style>.alllist_app{float: left;width: 84%;}.del_btn{margin-top: 34px;}</style>";
?>
<div class="container">
    <div class="bread_nav">
        <span class="bread_nav_first">
            <a href="/">首页</a>
        </span>
        / <span class="bread_nav_second">
            <a href="javascript:void(0);">{{ $title ?? '' }}（共{{ $count ?? 0}}个）</a>
        </span>
    </div>
    <div class="app_left" id="game_left">
        <div class="app_list_left">
            <div class="left_nav">
                <span class="left_nav_title">全部应用</span>
            </div>

            <?php
            if (!isset($appList) || !is_array($appList))
                exit("异常");
            foreach ($appList as $key => $value) {
                /*
                            array (size=13)
                                'id' => string '9239' (length=4)
                                'version' => string '101' (length=3)
                                'name' => string '华容道' (length=9)
                                'author' => string 'SkyMobi' (length=7)
                                'description' => string '华容道，影子俱乐部小蟀作品' (length=39)
                                'icon' => string '30028' (length=5)
                                'file_path' => string '华容道_15b8de47e0c39e5a631f3861667443e6_hrd.mrp' (length=50)
                                'md5' => string '15b8de47e0c39e5a631f3861667443e6' (length=32)
                                'size' => string '250448' (length=6)
                                'addTime' => string '1582896657' (length=10)
                                'type' => string '' (length=0)
                            */
                echo "<a href=\"/Store/App/info/id/{$value->id}\" id=\"{$value->id}\" >
                            <div class=\"alllist_app\">
                                <div class=\"alllist_app_side\">
                                    <img class=\"alllist_img\" style=\"border-radius: 35px;\" src=\"";
                                    if(strlen($value->icon)>10)
                                    {
                                        echo file_exists("public/{$value->icon}")? $value->icon:"/assets/img/jar-icon.png";
                                    }else if (file_exists(dirname(__DIR__) . '/assets/img/app/' . strtoupper(base_convert($value->icon, 10, 32)) . '.bmp'))
                // 存在
                    echo '/assets/img/app/' . strtoupper(base_convert($value->icon, 10, 32)) . '.bmp';
                else
                // 不存在
                    echo '/assets/img/mrp-icon.png';
                echo "\"><div class=\"alllist_mss\">
                                        <p class=\"list_app_title\">{$value->name}</p>
                                        <p class=\"list_app_info\">{$value->author} &nbsp; ";
                printf('%.2fKB', ($value->size / 1024));
                echo " &nbsp;
                                            <span class=\"list_app_count\">v{$value->version} &nbsp; " . date('Y-m-d H:i:s', $value->addTime) . "更新</span>
                                        </p>
                                        <p class=\"list_app_description\">{$value->description}</p>
                                    </div>
                                    <button class=\"alllist_btn\">查看</button>
                                </div>
                            </div>
                        </a>";
                if (isset($_SESSION['admin']) && $_SESSION['admin'] === 1)
                    echo "<div><button class=\"alllist_btn del_btn\" onclick=\"deleteApp({$value->id});\" id=\"{$value->id}\">删除</button></div>";
            }
            ?>

            <!-- <a href="/apk/com.oasisfeng.greenify">
                        <div class="alllist_app">
                            <div class="alllist_app_side">
                                <img class="alllist_img" src="">
                                <div class="alllist_mss">
                                    <p class="list_app_title">Greenify绿色守护</p>
                                    <p class="list_app_info">4.6分 &nbsp; 3.9M &nbsp;
                                        <span class="list_app_count">883万次下载 &nbsp; 2019-10-11更新</span>
                                    </p>
                                    <p class="list_app_description">绿色守护绝对是称的上神器的应用，出来很久了，已经通过众基友的考验，绝对好东西，但现在的一些app太牛x了，总是不经意间就自动后台拉起，绿色要再接再厉啊。</p>
                                </div>
                                <button class="alllist_btn">查看</button>
                            </div>
                        </div>
                    </a> -->

            <div class="panel-footer ex-card-footer text-center">
                <ul class="pagination">
                    <?php
                    if ($page == 1)
                        $html = '<li class="disabled"><a href="javascript:void(0);">首页</a></li><li class="disabled"><a href="javascript:void(0);">&lt;</a></li>';
                    else
                        $html = '<li><a href="/">首页</a></li><li><a href="/Store/App/list/slug/' . $slug . '/' . ($page - 1) . '">&lt;</a></li>';

                    for ($i = 1; $i <= $total_page; $i++) {
                        if ($i != $page) {
                            if ($i > $page - 3 && $i < $page + 4)
                                $html .= '<li><a name="a' . $i . '"href="/Store/App/list/slug/' . $slug . '/' . $i . '">' . $i . '</a></li>';
                        } else {
                            $html .= '<li class="active"><a href="javascript:void(0);">' . $i . '</a></li>';
                        }
                    }
                    if ($page != $total_page) {
                        $html .= '<li><a href="/Store/App/list/slug/' . $slug . '/' . ($page + 1) . '">&gt;</a></li><li><a href="/Store/App/list/slug/' . $slug . '/' . $total_page . '">尾页</a></li>';
                    } else {
                        $html .= '<li class="disabled"><a href="/Store/App/list/slug/' . $slug . '/' . ($page + 1) . '">&gt;</a></li><li class="disabled"><a href="/Store/App/list/slug/' . $slug . '/' . $total_page . '?t=' . time() . '">尾页</a></li>';
                    }
                    echo $html;
                    ?>

                    <!--
                            <li class="active"><a name="a1" href="/apk/system/?p=1">1</a></li>
                            <li><a name="a2" href="/apk/system/?p=2">2</a></li>
                            <li><a name="a3" href="/apk/system/?p=3">3</a></li>
                            <li><a name="a4" href="/apk/system/?p=4">4</a></li>
                            <li><a name="a5" href="/apk/system/?p=5">5</a></li>
                            <li><a href="/apk/system/?p=2">&gt;</a></li>
                            <li><a href="/apk/system/?p=11">尾页</a></li> -->
                </ul>
            </div>
        </div>
    </div>
    <br><br><br>
    <div class="app_right">
        <!-- <div class="right_title">
            <span>支持赞助</span>
        </div>
        <div class="type_list">
            <p class="type_title">
                <a>赞助列表</a>
            </p>
            <p class="type_tag">
                <a>暂无</a>
            </p>
        </div>
        <div class="type_list">
            -----&gt;<a href="https://afdian.net/@mrp_center" target="_blank" style="font-size: initial;">爱发电赞助</a>
            <br>
            <img src="/assets/img/AliPay.jpg" width="100%" /><br>
            <img src="/assets/img/WeChat.png" width="100%" />
        </div> -->
        <div class="right_title">
                    <span>应用类别</span>
                </div>
                <?php
                    $tag2Name = [
                        'app_type' => '应用类型',
                        'app_resolution' => '分辨率',
                        'app_tag' => '应用标签'
                    ];
                    foreach ($tags as $key => $value) {
                        echo "<div class=\"type_list\">
                        <p class=\"type_title\">
                            <a href=\"/Store/App/list/slug/\">{$tag2Name[$key]}</a>
                        </p>
                        <p class=\"type_tag\">";
                            foreach($value as $v)
                                echo "<a href=\"/Store/App/list/slug/{$v['tag_slug']}\">{$v['tag_name']} </a>";

                        echo '</p>
                        </div>';
                    }
                ?>
                <!-- <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/system">系统工具</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/输入法">输入法 </a>
                        <a href="/apk/tag/文件管理">文件管理 </a>
                        <a href="/apk/tag/清理优化">清理优化 </a>
                        <a href="/apk/tag/安全防护">安全防护 </a>
                        <a href="/apk/tag/备份还原">备份还原 </a>
                        <a href="/apk/tag/辅助加强">辅助加强 </a>
                    </p>
                </div>
                <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/desktop">桌面插件</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/桌面">桌面 </a>
                        <a href="/apk/tag/插件">插件 </a>
                        <a href="/apk/tag/锁屏">锁屏 </a>
                    </p>
                </div>
                <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/themes">主题美化</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/壁纸">壁纸 </a>
                        <a href="/apk/tag/图标">图标 </a>
                        <a href="/apk/tag/字体">字体 </a>
                        <a href="/apk/tag/Substratum主题">Substratum主题 </a>
                        <a href="/apk/tag/xperia主题">xperia主题 </a>
                        <a href="/apk/tag/emui主题">emui主题 </a>
                        <a href="/apk/tag/开机动画">开机动画 </a>
                    </p>
                </div>
                <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/sns">社交聊天</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/聊天">聊天 </a>
                        <a href="/apk/tag/微博">微博 </a>
                        <a href="/apk/tag/交友">交友 </a>
                        <a href="/apk/tag/论坛">论坛 </a>
                        <a href="/apk/tag/表情">表情 </a>
                    </p>
                </div>
                <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/news">资讯阅读</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/阅读器">阅读器 </a>
                        <a href="/apk/tag/新闻">新闻 </a>
                        <a href="/apk/tag/漫画">漫画 </a>
                        <a href="/apk/tag/小说">小说 </a>
                        <a href="/apk/tag/科普">科普 </a>
                    </p>
                </div>
                <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/network">通讯网络</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/拨号">拨号 </a>
                        <a href="/apk/tag/短信">短信 </a>
                        <a href="/apk/tag/浏览器">浏览器 </a>
                        <a href="/apk/tag/下载">下载 </a>
                        <a href="/apk/tag/流量">流量 </a>
                        <a href="/apk/tag/通讯录">通讯录 </a>
                        <a href="/apk/tag/邮箱">邮箱 </a>
                        <a href="/apk/tag/运营商">运营商 </a>
                        <a href="/apk/tag/通知">通知 </a>
                        <a href="/apk/tag/路由">路由 </a>
                        <a href="/apk/tag/录音">录音 </a>
                        <a href="/apk/tag/WIFI">WIFI </a>
                    </p>
                </div>
                <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/media">影音娱乐</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/视频">视频 </a>
                        <a href="/apk/tag/音乐">音乐 </a>
                        <a href="/apk/tag/电台">电台 </a>
                        <a href="/apk/tag/铃音">铃音 </a>
                        <a href="/apk/tag/播放器">播放器 </a>
                        <a href="/apk/tag/直播">直播 </a>
                    </p>
                </div>
                <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/photography">摄影图片</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/拍照">拍照 </a>
                        <a href="/apk/tag/美图">美图 </a>
                        <a href="/apk/tag/图库">图库 </a>
                    </p>
                </div>
                <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/life">生活服务</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/天气">天气 </a>
                        <a href="/apk/tag/美食">美食 </a>
                        <a href="/apk/tag/快递">快递 </a>
                        <a href="/apk/tag/日历">日历 </a>
                    </p>
                </div>
                <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/tools">实用工具</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/计算器">计算器 </a>
                        <a href="/apk/tag/测量">测量 </a>
                        <a href="/apk/tag/酷友作品">酷友作品 </a>
                        <a href="/apk/tag/刷机">刷机 </a>
                    </p>
                </div>
                <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/business">文档商务</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/office">office </a>
                        <a href="/apk/tag/笔记">笔记 </a>
                    </p>
                </div>
                <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/finance">金融财经</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/理财">理财 </a>
                        <a href="/apk/tag/银行">银行 </a>
                        <a href="/apk/tag/股票">股票 </a>
                        <a href="/apk/tag/记账">记账 </a>
                    </p>
                </div>
                <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/sport">运动健康</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/运动">运动 </a>
                        <a href="/apk/tag/医疗">医疗 </a>
                        <a href="/apk/tag/健身">健身 </a>
                    </p>
                </div>
                <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/education">学习教育</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/词典">词典 </a>
                        <a href="/apk/tag/课程表">课程表 </a>
                        <a href="/apk/tag/考试">考试 </a>
                        <a href="/apk/tag/外语">外语 </a>
                        <a href="/apk/tag/儿童">儿童 </a>
                        <a href="/apk/tag/题库">题库 </a>
                        <a href="/apk/tag/大学">大学 </a>
                    </p>
                </div>
                <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/trave">旅行交通</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/导航">导航 </a>
                        <a href="/apk/tag/地图">地图 </a>
                        <a href="/apk/tag/交通">交通 </a>
                        <a href="/apk/tag/旅游">旅游 </a>
                        <a href="/apk/tag/酒店">酒店 </a>
                        <a href="/apk/tag/打车">打车 </a>
                        <a href="/apk/tag/公交">公交 </a>
                        <a href="/apk/tag/火车">火车 </a>
                        <a href="/apk/tag/飞机">飞机 </a>
                    </p>
                </div>
                <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/shopping">购物</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/电商">电商 </a>
                        <a href="/apk/tag/团购">团购 </a>
                        <a href="/apk/tag/导购">导购 </a>
                        <a href="/apk/tag/海淘">海淘 </a>
                        <a href="/apk/tag/购物">购物 </a>
                    </p>
                </div>
                <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/xposed">Xposed模块</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/xposed">xposed </a>
                    </p>
                </div>
                <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/vr">VR</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/VR视频">VR视频 </a>
                        <a href="/apk/tag/VR游戏">VR游戏 </a>
                    </p>
                </div>
                <div class="type_list">
                    <p class="type_title">
                        <a href="/apk/other">其他</a>
                    </p>
                    <p class="type_tag">
                        <a href="/apk/tag/其他">其他 </a>
                    </p>
                </div> -->
    </div>
</div>
<div class="container">

</div>

</div>
<a href="javascript:void(0);" onclick="onDownloadApk(0);">
    <div class="under">
        <img src="/assets/img/under_logo.png" class="under_logo">
        <div class="under_text">
            <p class="under_title">mrpoid</p>
            <p class="under_info">发现冒泡的乐趣</p>
        </div>
        <button class="under_btn">下载mrpoid2手机模拟器</button>
    </div>
</a>

<?php
if (isset($_SESSION['admin']) && $_SESSION['admin'] === 1) {
?>
    <script>
        function deleteApp(id){
            $.ajax({
                url: '/api/action/delete',
                type: 'post',
                dataType: 'json',
                data: {
                    'delete': id
                },
                cache: false,
                success: function(data) {
                    if(data.code == 200)
                    {
                        console.log('success');
                        document.getElementById(id).remove();
                        document.getElementById(id).remove();
                    }else
                    {
                        console.log(data.msg);
                    }
                }
            })
        }
    </script>
<?php
}

@include('footer')
