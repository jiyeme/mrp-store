<template>
  <app-layout title="MRP列表">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">应用详情</h2>
    </template>

    <div class="py-12">
        <div class="bg-white">
            <el-container>
                <el-container>
                <el-header>头部</el-header>
                <el-main>主体</el-main>
                <el-footer>底部</el-footer>
                </el-container>
                <el-aside width="200px">Aside</el-aside>
            </el-container>
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
