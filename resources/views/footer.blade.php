<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/weui/1.1.2/weui.js"></script>
<script type="text/javascript">
    function onDownloadApk(id) {
        if (id && id != 0) {
            if({{ env('DL_PASS_STATUS')?1:0 }})
            {
                if(null === localStorage.getItem("DL_PASS"))
                {
                    swal.fire({
                        title: '输入下载密码',
                        text: "获取方法见群公告",
                        input: 'password',
                        inputAttributes: {
                            'maxlength': 10,
                            'autocapitalize': 'off',
                            'autocorrect': 'off'
                        }
                    }).then(function(password) {
                        if (password.value) {
                            getMRPDownloadLink(id, password.value);
                        }else{
                            swal.fire({
                                title: '错误！',
                                text: '密码不能为空！',
                                type: 'error',
                                confirmButtonText: '确定'
                            })
                        }
                    })
                }else{
                    getMRPDownloadLink(id, localStorage.getItem("DL_PASS"));
                }
            }else{
                getMRPDownloadLink(id, '1');
            }
        } else if (id == 0) {
            window.open('https://www.lanzous.com/i9te9ub');
        }
    }

    function getMRPDownloadLink(id, password){
        document.getElementById('download').innerText = '获取下载链接....';
        swal.fire({
            text: '获取下载链接....',
            type: 'info',
            confirmButtonText: '确定'
        })
        new Promise((resolve, reject)=>{
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config("reCAPTCHA_site_key") }}').then(function(token) {
                    console.log(token);
                    resolve(token);
                }).catch((err)=>{
                    console.log(err);
                    reject(err)
                });
            });
        }).then((token)=>{
            $.ajax({
                url: '/api/download',
                type: 'POST',
                data: {
                    "token": token,
                    "id": id,
                    "password": password
                },
                success: function(result) {
                    console.log(result);
                    var data = result;
                    if (data.code == 200) {
                        localStorage.setItem("DL_PASS", password);
                        window.location.href = data.msg;
                        swal.fire({
                            text: "开始下载",
                            type: 'info',
                            confirmButtonText: '确定'
                        })
                        document.getElementById('download').style = ""
                        document.getElementById('download').innerText = '开始下载';

                    } else {
                        if(data.code == 105)localStorage.removeItem("DL_PASS");
                        swal.fire({
                            title: '错误！',
                            text: data.msg,
                            type: 'error',
                            confirmButtonText: '确定'
                        })
                        document.getElementById('download').style = "color:red"
                        document.getElementById('download').innerText = data.msg;
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    errorMsg = "获取身份错误<br/>错误信息：" + textStatus + "错误码：" + XMLHttpRequest.status + '<br/>请重试';
                    console.log(errorMsg, '网络错误');
                    document.getElementById('download').style = "color:red"
                    document.getElementById('download').innerText = errorMsg;
                    swal.fire({
                        title: '网络错误！请重试！',
                        text: errorMsg,
                        type: 'error',
                        confirmButtonText: '确定'
                    })
                }
            });
        }).catch((err)=>{
            document.getElementById('download').style = "color:red"
            document.getElementById('download').innerText = '人机验证失败！请重试！';
            swal.fire({
                title: '人机验证失败！请重试！',
                text: err,
                type: 'error',
                confirmButtonText: '确定'
            })
        })
    }
    // 分类信息更新
    function update_tags(app_id, tagType) {
        $.ajax({
            url: '/api/action/updateTag',
            type: 'POST',
            data: {
                "term_taxonomy_id": document.getElementsByName(tagType)[0].value,
                "appId": app_id
            },
            success: function(result) {
                console.log('请求成功');
                switch (result.code) {
                    case 200:
                        alert("投票成功φ(゜▽゜*)♪");
                        break;
                    case 222:
                        console.log(result);
                        alert("投票失败╮(╯▽╰)╭");
                        break;
                    case 201:
                        alert("参数错误╮(╯▽╰)╭");
                        break;
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                errorMsg = "标签更新错误<br/>错误信息：" + textStatus + "错误码：" + XMLHttpRequest.status + '<br/>请重试';
                console.log(errorMsg, '网络错误');
            }
        });
    }
    /*灰色调*/
var colorDate = new Date();
var sadDay = [ '9-18'];
var today = (colorDate.getMonth() + 1) + '-' + colorDate.getDate();
if(sadDay.includes(today))
{
    document.getElementsByTagName("html")[0].className="gray";
//     $(function(){
//     		if($.cookie("isClose") != 'yes'){
//     			var winWid = $(window).width()/2 - $('.alert_windows').width()/2;
//     			var winHig = $(window).height()/2 - $('.alert_windows').height()/2;
//     			$(".alert_windows").css({"left":winWid,"top":-winHig*2});	//自上而下滑出
//     			$(".alert_windows").show();
//     			$(".alert_windows").animate({"left":winWid,"top":winHig},1000);
//     			$(".alert_windows span").click(function(){
//     				$(this).parent().fadeOut(500);
//     				//以天为单位
//     				//$.cookie("isClose",'yes',{ expires:1/8640});	//测试十秒
//     				$.cookie("isClose",'yes',{ expires:1});		//一天
//     			});
//     		}
// 	});
}
</script>
<!--reCaptcha-->
<script src='https://www.recaptcha.net/recaptcha/api.js?render={{ env('reCAPTCHA_site_key') }}'></script>
<!--reCaptcha-->
<div id="footer" style="margin-top: 20px;/*z-index: 10;*/">
    <div class="content">
        <div class="footer-left">
            <div class="icon-group">
                <div class="footer-logo-box">
                    <img src="/assets/img/header-logo.png" alt="">
                </div>
                <div class="footer-text-box" style="margin-left: 20px;">
                    <p class="title">MRP下载中心</p>
                    <p class="sub-title">MRP下载中心</p>
                </div>
            </div>
            <div class="footer-navbar">
                <ul>
                    <li><a class="first" href="/about/about.html">关于下载中心</a>
                    </li>
                    <li><a href="https://www.jysafe.cn/" target="_blank">祭夜の咖啡馆</a>
                    </li>
                    <li><a href="http://sohehe4.ys168.com/" target="_blank">斯凯Mrp软件[国内]</a>
                    </li>
                    <li><a class="last" href="http://a3475272270.ys168.com/" target="_blank">坏心下载站</a>
                    </li>
                    <!-- <li><a href="/about/contact.html">联系合作</a></li>
                            <li><a href="/about/jobs.html">加入我们</a></li>
                            <li><a href="/about/copyright.html">版权声明</a></li>
                            <li><a href="/apk/com.coolapk.market?from=footer">酷安APP</a></li>
                            <li><a class="last" href="https://developer.coolapk.com?from=footer">酷安开发者平台</a></li> -->
                </ul>
            </div>
            <div class="footer-info">
                <p>Copyright © 2020-2020 MRP下载中心, All Rights Reserved.</p>
                <p><a href="http://www.beian.miit.gov.cn" target="_blank" style="color: #999">闽ICP备19015813号</a>
                </p>
            </div>
        </div>
        <!-- <div class="footer-right">
                    <div class="footer-qrcode-box">
                        <img src="/qr/image?data=base64:aHR0cHM6Ly93ZWliby5jb20vdS8xNzQ2NTIzOTc0&h=514f5cf8" alt="">
                        <p>酷安微博</p>
                    </div>
                    <div class="footer-qrcode-box">
                        <img src="/qr/image?data=base64:aHR0cDovL3dlaXhpbi5xcS5jb20vci9IQ2s4SlBMRWpLLXpyYlpIOTN3Tw~~&h=5aac8427" alt="">
                        <p>酷安公众号</p>
                    </div>
                </div> -->
    </div>
</div>
<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?ba491c87bcbea3be38c097c6d7729195";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
<script>
    (function() {
        var bp = document.createElement('script');
        var curProtocol = window.location.protocol.split(':')[0];
        if (curProtocol === 'https') {
            bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
        } else {
            bp.src = 'http://push.zhanzhang.baidu.com/push.js';
        }
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(bp, s);
    })();
    (function() {
        var src = "https://jspassport.ssl.qhimg.com/11.0.1.js?d182b3f28525f2db83acfaaf6e696dba";
        document.write('<script src="' + src + '" id="sozz"><\/script>');
    })();
    $(function() {
        var OriginTitile = document.title;
        var titleTime = "";
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                document.title = '(●—●)喔哟，崩溃啦！';
                $('[rel="shortcut icon"]').attr('href', "/fail.ico");
                clearTimeout(titleTime)
            } else {
                document.title = '(/≧▽≦/)咦！又好了！';
                $('[rel="shortcut icon"]').attr('href', "/favicon.ico");
                titleTime = setTimeout(function() {
                    document.title = OriginTitile;
                    $('[rel="shortcut icon"]').attr('href', "/favicon.ico")
                }, 2000)
            }
        })
    }); /*标题栏End*/ /*控制台*/
    try {
        if (window.console && window.console.log) {
            console.log("欢迎访问MRP下载中心！");
            console.log("这个一个穷逼的网站！");
            console.log("这里提供MRP下载。。。");
            console.log("%c联系祭夜请加群", "color:red");
            console.log("%c当然也有QQ：1690127128 Email：me@jysafe.cn 支付宝：1690127128@qq.com（欢迎各种赞助）", "color:red")
        }
    } catch (e) {} /*控制台End*/
</script>
</body>

</html>
