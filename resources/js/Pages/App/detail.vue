<template>
  <app-layout title="MRP列表">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">应用详情{{ title }}</h2>
    </template>

    <div class="py-12">
      <div class="p-4 ml-4 mr-4">
        <div class="flex flex-col sm:flex-row justify-between">
          <div class="flex-col sm:w-3/5">
            <el-card>
              <el-row class="flex-col sm:flex-row justify-center items-center">
                <el-col :span="5">
                  <el-image :src="icon"></el-image>
                </el-col>
                <el-col :span="14">
                  <span class="text-3xl">{{ appInfo.name }}</span>
                </el-col>
                <el-col :span="5">二维码</el-col>
              </el-row>
            </el-card>
            <el-card class="mt-4">
              <h3>应用简介</h3>
              {{ appInfo.description }}
              <br />
              <br />
              <h3>版本列表</h3>
              <div v-for="ver in verList" v-bind:key="ver.id">
                <ver-item :verInfo="ver" @download="download"></ver-item>
              </div>
            </el-card>
          </div>
          <div class="mt-4 sm:mt-0 sm:w-2/6">
            <el-card>Aside</el-card>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import { defineComponent } from "@vue/runtime-core";
import VerItem from "./components/VerItem.vue";
import { load } from "recaptcha-v3";
import axios from "axios";

export default defineComponent({
  props: ["title", "appInfo", "verList", "icon", "captchaKey"],
  components: {
    AppLayout,
    VerItem,
  },
  data() {
    return {
      recaptcha: null,
    };
  },
  mounted() {
    (async () => {
      this.recaptcha = await load(this.$props.captchaKey, {
        useRecaptchaNet: true,
      });
    })();
  },
  methods: {
    download: async function (verInfo, done) {
      try {
        console.log(this.$props.captchaKey);
        const token = await this.recaptcha.execute("download");

        const listId = verInfo.list_id;
        const verId = verInfo.id;
        const result = await axios.post("/api/download", {
          listId,
          verId,
          token,
          password: 1,
        });
        // console.log(result)
        done();
        const resp = result.data;
        if (resp.code === 200) {
          // 成功
          ElMessage({
            message: "开始下载",
            type: "success",
          });
          window.open(resp.msg);
        } else {
          ElMessage({
            message: resp?.msg ?? "错误",
            type: "error",
          });
        }
      } catch (e) {
        done();
          ElMessage({
            message: "错误",
            type: "error",
          });
      }
    },
  },
});
</script>

<style>
</style>
