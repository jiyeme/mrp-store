<template>
    <app-layout title="首页">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                首页
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <el-card>
                        <template #header>
                            <div class="card-header">
                                <span>入口</span>
                            </div>
                        </template>
                        <div>
                            <Link :href="route('applist')">MRP应用</Link>
                        </div>
                    </el-card>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import { Link } from '@inertiajs/inertia-vue3'


    export default defineComponent({
        components: {
            AppLayout,
            Link,
        },
    })
</script>
