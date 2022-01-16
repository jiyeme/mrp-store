<template>
    <app-layout>
        <div class="m-10">
            <el-form :model="app">
                <el-form-item label="应用id" prop="appid">
                    <el-input type="text" v-model="app.appid"></el-input>
                </el-form-item>
                <el-form-item label="应用名称" prop="name">
                    <el-input v-model="app.name"></el-input>
                </el-form-item>
                <el-form-item label="应用介绍" prop="description">
                    <el-input v-model="app.description"></el-input>
                </el-form-item>

                <el-divider></el-divider>

                <el-table :data="verList">
                    <el-table-column prop="version" label="版本"></el-table-column>
                    <el-table-column label="操作">
                        <template #default="scope">
                            <el-popconfirm title="确定删除该版本？" @confirm="delVer(scope.row.id)">
                                <template #reference>
                                    <el-button type="danger" size="small">删除</el-button>
                                </template>
                            </el-popconfirm>
                        </template>
                    </el-table-column>
                </el-table>
            </el-form>
        </div>
    </app-layout>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";
import { defineComponent } from "@vue/runtime-core";
import axios from "axios";
import AppLayout from "../../Layouts/AppLayout.vue";


export default defineComponent({
    props: {
        appDetail:{
            type: Object,
            required: true
        },
        verList: {
            type: Array,
            required: true
        },
        edit: {
            type: Boolean,
            default: false
        }
    },
    components: {
        AppLayout,
    },
    data(){
        return {
            app: {
                id: 0,
                appid: 0,
                name: ''
            }
        }
    },
    mounted(){
        const ori = this.$props.appDetail
        const attrs = ['id', 'appid', 'name', 'description']

        for(let attr of attrs){
            this.app[attr] = ori[attr]
        }

        // console.log(this.$props.verList)

    },
    methods: {
        delVer: async function(id){
            // console.log(id)
            const res = await axios.delete(route('verDelete', {id}))
            const resp = res.data;
            if(resp.result){
                // success
                ElMessage({
                    message: '删除成功,重载数据',
                    type: 'success'
                })
                Inertia.visit('')
            }else{
                // failed
                ElMessage({
                    message: '删除失败',
                    type: 'error'
                })
            }
        }
    }
})
</script>

<style>

</style>
