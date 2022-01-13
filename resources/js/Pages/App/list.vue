<template>
  <app-layout title="MRP列表">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">MRP列表</h2>
    </template>

    <!-- 主体 -->
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" style="padding:40px">
          <div v-for="item in appList" v-bind:key="item.id">
            <list-item :appInfo="item" />
          </div>
          {{ title }}
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import ListItem from "./components/item.vue";

export default defineComponent({
  props: ["title", "appList"],

  components: {
    AppLayout,
    ListItem,
  },
  methods: {

  }
});
</script>

<style>
</style>
