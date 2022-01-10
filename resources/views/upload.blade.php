@include('header')
<br><br>
<div class="container">
	<div class="app_left" id="game_left">
		<div class="app_list_left">
			<div class="left_nav">
				<span class="left_nav_title">
					<p>
						说明：<br>
						1.支持多选上传<br>
						2.重命名格式：显示名 _ MD5 .xxx<br>
						3.文件根据MD5判断是否存在
					</p>
				</span>
			</div>
			<br><br><br><br>
			<div class="alllist_app">
				<div class="alllist_app_side">
					<img class="alllist_img" style="border-radius: 35px;" src="/assets/img/mrp-icon.png">
					<div class="alllist_mss">
						<p class="list_app_title">多文件上传文件： </p>
						<p class="list_app_info">
							<input type="file" name="file" id="file1" accept=".mrp,.jar" multiple />
						</p>
					</div>
					<button class="alllist_btn" onclick="doUploadV3()">上传</button>
					<!--<button class="alllist_btn" onclick="javascript:alert('已停止！！！')">上传</button>-->
				</div>
			</div>
			<div style="width: 94%;margin: 0 auto;margin-top: 10px;margin-bottom: 13px;">
				<span class="list_app_title">文件：<br /><span id="toUploaded"></span></span>
			</div>

			<!--
    	<form action="upload_mrp.php" method="post" enctype="multipart/form-data">
    		<label for="file">文件名：</label><br />
    		<input type="file" name="file[]" multiple="" /><br />
    		<input type="submit" name="submit" value="提交" />
    	</form>
    	-->
			<!--    	<form id="uploadForm">-->
			<!--    		<p>文件：<br /><span id="toUploaded"></span>-->
			<!--    			<p>多文件上传文件： <input type="file" name="file" id="file1" accept=".mrp" multiple /></p>-->
			<!--    			<input type="button" value="上传" onclick="doUpload()" />-->
			<!--    	</form>-->
		</div>
	</div>
