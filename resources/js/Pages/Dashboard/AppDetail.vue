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

                        </template>
                    </el-table-column>
                </el-table>
            </el-form>
        </div>
    </app-layout>
</template>

<script>
import { defineComponent } from "@vue/runtime-core";
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

        console.log(this.$props.verList)

    },
})
</script>

<style>

</style>
