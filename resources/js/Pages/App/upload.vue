<template>
  <app-layout title="上传">
    <div>
      <div class="text-lg bg-white px-8">
        <el-tabs v-model="type">
          <el-tab-pane label="MRP应用" name="mrp"></el-tab-pane>
          <el-tab-pane v-if="$page.props.user" label="MRP资源" name="mrpres"></el-tab-pane>
        </el-tabs>
      </div>
      <div class="m-10 flex justify-center">
        <el-upload
          :action="`/api/upload2/${type}`"
          multiple
          :drag="true"
          :auto-upload="false"
          ref="uploadRef"
          :on-change="fileChange"
          :before-upload="checkMd5"
          :on-success="uploadSuccess"
          :on-error="uploadError"
          :before-remove="()=>{return false}"
          :with-credentials="true"
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
              >{{scope.file.text ?? '等待'}}</span>
              <span v-if="scope.file.text === '已存在'">
                |
                <el-button type="text" @click="deleteFile(scope.file)">删除</el-button>
              </span>
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
      type: "mrp",
    };
  },
  mounted() {},
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
      const targetFile = this.fileList.find((f) => f.uid === file.uid);
      targetFile.text = "检查MD5";
      return this.genMd5(file).then(async (md5) => {
        const res = await axios.post("/api/upload2/md5Check", {
          type: this.type,
          md5,
        });
        const resp = res.data;
        if (resp.errCode === 2000) {
          console.log(resp);
          targetFile.text = "上传中";
          targetFile.color = "blue";
          return Promise.resolve();
        }
        targetFile.text = resp.errMsg;
        targetFile.color = "red";
        targetFile.status = "ready";
        return Promise.reject();
      });
    },
    uploadSuccess: function (resp, file, fileList) {
      console.log('success', resp, file, fileList);
      if (resp.errCode === 2000) {
        file.text = "成功";
        file.color = "green";
      } else {
        file.text = resp.errMsg;
        file.color = "red";
      }
    },
    uploadError: function (err, file, fileList) {
      console.log('error', err, file, fileList);
    },
    deleteFile: function (file) {
      const index = this.fileList.indexOf(file);
      this.fileList.splice(index, 1);
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
