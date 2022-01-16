<template>
  <!-- 应用管理界面 -->
  <Head title="应用管理"></Head>
  <app-layout>
    <div class="m-4">
      <el-table :data="appList" style="width: 100%">
        <el-table-column prop="appid" label="应用id" width="180" />
        <el-table-column prop="name" label="名称" width="180" />
        <el-table-column prop="created_at" label="创建时间" />
        <el-table-column fixed="right" label="操作" width="250">
          <template #default="scope">
            <el-button size="small" @click="viewDetail"><Link :href="route('appDetail', {id: scope.row.id})" >详情</Link></el-button>
            <el-button size="small">编辑</el-button>
            <el-popconfirm title="此操作将会删除应用所有相关数据!" @confirm="delApp(scope.row.id)">
                <template #reference>
                    <el-button type="danger" size="small">删除</el-button>
                </template>
            </el-popconfirm>
          </template>
        </el-table-column>
      </el-table>

      <!-- 分页导航 -->
      <div class="flex justify-center bg-white py-4">
        <el-pagination
          background
          layout="prev, pager, next"
          v-model:current-page="currPage"
          :page-count="lastPage"
        ></el-pagination>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { defineComponent } from "@vue/runtime-core";
import axios from "axios";
import AppLayout from "../../Layouts/AppLayout.vue";

export default defineComponent({
  props: ["appList", "currentPage", "total", "lastPage"],
  components: {
    AppLayout,
    Head,
    Link,
  },
  data() {
    return {
      currPage: this.$props.currentPage,
      showDetail: true,
    };
  },
  watch: {
    currPage: (newVal, oldval) => {
      Inertia.visit("", {
        data: {
          page: newVal,
        },
      });
    },
  },
  mounted() {
    console.log(this.$props);
  },
  methods: {
    viewDetail: function () {},
    delApp: async function(id){
        console.log(id)
        const result = await axios.delete(`/dash/appDelete/${id}`)
        console.log(result)
        const resp = result.data
        if(resp[1]){
            // 成功
            ElMessage({
                message: '删除成功,重载数据',
                type: 'success'
            })
            Inertia.visit('')
        }else{
            // 失败
            ElMessage({
                message: '删除失败',
                type: 'error'
            })
        }
    }
  },
});
</script>

<style>
</style>
