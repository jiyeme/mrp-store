<template>
  <app-layout title="MRP列表">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">应用详情</h2>
    </template>

    <div class="py-12">
        <div class="p-4 ml-4 mr-4">
            <div class="flex flex-col sm:flex-row justify-between">
                <div class="flex-col sm:w-3/5">
                    <el-card>
                        <el-row class="flex-col sm:flex-row">
                            <el-col :span="5">icon</el-col>
                            <el-col :span="14">description</el-col>
                            <el-col :span="5">qrcode</el-col>
                        </el-row>
                    </el-card>
                    <el-card class="mt-4">
                        introduction<br>
                        verlist
                    </el-card>
                </div>
                <div class="mt-4 sm:mt-0 sm:w-2/6">
                    <el-card>
                        Aside
                    </el-card>
                </div>
            </div>
        </div>
    </div>
  </app-layout>
</template>

<script>

import AppLayout from "@/Layouts/AppLayout.vue";
import { defineComponent } from "@vue/runtime-core";

export default defineComponent({
  props: ["title"],
  components: {
      AppLayout,
  },
  methods: {

  }
});
</script>

<style>
</style>
