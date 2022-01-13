<template>
    <Head title="Privacy Policy" />

    <div class="font-sans text-gray-900 antialiased">
        <div class="pt-4 bg-gray-100">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <jet-authentication-card-logo />
                </div>
                <h3>政策页面</h3>
                {{ test_value }}
                <el-button type="primary">按钮</el-button>
                <div v-html="policy" class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue'
import { Head } from '@inertiajs/inertia-vue3';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'

export default defineComponent({
    props: ['policy', 'test_value'],

    components: {
        Head,
        JetAuthenticationCardLogo
    },
})
</script>
