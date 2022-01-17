<template>
  <div>
    <el-card>
      <el-row type="flex" align="middle">
        <el-col :span="5"><el-image :src="appInfo.icon" class="w-20"></el-image></el-col>
        <el-col :span="14">
          <el-row>{{appInfo.name}}</el-row>
          <el-row>
            <span class="text">{{appInfo.author}} &nbsp; {{appInfo.updated_at}}更新</span>
          </el-row>
          <el-row>
            <span class="text hidden sm:block">{{appInfo.description}}</span>
          </el-row>
        </el-col>
        <el-col :span="5">
          <Link :href="route('appinfo', {id: appInfo.id})">
            <el-button type="primary">
                查看
            </el-button>
          </Link>
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
