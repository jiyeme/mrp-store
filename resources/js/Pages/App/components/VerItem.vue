<template>
    <div>
        <el-divider ></el-divider>
        <el-row class="items-center">
            <el-col :span="18">
                {{ verInfo.version }}
            </el-col>
            <el-col :span="6"><el-button type="primary" :loading="loading" @click="download">下载</el-button></el-col>
        </el-row>
    </div>
</template>

<script>
import { defineComponent } from "@vue/runtime-core";

export default defineComponent({
    props:['verInfo'],
    data(){
        return {
            loading: false
        }
    },
    components: {
    },
    methods: {

    },
    emits:['download'],
    mounted(){
    },
    methods: {
        download: function(){
            this.loading = true
            this.$emit('download', this.$props.verInfo, this.done)
        },
        done: function(){
            this.loading = false
        }
    }
})
</script>

<style>

</style>
