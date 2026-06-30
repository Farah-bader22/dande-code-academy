<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

// تجهيز الفورم للربط مع الباك إند
const form = useForm({
    username: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="تسجيل الدخول - Dande Code" />

    <div class="min-h-screen bg-gradient-to-br from-[#f8faff] to-[#e8efff] flex flex-col items-center justify-center p-6 font-['Nunito']" dir="rtl">

        <main class="w-full max-w-[460px] bg-white rounded-[45px] shadow-[0_20px_50px_rgba(0,0,0,0.05)] p-10 md:p-14 text-center">

            <div class="flex justify-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-r from-[#9b4dff] to-[#3a7bd5] rounded-[20px] flex items-center justify-center shadow-lg transform rotate-3">
                    <i class="fas fa-star text-white text-3xl"></i>
                </div>
            </div>

            <div class="flex items-center justify-center mb-3">
                <h1 class="text-[32px] font-[800] text-[#5c6bc0]">
                    Welcome Back !
                </h1>
            </div>

            <p class="text-[#7c86a1] text-lg font-semibold mb-10">Continue your learning journey</p>

            <form @submit.prevent="submit" class="space-y-7">

                <div class="text-left" dir="ltr">
                    <label class="block text-[#444] font-bold mb-3 ml-2">Username</label>
                    <div class="relative">
                        <i class="far fa-user absolute left-5 top-1/2 -translate-y-1/2 text-[#b0b8c9] text-xl"></i>
                        <input
                            v-model="form.username"
                            type="text"
                            placeholder="Your cool username"
                            class="w-full bg-[#fcfdfe] border-2 border-[#f0f2f5] rounded-[22px] py-5 pl-14 pr-5 outline-none focus:border-[#9b4dff] focus:ring-4 focus:ring-purple-50 transition-all text-[16px]"
                            required
                        >
                    </div>
                    <p v-if="form.errors.username" class="text-red-500 text-sm mt-1 ml-2">{{ form.errors.username }}</p>
                </div>

                <div class="text-left" dir="ltr">
                    <label class="block text-[#444] font-bold mb-3 ml-2">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-[#b0b8c9] text-xl"></i>
                        <input
                            v-model="form.password"
                            type="password"
                            placeholder="Your secret password"
                            class="w-full bg-[#fcfdfe] border-2 border-[#f0f2f5] rounded-[22px] py-5 pl-14 pr-5 outline-none focus:border-[#9b4dff] focus:ring-4 focus:ring-purple-50 transition-all text-[16px]"
                            required
                        >
                    </div>
                    <p v-if="form.errors.password" class="text-red-500 text-sm mt-1 ml-2">{{ form.errors.password }}</p>
                </div>

                <button
                    type="submit"
                    :class="{ 'opacity-25': form.processing }"
                    class="w-full bg-gradient-to-r from-[#9b4dff] to-[#3a7bd5] text-white text-xl font-extrabold py-5 rounded-[22px] shadow-[0_10px_25px_rgba(155,77,255,0.3)] hover:scale-[1.02] active:scale-[0.98] transition-all mt-4 flex items-center justify-center gap-2"
                >
                    <span class="flex items-center gap-2">
                        Let's Code ✨
                    </span>
                </button>
            </form>

            <div class="mt-10 space-y-4 text-center">
                <Link :href="route('register')" class="block text-[#a855f7] font-extrabold text-[16px] hover:underline">
                    New here? <span class="text-[#3a7bd5]">Create account</span>
                </Link>
                <Link v-if="route('password.request')" :href="route('password.request')" class="block text-[#7c86a1] font-bold text-[15px] hover:text-[#5c6bc0]">
                    🔑 Forgot password?
                </Link>
            </div>

            <div class="relative my-10">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
                <div class="relative flex justify-center text-sm"><span class="px-4 bg-white text-gray-300 font-bold">OR</span></div>
            </div>

            <div class="space-y-5">
                <p class="text-[#7c86a1] font-bold">Are you a parent or teacher?</p>
                <div class="flex gap-4" dir="ltr">
                    <Link :href="route('login')" class="flex-1 bg-blue-50 text-blue-600 font-extrabold py-4 rounded-[20px] hover:bg-blue-100 transition-colors text-center border border-blue-100">
                        Parent Login
                    </Link>
                    <Link :href="route('login')" class="flex-1 bg-green-50 text-green-600 font-extrabold py-4 rounded-[20px] hover:bg-green-100 transition-colors text-center border border-green-100">
                        Teacher Login
                    </Link>
                </div>
            </div>
        </main>

        <Link href="/" class="mt-8 text-[#7c86a1] font-extrabold hover:text-[#5c6bc0] transition-colors" dir="ltr">
            ← Back to Home
        </Link>
    </div>
</template>

<style scoped>
/* أضفنا الخط هنا للتأكد من ظهوره */
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap');
</style>
