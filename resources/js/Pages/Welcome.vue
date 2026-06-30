<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

// فحص إذا كان المستخدم مسجل دخول حالياً وما هو دوره
const authUser = computed(() => usePage().props.auth?.user);
</script>

<template>
    <Head title="Welcome to Dande Code" />

    <div class="main-wrapper">
        <section class="hero">
            <div class="container hero-content">
                <div class="hero-text">
                    <span class="mini-badge">Learn Coding Through Play</span>
                    <h1>
                        <span class="brand-name">Dande Code</span><br>
                        Where Kids Learn to Code
                    </h1>
                    <p>
                        A playful platform that teaches programming and computational thinking
                        to children through digital lessons and hands-on activities.
                    </p>

                    <div class="hero-buttons">
                        <Link :href="route('go.student')" class="btn primary-btn">Start Learning</Link>

                        <Link :href="authUser && authUser.role === 'parent' ? route('parent.dashboard') : route('login', { role: 'parent' })" class="btn secondary-btn">Parent Dashboard</Link>
                    </div>
                </div>

                <div class="hero-visual">
                    <div class="visual-box">
                        <div class="grid-box" v-for="n in 3" :key="'g1-'+n">{ }</div>
                        <div class="grid-box" v-for="n in 3" :key="'g2-'+n">✦</div>
                        <div class="grid-box" v-for="n in 3" :key="'g3-'+n">👤</div>
                        <div class="small-circle">▲</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <h2>Learning Made Fun</h2>
                <p class="section-text">Three ways to learn coding and computational thinking</p>

                <div class="cards">
                    <div class="card card-purple">
                        <div class="icon-box">{ }</div>
                        <h3>Block-Based Coding</h3>
                        <p>Drag and drop visual blocks to create programs and learn logic step by step.</p>
                    </div>

                    <div class="card card-blue">
                        <div class="icon-box">✦</div>
                        <h3>Unplugged Activities</h3>
                        <p>Hands-on activities to learn coding without a screen.</p>
                    </div>

                    <div class="card card-green">
                        <div class="icon-box">👤</div>
                        <h3>Parent & Teacher Tools</h3>
                        <p>Track progress and monitor learning in a simple way.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="cta">
            <div class="container cta-content">
                <h2>Ready to Start the Coding Journey?</h2>
                <p>Join thousands of kids learning to code through play</p>
                <div class="cta-buttons">
                    <Link :href="route('register')" class="btn white-btn">Get Started Free</Link>

                    <Link :href="route('go.teacher')" class="btn dark-btn">For Teachers</Link>
                </div>
            </div>
        </section>

    </div>
</template>

<style scoped>
/* انقلي الـ CSS الخاص بك هنا مع التأكد من إضافة التنسيقات العامة */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Hero Styles */
.hero {
    padding: 80px 0;
    background: #fff;
}

.hero-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 50px;
}

.brand-name {
    color: #7b61ff;
    font-weight: 900;
}

/* أكملي بقية الـ CSS الخاص بك هنا ليناسب المكون */
/* ... */

/* ===== HERO ===== */
.hero {
  background: #f4f6fb;
  padding: 70px 0 55px;
}

.hero-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 60px;
}

.hero-text {
  flex: 1;
}

.mini-badge {
  display: inline-block;
  background-color: #f0ecff;
  color: #8a46ff;
  padding: 7px 14px;
  border-radius: 20px;
  font-size: 12px;
  margin-bottom: 18px;
}

.hero-text h1 {
  font-size: 55px;
  line-height: 1.18;
  margin-bottom: 18px;
  font-weight: 700;
  color: #181818;
}

.hero-text h1 span {
  color: #6f43ff;
}

.hero-text p {
  max-width: 470px;
  color: #6f6f79;
  margin-bottom: 26px;
  font-size: 14px;
}

.hero-buttons {
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
}