</div>
<!-- 引入jquery -->
<!--<script src="//cdn.staticfile.org/jquery/3.4.1/jquery.js"></script>-->
<!-- 引入spark-MD5.js计算文件MD5值 -->
<script src="//cdn.staticfile.org/spark-md5/3.0.0/spark-md5.min.js" type="text/javascript"></script>
<script>
	var files = null;

	$(function() {
		$('input[name="file"]').change(function(event) {
			if ($(this).attr('multiple') == 'multiple') { //多文件上传
				var filePaths = $(this)[0].files;
				$('.file_multiple').remove(); //重选时，删掉原来已选的
				for (var i = 0; i < filePaths.length; i++) {
					if (filePaths[i].name) {
						$('#toUploaded').append(`<span class='file_multiple'>${filePaths[i].name} - ${(filePaths[i].size/1024).toFixed(2)}KB</span><span class='file_multiple' id='file_${i}'>|等待<br /></span>`);
					}
				}
			} else { //单文件上传
				var val = $(this).val();
				if (val) {
					var id = $(this).attr('id');
					$('.' + id).remove(); //重选时，删掉原来已选的
					$('#toUploaded').append("<span class='" + id + "'>" + val.substr(val.lastIndexOf('\\') + 1) + "</span><span class='" + id + "' id='file_" + i + "'>等待</span>");
				}
			}
		});
	})

	// =====================
	function doUploadV3() {
		var i = 0;
		files = $("#file1")[0].files;
		console.log(files)
		var fileCount = files.length;
		//使用循环实现多文件逐个上传
		var loop = function() {
			if (i >= fileCount) //退出循环
				return;
			console.log("123")

			upload(i).finally(() => {
				i++;
				window.setTimeout(loop, 500);
			});
		}
		window.setTimeout(loop, 500);
	}
	//上传进度回调函数
	function resultProgress(e) {
		if (e.lengthComputable) {
			var percent = e.loaded / e.total * 100;
			$("#uploading").html(percent.toFixed(2) + "%");
			var percentStr = String(percent);
			if (percentStr == "100") {
				$("#uploading").html("上传已完成，正在等待服务器响应结果。");
			}
		}
	}
	function upload(i){
		var content = "";

		content = "|<span style=\"color:#63d868;\">计算MD5...</span><br />";
		document.getElementById("file_" + i).innerHTML = content;
		// 计算MD5
		return getFileMd5V3(i).then(md5 => {
			// 检查MD5
			content = "|<span style=\"color:green;\">检测MD5...</span><br />";
			document.getElementById("file_" + i).innerHTML = content;
			return checkFileMd5V3(i, md5);
		}).then(ret => {
			// 上传文件
			content = "|<span id=\"uploading\" style=\"color:#0066ff;\">上传中...</span><br />";
			document.getElementById("file_" + i).innerHTML = content;
			return uploadFileV3(i);
		}).then((data) => {
			content = "|<span style=\"color:blue;\">成功</span><br />";
			document.getElementById("file_" + i).innerHTML = content;
			//下一步循环
			// console.log('next loop');
		}).catch((err) => {
			console.log(err)
			content = "|<span style=\"color:red;\">失败|";
			content += "原因：" + err.errMsg + "</span><br />";
			document.getElementById("file_" + i).innerHTML = content;
			if(err.errCode != 2101){
				$("#file_" + i).attr({onclick: `upload(${i})`, title: '点击重试'})
				$("#file_" + i).css({cursor: 'pointer'})
			}
		})
	}
	function uploadFileV3(i) {
		return new Promise((resolve, reject) => {
			var formData = new FormData();
			formData.append("file", files[i]);

			var suffix = files[i].name.substr(files[i].name.lastIndexOf('.') + 1);

			$.ajax({
				url: `/api/upload/${suffix}`,
				type: 'post',
				data: formData,
				cache: false,
				processData: false,
				contentType: false,
                dataType: 'json',
				async: true,
				enctype: 'multipart/form-data',
				xhr: function() {
					//获取ajax中的ajaxSettings的xhr对象  为他的upload属性绑定progress事件的处理函数
					var myXhr = $.ajaxSettings.xhr();
					if (myXhr.upload) {
						//检查其属性upload是否存在
						myXhr.upload.addEventListener("progress", resultProgress, false);
					}
					return myXhr;
				},
				success: (data) => {
					// if (data.upload_res.status != 'Success') {
					// 	console.log(data.upload_res + 'data:' + JSON.stringify(data.mrp_info));
					// }
					if (data.errCode !== 2000)
						reject(data);
					else
						resolve(data);
				},
				error: err => {
					reject({
						errMsg: err.statusText
					})
				}
			})
		})
	}

	function checkFileMd5V3(i, md5) {
		return new Promise((resolve, reject) => {
			console.log(i, '--md5-->', md5);
			var suffix = files[i].name.substr(files[i].name.lastIndexOf('.') + 1);

			// check MD5
			$.ajax({
				url: '/api/upload/md5Check',
				type: 'post',
				dataType: 'json',
				data: {
					md5: md5,
					type: suffix
				},
				async: true,
				cache: false,
				timeout: 10 * 1000,
				success: function(data) {
					if (data.errCode !== 2000) {
						// MD5已存在
						reject(data);
					} else {
						//MD5检查通过，进行上传操作
						resolve(true);
					}
				},
				error: err => {
					console.log(err)
					reject({
						errCode: 20503,
						errMsg: err.statusText
					})
				}
			})
		})
	}

	function getFileMd5V3(i) {
		return new Promise((resolve, reject) => {
			// 获取文件
			var file = files[i];
			// 创建文件读取对象，此对象允许Web应用程序异步读取存储在用户计算机上的文件内容
			var fileReader = new FileReader();
			// 根据浏览器获取文件分割方法
			var blobSlice = File.prototype.mozSlice || File.prototype.webkitSlice || File.prototype.slice;
			// 指定文件分块大小(2M)
			var chunkSize = 2 * 1024 * 1024;
			// 计算文件分块总数
			var chunks = Math.ceil(file.size / chunkSize);
			// 指定当前块指针
			var currentChunk = 0;

			// 创建MD5计算对象
			var spark = new SparkMD5.ArrayBuffer();

			// 记录开始时间
			// var startTime = new Date().getTime();

			// FileReader分片式读取文件
			loadNext();

			// 获取输出信息区域
			// var showInfo = $(".showInfo");
			// showInfo.html('');

			// 当读取操作成功完成时调用
			fileReader.onload = function() {
				// 输出加载信息
				// showInfo.append('读取文件： <strong>' + (currentChunk + 1) + '</strong> / <strong>' + chunks + ' ...</strong><br/>');

				// 将文件内容追加至spark中
				spark.append(this.result);
				currentChunk += 1;

				// 判断文件是否都已经读取完
				if (currentChunk < chunks) {
					loadNext();
				} else {
					// 计算spack中内容的MD5值,并返回
					// showInfo.append('<br/>MD5值为： <strong><font color="green">' + spark.end() + '</font></strong><br/>');
					// showInfo.append('计算时长 ： <strong><font color="green">' + (new Date().getTime() - startTime) + '</font></strong> 毫秒！<br/>');
					// console.log('aaaaa' + spark.end());
					// console.log('bbbbb' + spark.end());
					var md5 = spark.end();
					//console.log('文件' + i + ':md5=>' + md5);
					//console.log('文件' + i + 'checking md5');
					resolve(md5);
				}
			};

			// FileReader分片式读取文件
			function loadNext() {
				// 计算开始读取的位置
				var start = currentChunk * chunkSize;
				// 计算结束读取的位置
				var end = start + chunkSize >= file.size ? file.size : start + chunkSize;
				fileReader.readAsArrayBuffer(blobSlice.call(file, start, end));
			}
		})
	}
</script>
@include('footer')
