@include('header')

<div class="container">
    <div class="bread_nav">
        <span class="bread_nav_first">
            <a href="javascript:void(0);">首页</a>
        </span>
    </div>
    <div class="app_left" id="game_left">
        <div class="app_list_left">
            <div class="left_nav">
                <span class="left_nav_title">全部目录</span>
            </div>
            <!--<a href="#">-->
            <!--    <div class="alllist_app">-->
            <!--        <div class="alllist_app_side">-->
            <!--            <img class="alllist_img" src="/assets/img/mrp-icon.png">-->
            <!--            <div class="alllist_mss">-->
            <!--                <p class="list_app_title">本站遭遇攻击[IP:101.200.58.15]</p>-->
            <!--                <p class="list_app_info">绝了 &nbsp; 本站遭遇攻击[IP:101.200.58.15] &nbsp;-->
            <!--                    <span class="list_app_count">累计109.84K次 &nbsp; </span>-->
            <!--                </p>-->
            <!--                <p class="list_app_description">本站遭遇攻击[IP:101.200.58.15]。</p>-->
            <!--            </div>-->
            <!--            <button class="alllist_btn">绝了</button>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</a>-->
            <a href="/Store/App/list/slug/MRPAPP">
                <div class="alllist_app">
                    <div class="alllist_app_side">
                        <img class="alllist_img" src="/assets/img/mrp-icon.png">
                        <div class="alllist_mss">
                            <p class="list_app_title">MRP软游</p>
                            <p class="list_app_info">请不要使用爬虫，谢谢 &nbsp;
                                <span class="list_app_count">提供MRP软件以及游戏的下载。</span>
                            </p>
                            <p class="list_app_description">请不要使用爬虫，谢谢</p>
                        </div>
                        <button class="alllist_btn">进入</button>
                    </div>
                </div>
            </a>
            <a href="/Store/App/list/slug/JAVAAPP">
                <div class="alllist_app">
                    <div class="alllist_app_side">
                        <img class="alllist_img" src="/assets/img/jar-icon.png">
                        <div class="alllist_mss">
                            <p class="list_app_title">Java软游</p>
                            <p class="list_app_info">提供Java软件以及游戏的下载。 &nbsp;
                                <span class="list_app_count">提供Java软件以及游戏的下载。</span>
                            </p>
                            <p class="list_app_description">提供Java资源的下载。</p>
                        </div>
                        <button class="alllist_btn">进入</button>
                    </div>
                </div>
            </a>

            <div class="panel-footer ex-card-footer text-center">
                <ul class="pagination">
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
        <div class="right_title">
            <span>支持赞助</span>
        </div>
        <div class="type_list">
            <p class="type_title">
                <a>赞助列表</a>
            </p>
            <p class="type_tag">
                <p style="font-size:25px">
                    *生&nbsp;&nbsp;&nbsp;&nbsp;1软妹币<br />*取&nbsp;&nbsp;&nbsp;&nbsp;0.1软妹币<br />
                梁*s&nbsp;&nbsp;1软妹币<br />
                *棱&nbsp;&nbsp;&nbsp;&nbsp;0.5软妹币</p>
            </p>
        </div>
        <div class="type_list">
            -----&gt;<a href="https://afdian.net/@mrp_center" target="_blank" style="font-size: initial;">爱发电赞助</a>
            <br>
            <!--<img src="/assets/img/AliPay.jpg" width="100%" /><br>
            <img src="/assets/img/WeChat.png" width="100%" />-->
        </div>
        <!-- <div class="right_title">
            <span>应用分类</span>
        </div>
        <div class="type_list">
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
@include('footer')