.btn {
  text-decoration: none;
  padding: 12px 22px;
  border-radius: 24px;
  font-size: 13px;
  font-weight: 600;
  transition: 0.3s;
  display: inline-block;
}

.primary-btn {
  background: linear-gradient(90deg, #8b2dff, #4663ff);
  color: white;
  box-shadow: 0 8px 20px rgba(124, 65, 255, 0.28);
}-----

.secondary-btn {
  background-color: #ffffff;
  color: #6f43ff;
  border: 1px solid #e6def8;
  box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
}

.primary-btn:hover,
.secondary-btn:hover,
.white-btn:hover,
.dark-btn:hover {
  transform: translateY(-2px);
}

/* ===== RIGHT BOX ===== */
.hero-visual {
  flex: 1;
  display: flex;
  justify-content: center;
}

.visual-box {
  width: 360px;
  min-height: 345px;
  background: linear-gradient(135deg, #c06cff 0%, #8a6dff 45%, #3d87ff 100%);
  border-radius: 18px;
  padding: 14px;
  position: relative;
  box-shadow: 0 18px 32px rgba(89, 97, 201, 0.22);
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 10px;
}

.grid-box {
  background: rgba(255, 255, 255, 0.13);
  border-radius: 12px;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #ffe17a;
  font-size: 23px;
  min-height: 98px;
}

.small-circle {
  position: absolute;
  top: -10px;
  right: -10px;
  width: 42px;
  height: 42px;
  background: #f6c71e;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  font-size: 16px;
  font-weight: bold;
  box-shadow: 0 8px 18px rgba(246, 199, 30, 0.35);
}

/* ===== FEATURES ===== */
.features {
  background-color: #ffffff;
  padding: 72px 0 78px;
  text-align: center;
}

.features h2 {
  font-size: 42px;
  margin-bottom: 5px;
  color: #1b1b1b;
}

.section-text {
  color: #7c7c85;
  margin-bottom: 34px;
  font-size: 14px;
}

.cards {
  display: flex;
  gap: 20px;
  justify-content: center;
  flex-wrap: wrap;
}

.card {
  width: 310px;
  text-align: left;
  padding: 26px 20px;
  border-radius: 16px;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.07);
}

.card-purple {
  background-color: #f7ecff;
}

.card-blue {
  background-color: #edf4ff;
}

.card-green {
  background-color: #edf9ef;
}

.icon-box {
  width: 46px;
  height: 46px;
  border-radius: 12px;
  background: linear-gradient(180deg, #8d2dff, #4c61ff);
  color: white;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 20px;
  margin-bottom: 16px;
}

.card-green .icon-box {
  background: #16a34a;
}

.card h3 {
  margin-bottom: 10px;
  font-size: 21px;
  color: #1f1f1f;
}

.card p {
  color: #5f5f68;
  font-size: 13px;
}

/* ===== CTA ===== */
.cta {
  background: linear-gradient(90deg, #8d2dff 0%, #4a5fff 100%);
  padding: 62px 0;
  text-align: center;
  color: white;
}

.cta-content h2 {
  font-size: 40px;
  margin-bottom: 10px;
}

.cta-content p {
  margin-bottom: 24px;
  color: #f5f3ff;
  font-size: 14px;
}

.cta-buttons {
  display: flex;
  justify-content: center;
  gap: 14px;
  flex-wrap: wrap;
}

.white-btn {
  background-color: #ffffff;
  color: #6a3fff;
}

.dark-btn {
  background: #8c24ff;
  color: white;
  border: 1px solid rgba(255, 255, 255, 0.15);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
  .hero-content {
    flex-direction: column;
    text-align: center;
  }

  .hero-text p {
    margin-left: auto;
    margin-right: auto;
  }

  .hero-buttons {
    justify-content: center;
  }

  .visual-box {
    width: 100%;
    max-width: 360px;
  }

  .hero-text h1,
  .features h2,
  .cta-content h2 {
    font-size: 34px;
  }
}
</style>
