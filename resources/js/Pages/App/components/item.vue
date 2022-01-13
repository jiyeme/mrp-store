<template>
  <div>
    <el-card>
      <el-row type="flex" align="middle">
        <el-col :span="3">icon</el-col>
        <el-col :span="18">
          <el-row>{{appInfo.name}}</el-row>
          <el-row>
            <span class="text">{{appInfo.author}} &nbsp; {{appInfo.updated_at}}更新</span>
          </el-row>
          <el-row>
            <span class="text">{{appInfo.description}}</span>
          </el-row>
        </el-col>
        <el-col :span="3">
          <el-button type="primary">
            <Link :href="route('appinfo', {id: appInfo.id})">查看</Link>
          </el-button>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
  props: ["title", "appInfo"],

  components: {
    Link,
  },
});
</script>

<style scoped>
.text {
  font-size: small;
  color: gray;
}
</style>
