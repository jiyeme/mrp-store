<template>
  <app-layout title="上传">
    <div>
      <div class="m-10 flex justify-center">
        <!-- <div class="app_left" id="game_left">
          <div class="app_list_left">
            <div class="left_nav">
              <span class="left_nav_title">
                <p>
                  说明：
                  <br />1.支持多选上传
                  <br />2.重命名格式：显示名 _ MD5 .xxx
                  <br />3.文件根据MD5判断是否存在
                </p>
              </span>
            </div>
            <br />
            <br />
            <br />
            <br />
            <div class="alllist_app">
              <div class="flex">
                <img class="w-20" style="border-radius: 35px;" src="/assets/img/mrp-icon.png" />
                <div class="alllist_mss">
                  <p class="list_app_title">多文件上传文件：</p>
                  <p class="list_app_info">
                    <input type="file" name="file" id="file1" accept=".mrp, .jar" multiple />
                  </p>
                </div>
                <el-button @click="doUploadV3">上传</el-button>
              </div>
            </div>
            <div style="width: 94%;margin: 0 auto;margin-top: 10px;margin-bottom: 13px;">
              <span class="list_app_title">
                文件：
                <br />
                <span id="toUploaded"></span>
              </span>
            </div>

          </div>
        </div>-->

        <el-upload
          action="/api/upload2/mrp"
          multiple
          :drag="true"
          :auto-upload="false"
          ref="uploadRef"
          :on-change="fileChange"
          :before-upload="checkMd5"
          :on-success="uploadSuccess"
          :on-error="uploadError"
          :before-remove="()=>{return false}"
        >
          <template #trigger>
            <el-icon class="el-icon--upload">
              <upload-filled />
            </el-icon>
            <div class="el-upload__text">
              拖入文件或
              <em>点击上传</em>
            </div>
            <!-- <el-button type="primary">select file</el-button> -->
          </template>
          <br />
          <template #tip>
            <div class="flex">
              <div class="el-upload__tip">仅支持MRP</div>
              <el-button type="success" @click="submitUpload">上传到服务器</el-button>
            </div>
          </template>
          <template #file="scope">
            <div>
              {{ scope.file.name }} |
              <span
                :style="{color: scope.file.color ?? 'slateblue'}"
              >{{scope.file.text ?? '等待'}}</span> <span v-if="scope.file.text === '已存在'">|<el-button type="text" @click="deleteFile(scope.file)">删除</el-button></span>
            </div>
          </template>
        </el-upload>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "@vue/runtime-core";
import AppLayout from "../../Layouts/AppLayout.vue";
import { UploadFilled } from "@element-plus/icons-vue";
import SparkMD5 from "spark-md5";
import axios from "axios";

export default defineComponent({
  components: {
    AppLayout,
    UploadFilled,
  },
  data() {
    return {
      fileList: null,
    };
  },
  mounted(){
  },
  methods: {
    fileChange: function (file, fileList) {
    //   console.log(file, fileList, this.fileList);
      if (this.fileList === null) this.fileList = fileList;
    },
    submitUpload: function () {
      this.$refs.uploadRef.submit();
    },
    checkMd5: function (file) {
      // file --- this.fileList[index].raw
      // console.log(this.fileList, file)
      return this.genMd5(file).then(async (md5) => {
        const res = await axios.post("/api/upload2/md5Check", {
          type: "mrp",
          md5,
        });
        const resp = res.data;
        const targetFile = this.fileList.find((f) => f.uid === file.uid);
        if (resp.errCode === 2000) {
          console.log(resp);
          targetFile.text = "上传中";
          targetFile.color = "blue";
          return Promise.resolve();
        }
        targetFile.text = resp.errMsg;
        targetFile.color = "red";
        return Promise.reject();
      });
    },
    uploadSuccess: function (resp, file, fileList) {
      console.log(resp, file, fileList);
      if (resp.errCode === 2000) {
        file.text = "成功";
        targetFile.color = "green";
      } else {
        file.text = resp.errMsg;
        targetFile.color = "red";
      }
    },
    uploadError: function (err, file, fileList) {
      console.log(err, file, fileList);
    },
    deleteFile: function(file){
        const index = this.fileList.indexOf(file);
        this.fileList.splice(index, 1)
        // console.log(index, this.fileList)
    },
    genMd5: (file) => {
      return new Promise((resolve, reject) => {
        const fileReader = new FileReader();
        const Spark = new SparkMD5.ArrayBuffer();
        fileReader.readAsArrayBuffer(file);
        fileReader.onload = function (e) {
          Spark.append(e.target.result);
          const md5 = Spark.end();
          resolve(md5);
        };
      });
    },
  },
});
</script>

<style>
</style>
