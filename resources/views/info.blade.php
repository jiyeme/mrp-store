@include('header')


<div class="container">
    <div class="bread_nav">
        <span class="bread_nav_first">
            <a href="/Store/App/list/slug/{{ $term['slug'] }}">{{ $term['name'] }}</a>
        </span>
        / <span class="bread_nav_second">
            <a href="javascript:void(0);">{{ $ret->name }}</a>
        </span>
    </div>
    <div class="app_left">
        <div class="apk_left_one">
            <div class="apk_topbar">
                <img src="<?php
                            if(strlen($ret->icon)>10)
                                echo file_exists("public/{$ret->icon}")? $ret->icon:"/assets/img/jar-icon.png";
                            else if (file_exists(dirname(__DIR__) . '/assets/img/app/' . strtoupper(base_convert($ret->icon, 10, 32)) . '.bmp'))
                                echo '/assets/img/app/' . strtoupper(base_convert($ret->icon, 10, 32)) . '.bmp';
                            else
                                echo '/assets/img/mrp-icon.png';
                            ?>">
                <div class="apk_topba_appinfo">
                    <div class="apk_topbar_mss">
                        <p class="detail_app_title"><?php echo $ret->name; ?><span class="list_app_info"> v<?php echo $ret->version; ?></span></p>
                        <p class="apk_topba_message">
                            <?php printf('%.2fKB', ($ret->size / 1024)); ?> </p>
                        <a href="javascript:void(0);" onclick="onDownloadApk('<?php echo $id; ?>');">
                            <button class="apk_topbar_btn">下载应用</button><span id="download"></span>
                        </a>

                    </div>
                    <div class="apk_topba_code">
                        <img src="https://api.jysafe.cn/qrcode?qrtype=general&text=http://mrp.jysafe.cn/mrp/id/<?php echo $ret->id; ?>&logo=&size=200&level=H&bgcolor=%23ffffff&fgcolor=%23000000&pt=%23000000&inpt=%23000000&s=1">
                        <p>扫码下载该应用</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="apk_left_two">
            <div class="apk_left_two_box">
                <div class="apk_left_first-title">
                    <p class="apk_left_title_nav">帮助区</p>
                    <p class="apk_left_title_info">帮助我们进行分类</p>

                    <select name="resolution">
                        <option value="resolution" selected="selected">请选择分辨率</option>
                        <?php
                            if(isset($tags['app_resolution']))
                                foreach($tags['app_resolution'] as $value)
                                {
                                    echo "<option value=\"{$value['term_taxonomy_id']}\">{$value['tag_name']}</option>";
                                }
                        ?>
                    </select>
                    <button onclick="update_tags('<?php echo $id; ?>', 'resolution')" class="apk_topbar_btn">投票</button>
                    <br />

                    <select name="type">
                        <option value="type" selected="selected">请选择应用类型</option>
                        <?php
                            if(isset($tags['app_type']))
                                foreach($tags['app_type'] as $value)
                                {
                                    echo "<option value=\"{$value['term_taxonomy_id']}\">{$value['tag_name']}</option>";
                                }
                        ?>
                    </select>
                    <button onclick="update_tags('<?php echo $id; ?>', 'type')" class="apk_topbar_btn">投票</button>
                    <br />

                    <select name="tag">
                        <option value="tag" selected="selected">请选择标签</option>';
                        <?php
                            if(isset($tags['app_tag']))
                                foreach($tags['app_tag'] as $value)
                                {
                                    echo "<option value=\"{$value['term_taxonomy_id']}\">{$value['tag_name']}</option>";
                                }
                        ?>
                    </select>
                    <button onclick="update_tags('<?php echo $id; ?>', 'tag')" class="apk_topbar_btn">投票</button>
                    <br />

                </div>
                <!-- <div class="apk_left_title">
                            <p class="apk_left_title_nav">应用截图</p>
                            <div class="apk_left_two_img">
                                <div class="ex-screenshot-thumb-carousel">
                                    <img src="http://pp.myapp.com/ma_pic2/0/shot_52415795_1_1582949799/180" data-toggle="modal" data-target="#ex-screenshot-modal" onclick="$(&quot;#ex-screenshot-carousel&quot;).carousel(0);">
                                    <img src="http://pp.myapp.com/ma_pic2/0/shot_52415795_2_1582949799/180" data-toggle="modal" data-target="#ex-screenshot-modal" onclick="$(&quot;#ex-screenshot-carousel&quot;).carousel(1);">
                                    <img src="http://pp.myapp.com/ma_pic2/0/shot_52415795_3_1582949799/180" data-toggle="modal" data-target="#ex-screenshot-modal" onclick="$(&quot;#ex-screenshot-carousel&quot;).carousel(2);">
                                </div>
                            </div>
                            <div id="ex-screenshot-modal" class="modal fade ex-screenshot-modal" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" style="margin:0 auto;max-width: 480px;">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                                            </button>
                                            <h4 class="modal-title" style="font-size: 16px;">截图预览</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div id="ex-screenshot-carousel" class="carousel slide ex-screenshot-carousel">
                                                <ol class="carousel-indicators">
                                                    <li data-target="#ex-screenshot-carousel" data-slide-to="0" class="active"></li>
                                                    <li data-target="#ex-screenshot-carousel" data-slide-to="1" class=""></li>
                                                    <li data-target="#ex-screenshot-carousel" data-slide-to="2" class=""></li>
                                                </ol>
                                                <div class="carousel-inner">
                                                    <div class="item active"><img src="http://pp.myapp.com/ma_pic2/0/shot_52415795_1_1582949799/0"></div>
                                                    <div class="item "><img src="http://pp.myapp.com/ma_pic2/0/shot_52415795_2_1582949799/0"></div>
                                                    <div class="item "><img src="http://pp.myapp.com/ma_pic2/0/shot_52415795_3_1582949799/0"></div>
                                                </div>
                                                <a class="left carousel-control" href="#ex-screenshot-carousel" data-slide="prev">
                                                    <span class="glyphicon glyphicon-chevron-left"></span>
                                                </a>
                                                <a class="right carousel-control" href="#ex-screenshot-carousel" data-slide="next">
                                                    <span class="glyphicon glyphicon-chevron-right"></span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                <!-- <div class="apk_left_title">
                            <p class="apk_left_title_nav">新版特性</p>
                            <p class="apk_left_title_info">
                                「日常优化」<br>
                                增加满屏播放功能，优化全屏播放体验<br>
                                。 </p>
                        </div> -->
                <div class="apk_left_title">
                    <p class="apk_left_title_nav">应用简介</p>
                    <div class="apk_left_title_info"><?php echo $ret->description; ?></div>
                </div>
                <!-- <div class="apk_left_title">
                            <p class="apk_left_title_nav">应用评分</p>
                            <div class="apk_rank">
                                <p class="rank_num">3.6</p>
                                <div class="rank_star">
                                    <div class="pull-left ex-apk-rank-intro">
                                        <span class="ex-apk-rank-star"><span class="big-star big-star-4"></span></span>
                                    </div>
                                    <p class="apk_rank_p1">共28个评分</p>
                                </div>
                                <div class="rank_bar">
                                    <div class="rank_bar_one">
                                        <div class="rank_bar_onecolor" style="width: 39px">
                                        </div>
                                    </div>
                                    <div class="rank_bar_two">
                                        <div class="rank_bar_twocolor" style="width: 21px">
                                        </div>
                                    </div>
                                    <div class="rank_bar_three">
                                        <div class="rank_bar_threecolor" style="width: 0px">
                                        </div>
                                    </div>
                                    <div class="rank_bar_four">
                                        <div class="rank_bar_fourcolor" style="width: 7px">
                                        </div>
                                    </div>
                                    <div class="rank_bar_five">
                                        <div class="rank_bar_fivecolor" style="width: 32px">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                <div class="apk_left_title">
                    <p class="apk_left_title_nav">分类标签</p>
                    <p>
                        <?php
                        // foreach ($ret->tags as $key => $value) {
                        //     echo "<a href=\"/Store/App/list/slug/{$key}\"><span class=\"apk_left_span2\">{$value}</span></a>";
                        // }
                        ?>
                    </p>
                </div>
                <!--
                        <div class="apk_left_title">
                            <p class="apk_left_title_nav">详细信息</p>
                            <p class="apk_left_title_info">应用包名：com.feng.car<br>
                                更新时间：8小时前<br>
                                支持ROM：4.1+<br>
                                开发者名称：北京锋巢信息技术有限公司 </p>
                        </div>
                        <div class="apk_left_title">
                            <p class="apk_left_title_nav">权限信息</p>

                        </div>
                        <div class="apk_left_under">
                            &nbsp;
                        </div> -->
            </div>
        </div>

    </div>
    <div class="apk_right">
        <div class="apk_right_two" id="apk_right">
            <div class="download_right">
                <div class="download_logo">
                    <p class="download_title">模拟器下载</p>
                </div>
                <div class="download_img">
                    <img src="data:image/png;base64,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" alt="扫码下载MRP模拟器">
                </div>
                <div class="download_msg">
                    <p>扫码下载MRP模拟器</p>
                </div>
                <div class="download_btn">
                    <a href="javascript:onDownloadApk(0);" >
                        <button class="download_button"><img src="/assets/img/mrpoid2.jpg" style="border-radius: 8px;" height="35px" alt="">&nbsp;下载MRP模拟器</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script crossorigin="anonymous" integrity="sha384-69o/1X+1UgjbSZElLKjGpfMiS6n/XpZvgAhbsYr4PmEkzdHTAmP7x/OKrpu/QrUd" src="https://lib.baomitu.com/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>
<link crossorigin="anonymous" integrity="sha384-bboc2lLlsFOpYLPbDeFw9e76cK8dYWO2qXsl43ln9dHBYG38vwzdVGGRTGQmzl7V" href="https://lib.baomitu.com/limonte-sweetalert2/8.11.8/sweetalert2.min.css" rel="stylesheet">
<style>html{font-size:100%!important}</style>

@include('footer')
