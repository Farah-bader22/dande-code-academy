<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const avatars = ref([
    '🦁', '🐼', '🦊', '🐸', '🦄', '🐨', '🐯', '🐰'
]);

const form = useForm({
    name: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '', // إضافة الحقل لضمان توافق Laravel
    avatar: '🦁',
    role: 'student',
    parent_username: '', // إضافة هذا الحقل الجديد
});

const submit = () => {
    // 1. تنظيف اسم المستخدم
    form.username = form.username.trim().replace(/\s+/g, '_');

    // 2. بناء الإيميل
    form.email = form.username + '@dandecode.com';

    // 3. تأكيد كلمة المرور
    form.password_confirmation = form.password;

    // 4. تعديل ذكي: إذا كان الحقل فارغاً، نرسله كـ null ليقبله لارافيل بسهولة
    if (form.parent_username.trim() === '') {
        form.parent_username = null;
    }

    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
        onError: (errors) => {
            console.log("تفاصيل الأخطاء:", errors);
        }
    });
};
</script>

<template>
    <Head title="Join Dande Code" />

    <div class="register-container">
        <div class="signup-card">
            <div class="top-accent"></div>

            <h1 class="main-title">Join Dande Code!</h1>
            <p class="subtitle">Create your coding adventure</p>

            <h2 class="section-label">Choose Your Avatar</h2>

            <div class="avatar-grid">
                <button
                    v-for="icon in avatars"
                    :key="icon"
                    type="button"
                    @click="form.avatar = icon"
                    :class="{ 'active-avatar': form.avatar === icon }"
                    class="avatar-item"
                >
                    {{ icon }}
                </button>
            </div>

            <form @submit.prevent="submit" class="register-form" dir="ltr">
                <div class="input-group">
                    <label>Your Name</label>
                    <input v-model="form.name" type="text" placeholder="Enter your name">
                    <span v-if="form.errors.name" style="color: red; font-size: 12px;">{{ form.errors.name }}</span>
                </div>

                <div class="input-group">
                    <label>Username</label>
                    <input v-model="form.username" type="text" placeholder="Your cool username">
                    <span v-if="form.errors.username" style="color: red; font-size: 12px;">{{ form.errors.username }}</span>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input v-model="form.password" type="password" placeholder="Your secret password">
                    <span v-if="form.errors.password" style="color: red; font-size: 12px;">{{ form.errors.password }}</span>
                </div>


<div class="input-group" v-if="form.role === 'student'">
        <label>Parent Username (Optional)</label>
        <input v-model="form.parent_username" type="text" placeholder="Enter parent username to link">
        <span v-if="form.errors.parent_username" style="color: red; font-size: 12px;">{{ form.errors.parent_username }}</span>
    </div>



                <div class="input-group">
    <label>Who are you?</label>
    <select v-model="form.role" class="p-2 border rounded">
        <option value="student">Student</option>
        <option value="parent">Parent</option>
        <option value="teacher">Teacher</option>
    </select>
</div>

                <div class="button-wrapper">
                    <button type="submit" class="adventure-btn" :disabled="form.processing">
                        <span v-if="form.processing">Loading... ⏳</span>
                        <span v-else>Start Adventure! 🚀</span>
                    </button>
                </div>
            </form>

            <p class="login-link">
                Already have an account? <Link :href="route('login')">Log in</Link>
            </p>

            <hr class="divider">

            <p class="role-text">Are you a parent or teacher?</p>
            <div class="role-actions">
                <Link :href="route('login')" class="btn-parent">Parent Login</Link>
                <Link :href="route('login')" class="btn-teacher">Teacher Login</Link>
            </div>
        </div>

        <Link href="/" class="back-home">← Back to Home</Link>
    </div>
</template>

<style scoped>
/* حل المشكلة جذرياً باستخدام CSS مخصص */
.register-container {
    background-color: #f8faff;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 40px 20px;
    font-family: 'Nunito', sans-serif;
}

.signup-card {
    background: white;
    width: 100%;
    max-width: 440px;
    border-radius: 40px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.03);
    padding: 50px 40px;
    text-align: center;
    position: relative;
}

.top-accent {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 45px;
    height: 18px;
    background: #7b61ff;
    border-radius: 0 0 15px 15px;
}

.main-title {
    color: #7b61ff;
    font-size: 34px;
    font-weight: 900;
    margin-bottom: 5px;
}

.subtitle {
    color: #8e9aaf;
    font-weight: bold;
    margin-bottom: 30px;
}

.section-label {
    color: #444;
    font-weight: 800;
    margin-bottom: 15px;
}

/* حل مشكلة الطول - الأفاتارز بجانب بعض */
.avatar-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 30px;
}

.avatar-item {
    height: 75px;
    background: #f3f6f9;
    border-radius: 20px;
    font-size: 35px;
    border: 3px solid transparent;
    transition: all 0.2s;
}

.active-avatar {
    border-color: #ffd666;
    background: #fff9db;
}

.input-group {
    text-align: left;
    margin-bottom: 18px;
}

.input-group label {
    display: block;
    font-weight: 800;
    color: #333;
    margin-left: 5px;
    margin-bottom: 5px;
}

.input-group input {
    width: 100%;
    background: #f9fafb;
    border: 2px solid #f0f3f6;
    border-radius: 18px;
    padding: 14px 20px;
    outline: none;
}

.adventure-btn {
    background: linear-gradient(90deg, #a855f7, #6366f1);
    color: white;
    width: 100%;
    padding: 12px;
    border-radius: 50px;
    font-size: 20px;
    font-weight: 900;
    box-shadow: 0 8px 20px rgba(123,97,255,0.3);
    margin-top: 15px;
}

.adventure-btn span {
    text-decoration: underline;
    text-underline-offset: 4px;
}

.login-link { color: #a0aec0; font-weight: bold; margin-top: 20px; }
.login-link a { color: #7b61ff; text-decoration: underline; }

.divider { border: 0; border-top: 1px solid #eee; margin: 30px 0; }

.role-text { color: #8e9aaf; font-weight: 800; margin-bottom: 15px; font-style: italic; }

.role-actions { display: flex; gap: 10px; }
.btn-parent, .btn-teacher {
    flex: 1;
    padding: 12px;
    border-radius: 15px;
    font-weight: 900;
    text-decoration: none;
}
.btn-parent { background: #f0f4ff; color: #4c6ef5; border-bottom: 4px solid #dbe4ff; }
.btn-teacher { background: #f0fff4; color: #22c55e; border-bottom: 4px solid #dcfce7; }

.back-home { margin-top: 25px; color: #a0aec0; font-weight: 900; text-decoration: none; }
</style>
