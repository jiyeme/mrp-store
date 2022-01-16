<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'MRP下载中心' }} - MRP下载中心 - 怀旧</title>
    <meta name="keywords" content="{{ $title ?? 'MRP下载中心' }},mrp,mrp下载,冒泡,斯凯,游戏,软件" />
    <meta name="description" content="MRP下载中心,提供MRP软游下载" />
    <link rel="shortcut icon" href="/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#48ae4c">
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css?v19012312" />
    <link rel="stylesheet" href="/assets/css/weui.extra.css?v=20011602">
    <link rel="stylesheet" href="/assets/css/homepage.weui.extra.css?v=1903143">
    <link rel="stylesheet" href="/assets/css/weui/1.1.2/weui.min.css">
    <script src="//upcdn.b0.upaiyun.com/libs/jquery/jquery-2.0.3.min.js"></script>
    <!--灰色界面-->
    <style type="text/css">
        html.gray {
            -webkit-filter: grayscale(1)
        }

        * {
            margin: 0;
            padding: 0;
        }

        .alert_windows {
            display: none;
            position: absolute;
            z-index: 10;
            width: 40%;
            height: 300px;
            background: #566F93;
        }

        .alert_windows span {
            float: right;
            width: 30px;
            height: 30px;
            text-align: center;
            font: 15px/30px Microsoft Yahei;
            cursor: pointer;
            color: #333;
            background: lightblue;
        }

        .alert_windows span:hover {
            color: #EEE;
            background: red;
        }
    </style>
    <script type="text/javascript" src="https://cdn.staticfile.org/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

    <style>
        .sub-menu li {
            visibility: hidden;
            height: 40px !important;

        }

        .sub-menu {
            text-align: center;
            list-style-type: none;
        }

        .nav>li {
            float: left;
        }
    </style>
</head>

<body>
    <!--    <div class="alert_windows">-->
    <!--	    <span>X</span>-->
    <!--	    <p style="font-size: x-large;">应国家号召，MRP下载中心将于2020年4月3日起，全站灰色调默哀至-->
    <!--4月4日23:59:59</p>-->
    <!--</div>-->
    <div class="warpper">
        <div class="header_nav">
            <div id="header">
                <div id="header-content">
                    <a href="/">
                        <span id="header-logo" style="display: flex;justify-content: left;align-items: center;">
                            <img style="height: 35px;margin-right: 10px;" src="/assets/img/header-logo.png" alt="">
                            <span>MRP下载中心</span>
                        </span>
                    </a>
                    <ul class="nav">
                        <li id="navbar-homepage"><a href="/">首页</a></li>
                        <li id="sub-menu"><a href="/App/list">应用列表</a></li>
                        {{-- <li>
                            <ul id="sub-menu" class="sub-menu">
                                <li id="navbar-cat" style="visibility:visible"><a href="#">分类</a></li>
                                <li id="navbar-mrpapp"><a href="/Store/App/list/slug/MRPAPP">MRP软游</a></li>
                                <li id="navbar-jarapp"><a href="/Store/App/list/slug/JAVAAPP">Java软游</a></li>
                                <li id="navbar-help"><a href="https://www.jysafe.cn/4132.air" target="_blank">帮助</a></li>
                            </ul>
                        </li> --}}
                        <li id="navbar-upload"><a href="/upload">上传</a></li>
                        <li id="navbar-support"><a href="/support">支持赞助</a></li>
                        <li><a href="https://jq.qq.com/?_wv=1027&k=vGaiy4Hg">加群反馈问题</a></li>
                        <!-- <li id="navbar-contact"><a href="/about/contact.html">联系酷安</a></li>
                        <li id="navbar-about"><a href="/about/about.html">关于酷安</a></li> -->
                    </ul>
                    <div class="navbar-right form-group">
                        <form action="/search">
                            <span style="padding: 10px;"><input id="search" type="search" class="form-control" name="name" style="padding-left: 30px;" placeholder="应用搜索" value="<?php echo isset($search_name)?$search_name:''; ?>">
                                <img style="width: 14px;height: 14px;position: absolute;margin-left: 10px;top: 29px;" src="/assets/img/search.png"></span>
                        </form>
                    </div>
                    <!--<a class="header-developer" href="/upload"><span>上传MRP应用</span></a>-->
                </div>
            </div>
            <script>
                var pathname = window.location.pathname;
                if (pathname.indexOf('/Store/App') !== -1) {
                    document.getElementById('sub-menu').classList.add('header-active');
                } else if (pathname.indexOf('/upload') !== -1) {
                    document.getElementById('navbar-upload').classList.add('header-active');
                } else if (pathname.indexOf('/support') !== -1) {
                    document.getElementById('navbar-support').classList.add('header-active');
                } else {
                    document.getElementById('navbar-homepage').classList.add('header-active');
                }
                // alert(pathname);
            </script>
            <div class="navbar navbar-fixed-top header" role="navigation" id="nav-outer">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed pull-left" data-toggle="collapse" data-target="#nav_right" data-target='#login' aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="/" id="logo">
                            <img id="logoimg" src="/assets/img/header-logo.png" alt="MRP下载中心" />
                            <span id="logofont">MRP下载中心</span>
                        </a>
                    </div>
                    <div class="collapse navbar-collapse navbar-left navbar-collapse-menu" id="nav_right">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="/">首页</a></li>
                            {{-- <li id="navbar-mrpapp"><a href="/Store/App/list/slug/MRPAPP">MRP软游</a></li>
                            <li id="navbar-jarapp"><a href="/Store/App/list/slug/JAVAAPP">Java软游</a></li> --}}
                            <li id="navbar-upload"><a href="/upload">上传</a></li>
                            <li id="navbar-support"><a href="/support">支持赞助</a></li>
                            <li><a href="https://jq.qq.com/?_wv=1027&k=5dTIITy">加群反馈问题</a></li>
                            <!-- <li><a href="/apk/">应用</a></li>
                            <li><a href="/game/">游戏</a></li>
                            <li><a href="/game/online/">在线游戏</a></li>
                            <li><a href="/apk/com.coolapk.market?from=navbar">酷安手机APP</a></li>
                            <li><a href="https://developer.coolapk.com/?from=navbar">开发者平台</a></li> -->
                        </ul>
                    </div>
                    <!--<div class="navbar-right" id="login">-->
                    <!--    <ul class="nav navbar-nav">-->
                    <!--        <li><a href="https://account.coolapk.com/auth/login">登录</a></li>-->
                    <!--        <li><a href="https://account.coolapk.com/auth/register">注册</a></li>-->
                    <!--    </ul>-->
                    <!--</div>-->
                    <div class="navbar-right form-group">
                        <form action="/search">
                            <input id="search" type="search" class="form-control" name="name" placeholder="应用搜索" />
                            <img src="/assets/img/search.png" />
                        </form>
                    </div>
                </div><!-- /.container -->
            </div>
        </div>
        <!--<h1 style="font-size: x-large;">服务正在维护中。。。可能会出现故障</h1>-->
