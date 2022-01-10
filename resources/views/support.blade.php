@include('header')

<div class="container">
    <div class="bread_nav">
        <span class="bread_nav_first">
            <a href="/">首页</a>
        </span>
        / <span class="bread_nav_second">
            <a href="javascript:void(0);">支持赞助</a>
        </span>
    </div>
    <div class="app_left">
        <div class="right_title">
            <span>支持赞助</span>
        </div>
        <div class="type_list">
            <p class="type_title">
                <a>赞助列表</a>
            </p>
            <p class="type_tag">
                <p style="font-size:25px">
                    *使&nbsp;&nbsp;&nbsp;&nbsp;6.66软妹币<br />*生&nbsp;&nbsp;&nbsp;&nbsp;1软妹币<br />*取&nbsp;&nbsp;&nbsp;&nbsp;0.1软妹币<br />
                梁*s&nbsp;&nbsp;1软妹币<br />
                *棱&nbsp;&nbsp;&nbsp;&nbsp;0.5软妹币</p>
            </p>
        </div>
        <div class="type_list">
            -----&gt;<a href="https://afdian.net/@mrp_center" target="_blank" style="font-size: initial;">爱发电赞助</a>
            <br>
            <img src="/assets/img/AliPay.jpg" width="100%" /><br>
            <img src="/assets/img/WeChat.png" width="100%" />
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
                    <a href="javascript:void(0);" onclick="onDownloadApk(0);">
                         <button class="download_button"><img src="/assets/img/mrpoid2.jpg" style="border-radius: 8px;" height="35px" alt="">&nbsp;下载MRP模拟器</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@include('footer')
