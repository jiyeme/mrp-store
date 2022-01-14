<template>
  <app-layout title="MRP列表">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">MRP列表</h2>
    </template>

    <!-- 主体 -->
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div v-for="item in appList" v-bind:key="item.id">
            <list-item :appInfo="item" />
          </div>
        </div>
        <div class="my-12 bg-white flex justify-center">
            <el-pagination background layout="prev, pager, next" v-model:current-page="pageData.current" v-model:page-size="pageData.pageSize" :total="pageData.total"></el-pagination>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import ListItem from "./components/ListItem.vue";
import { Inertia } from '@inertiajs/inertia'

export default defineComponent({
  props: ["title", "appList", 'pageData'],

  components: {
    AppLayout,
    ListItem,
  },
  mounted(){
      console.log('pageData', this.$props.pageData)
  },
  watch: {
      'pageData.current': (newVal, oldVal)=>{
          console.log(newVal, oldVal)
          Inertia.visit('', {
              data: {
                  page: newVal
              }
          })
      }
  },
  methods: {},
});
</script>

<style>
</style>
