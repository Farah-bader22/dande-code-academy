<script setup>
import { ref, onMounted, computed, nextTick } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';

// استقبال الـ Props بشكل صحيح ونظيف
const props = defineProps({
    auth: Object,
    tracks: Array,
    stats: Object,
    assignments: Array,
    sessions: Array,
    feedbacks: Array,
    resources: Array
});

const user = computed(() => props.auth?.user || usePage().props.auth?.user || { name: 'طالبنا المبدع', avatar: '🦁' });

// حساب نسبة التقدم الإجمالية بناءً على الدروس المكتملة لجميع المسارات
const overallPercentage = computed(() => {
    if (!props.tracks || props.tracks.length === 0) return 0;
    const total = props.tracks.reduce((sum, track) => sum + (track.total_lessons || 0), 0);
    const completed = props.tracks.reduce((sum, track) => sum + (track.lessons_completed_count || 0), 0);
    return total > 0 ? Math.round((completed / total) * 100) : 0;
});

const dashOffset = computed(() => {
    const circumference = 2 * Math.PI * 45;
    return circumference - (overallPercentage.value / 100) * circumference;
});

const isGameVisible = ref(false);
const rining = ref(false);
const myCommands = ref([]);
const robotPosition = ref(0);
const targetPosition = ref(12);

// --- Modal Logic ---
const showResultModal = ref(false);
const resultStatus = ref('success');

// دالة تشغيل اللعبة عند الضغط على زر Start
const openGame = async () => {
    isGameVisible.value = true;
    await nextTick();
    buildGrid();
};

const buildGrid = () => {
    const gridContainer = document.getElementById('gameGrid');
    if (!gridContainer) return;

    gridContainer.innerHTML = '';
    for (let i = 0; i < 25; i++) {
        const cell = document.createElement('div');
        cell.className = 'relative bg-white/50 border border-slate-100 rounded-2xl flex items-center justify-center text-3xl shadow-sm transition-all duration-500';

        if (i === robotPosition.value) {
            cell.innerHTML = '🤖';
            cell.classList.add('bg-blue-50', 'scale-110', 'z-10', 'shadow-md');
        } else if (i === targetPosition.value) {
            cell.innerHTML = '⭐';
            cell.classList.add('animate-pulse');
        }
        gridContainer.appendChild(cell);
    }
};

const addCommand = (type, label, icon, color) => {
    if (myCommands.value.length < 10) {
        myCommands.value.push({ type, label, icon, color, active: false });
    }
};

const runCode = async () => {
    if (rining.value || myCommands.value.length === 0) return;
    rining.value = true;

    for (const cmd of myCommands.value) {
        cmd.active = true;
        if (cmd.type === 'move') {
            if (robotPosition.value % 5 < 4) robotPosition.value += 1;
        } else if (cmd.type === 'turn') {
            if (robotPosition.value + 5 < 25) robotPosition.value += 5;
        }
        buildGrid();
        await new Promise(r => setTimeout(r, 600));
        cmd.active = false;
    }

    resultStatus.value = (robotPosition.value === targetPosition.value) ? 'success' : 'try-again';
    showResultModal.value = true;
    rining.value = false;
    myCommands.value = [];
};

const closeFeedback = () => {
    showResultModal.value = false;
    if (resultStatus.value === 'success') {
        robotPosition.value = 0;
        targetPosition.value = Math.floor(Math.random() * 20) + 4;
        buildGrid();
    }
};

const getProgress = (lessons, total) => (total > 0 ? Math.round((lessons / total) * 100) : 0);

onMounted(() => {
    console.log("🌟 كل الـ Props المشتركة لـ DandeCode الحين:", props.sessions);
});
</script>
<template>
  <div class="min-h-screen bg-gray-50 pb-10">

    <!-- الـ Header العلوي -->
    <header class="bg-white border-b border-gray-100 px-8 py-4 flex items-center justify-between sticky top-0 z-50">
      <div class="flex items-center gap-4">
        <!-- الأفاتار: تم تعديل تدرج الألوان ليتناسب مع هوية DandeCode -->
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-600 to-blue-500 text-white flex items-center justify-center text-3xl shadow-lg">
          <span>{{ user?.avatar || '🦄' }}</span>
        </div>

        <div>
          <!-- ترحيب مخصص -->
          <h1 class="text-2xl font-black text-gray-800">
            Hey, {{ user?.name ? user.name.split(' ')[0] : 'Explorer' }}! 👋
          </h1>
          <p class="text-sm text-gray-500 font-bold">Ready to code something cool?</p>
        </div>
      </div>

      <!-- الإحصائيات السريعة -->
      <div class="flex items-center gap-4">
        <div class="hidden md:flex bg-orange-50 text-orange-600 px-5 py-2 rounded-2xl font-black">
          <i class="fa-solid fa-fire mr-2"></i> 5 Day Streak!
        </div>
        <div class="bg-yellow-50 text-yellow-600 px-5 py-2 rounded-2xl font-black">
          <i class="fa-regular fa-star mr-2"></i> 850 Points
        </div>
        <!-- تم التأكد من استخدام <Link> الخاص بـ Inertia -->
        <Link href="/" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-2xl font-bold transition-colors">
          Home
        </Link>
      </div>
    </header>

    <!-- محتوى الصفحة الرئيسي -->
    <main class="p-8 max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">

<!-- القسم الأيسر: التحديات والمسارات -->
<section class="lg:col-span-8 space-y-10">

  <!-- 1. تحدي اليوم -->
  <section>
    <h2 class="text-2xl font-black mb-5 flex items-center gap-3 text-gray-800">
      <i class="fa-solid fa-bullseye text-purple-500"></i>
      Today's Challenge
    </h2>

    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-[32px] p-8 text-white soft-shadow relative min-h-[260px] overflow-hidden">
      <div class="relative z-10">
        <div class="text-4xl mb-4">🤖 ✨</div>
        <h3 class="text-2xl font-black mb-3 text-white">Create a Dancing Robot!</h3>
        <p class="font-bold text-white/90">Use loops to make the robot dance and spin</p>
        <p class="mt-8 text-sm font-bold opacity-80">⏰ 6 hours left</p>
      </div>

      <div class="absolute right-8 top-8 bg-white/20 rounded-2xl px-5 py-4 text-center font-black">
        +50<br><span class="text-sm font-bold">points</span>
      </div>

      <button
        v-if="!isGameVisible"
        @click="openGame"
        class="absolute right-8 bottom-8 z-20 bg-white text-orange-500 px-8 py-3 rounded-full font-black shadow-lg hover:scale-105 active:scale-95 transition-all"
      >
        Start Challenge →
      </button>
    </div>
  </section>

  <!-- 2. رحلة التعلم -->
  <section class="mt-10">
    <h2 class="text-2xl font-black mb-6 text-gray-800">Your Learning Journey</h2>

    <div class="space-y-6">
        <div v-for="(track, index) in tracks" :key="index"
             :class="['rounded-[28px] p-6 shadow-sm transition-all', track.is_unlocked ? 'hover:translate-x-2' : 'opacity-70 cursor-not-allowed']"
             :style="{ backgroundColor: track.is_unlocked ? track.bg_color : '#f3f4f6' }">

            <div class="flex items-center gap-6">
                <div class="w-16 h-16 rounded-2xl text-white flex items-center justify-center text-2xl shadow-md"
                     :style="{ backgroundColor: track.is_unlocked ? track.color : '#9ca3af' }">
                    <i :class="[track.is_unlocked ? track.icon : 'fas fa-lock']"></i>
                </div>

                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 :class="['text-xl font-black', track.is_unlocked ? 'text-gray-800' : 'text-gray-500']">
                                {{ track.title }}
                            </h3>
                            <span v-if="track.is_unlocked" class="bg-white/50 px-3 py-1 rounded-full text-xs text-gray-600 font-bold">
                                {{ track.type }}
                            </span>
                        </div>

                        <div v-if="track.is_unlocked" class="text-yellow-500 text-lg">
                            <span v-for="s in 5" :key="s">{{ s <= track.stars_earned ? '★' : '☆' }}</span>
                        </div>
                    </div>

                    <div v-if="track.is_unlocked" class="mt-5">
                        <div class="flex justify-between mb-2 text-sm font-bold text-gray-500">
                            <span>{{ track.lessons_completed_count }} / {{ track.total_lessons }} Lessons</span>
                            <span>{{ getProgress(track.lessons_completed_count, track.total_lessons) }}%</span>
                        </div>
                        <div class="w-full bg-white/60 rounded-full h-3 overflow-hidden">
                            <div class="h-3 rounded-full transition-all duration-1000"
                                 :style="{ width: getProgress(track.lessons_completed_count, track.total_lessons) + '%', backgroundColor: track.color }">
                            </div>
                        </div>
                    </div>

                    <p v-else class="mt-2 text-gray-400 font-bold italic text-sm">
                        Keep exploring to unlock this track! 🔒
                    </p>
                </div>
            </div>
        </div>
    </div>
  </section>

  <!-- 3. تحديات الروبوت -->
  <!-- Robot Challenges Section -->
<section class="mt-12">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-black text-indigo-900 flex items-center gap-3">
            <span class="bg-indigo-100 p-2 rounded-2xl text-2xl">🤖</span>
            Robot Challenges
        </h2>
        <span class="bg-indigo-50 text-indigo-600 px-4 py-2 rounded-full text-sm font-bold border border-indigo-100">
            {{ assignments.length }} Tasks Available
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div v-for="assignment in assignments" :key="assignment.id"
             class="group bg-white rounded-[2.5rem] p-6 shadow-xl border-b-8 border-indigo-500 hover:-translate-y-2 transition-all duration-300 relative overflow-hidden">

            <!-- Decor Design Element -->
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50 group-hover:scale-150 transition-transform"></div>

            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-lg text-xs font-black uppercase tracking-wider">
                        {{ assignment.difficulty || 'Beginner' }}
                    </span>
                    <span class="text-indigo-500 font-black text-sm">
                        +{{ assignment.points || 100 }} XP
                    </span>
                </div>

                <h3 class="text-xl font-black text-indigo-900 mb-2 group-hover:text-indigo-600 transition-colors">
                    {{ assignment.title }}
                </h3>

                <p class="text-gray-500 text-sm mb-6 line-clamp-2 leading-relaxed">
                    {{ assignment.description }}
                </p>

                <div class="flex items-center gap-4 mb-6 text-xs font-bold text-gray-400">
                    <span class="flex items-center gap-1">
                        <i class="fa-regular fa-clock"></i> 15 mins
                    </span>
                    <span class="flex items-center gap-1">
                        <i class="fa-regular fa-user"></i> {{ Math.floor(Math.random() * 50) }} solved
                    </span>
                </div>

                <Link :href="route('student.play', assignment.id)"
                      class="block w-full text-center bg-indigo-600 text-white py-4 rounded-[1.5rem] font-black hover:bg-indigo-700 hover:shadow-indigo-200 hover:shadow-2xl transition-all active:scale-95">
                    Start Mission 🚀
                </Link>
            </div>
        </div>
    </div>
</section>

</section> <!-- إغلاق الـ section الرئيسي (السطر 139) -->







<!-- RIGHT SIDE -->
<aside class="lg:col-span-4 space-y-8">

  <!-- PROGRESS -->
  <section class="bg-white rounded-[26px] p-7 shadow-sm border border-gray-50">
    <h2 class="text-xl font-black mb-6 text-gray-800">🏆 Your Progress</h2>

    <div class="flex justify-center mb-6">
      <div class="relative w-[150px] h-[150px] flex items-center justify-center">
        <!-- SVG Progress Circle -->
        <svg width="150" height="150" class="transform -rotate-90">
          <circle cx="75" cy="75" r="58" stroke="#f3f4f6" stroke-width="12" fill="none"></circle>
          <circle cx="75" cy="75" r="58" stroke="#4f6df5" stroke-width="12" fill="none"
            stroke-linecap="round"
            :stroke-dasharray="364"
            :stroke-dashoffset="dashOffset"
            class="transition-all duration-1000 ease-out"></circle>
        </svg>

        <div class="absolute text-center transform rotate-90">
          <div class="text-3xl font-black text-gray-800">{{ overallPercentage }}%</div>
          <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Complete</div>
        </div>
      </div>
    </div>

    <div class="space-y-3">
      <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl border border-gray-100">
        <span class="text-xs font-bold text-gray-500 uppercase">Lessons</span>
        <span class="font-black text-gray-800">{{ stats.completed_lessons }} / {{ stats.total_lessons }}</span>
      </div>
      <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl border border-gray-100">
        <span class="text-xs font-bold text-gray-500 uppercase">Coding Time</span>
        <span class="font-black text-gray-800">{{ stats.coding_hours }}h</span>
      </div>
      <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl border border-gray-100">
        <span class="text-xs font-bold text-gray-500 uppercase">Challenges</span>
        <span class="font-black text-gray-800">{{ stats.challenges_solved }}</span>
      </div>
    </div>
  </section>

  <!-- ACHIEVEMENTS -->
  <section class="bg-white rounded-[26px] p-7 shadow-sm border border-gray-50">
    <h2 class="text-xl font-black mb-6 text-gray-800 flex items-center gap-2">
      <i class="fa-solid fa-award text-purple-500"></i>
      Achievements
    </h2>

    <div class="grid grid-cols-2 gap-4">
      <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl h-32 flex flex-col items-center justify-center text-white font-black shadow-sm transition-transform hover:scale-105 cursor-pointer">
        <div class="text-3xl mb-1">🏆</div>
        <p class="text-[10px] uppercase tracking-tighter">First Code</p>
      </div>

      <div class="bg-gradient-to-br from-purple-400 to-pink-500 rounded-2xl h-32 flex flex-col items-center justify-center text-white font-black shadow-sm transition-transform hover:scale-105 cursor-pointer">
        <div class="text-3xl mb-1">⭐</div>
        <p class="text-[10px] uppercase tracking-tighter">5 Day Streak</p>
      </div>

      <div class="bg-gradient-to-br from-blue-400 to-cyan-500 rounded-2xl h-32 flex flex-col items-center justify-center text-white font-black shadow-sm transition-transform hover:scale-105 cursor-pointer">
        <div class="text-3xl mb-1">🎯</div>
        <p class="text-[10px] uppercase tracking-tighter">Perfect Score</p>
      </div>

      <div class="bg-gradient-to-br from-green-400 to-emerald-500 rounded-2xl h-32 flex flex-col items-center justify-center text-white font-black shadow-sm transition-transform hover:scale-105 cursor-pointer">
        <div class="text-3xl mb-1">🚀</div>
        <p class="text-[10px] uppercase tracking-tighter">10 Lessons</p>
      </div>
    </div>

    <button class="w-full mt-6 bg-gray-100 hover:bg-gray-200 text-gray-500 py-4 rounded-2xl font-black text-sm transition-colors uppercase tracking-widest">
      View All Badges
    </button>
  </section>

  <!-- FAVORITES -->
  <section class="bg-white rounded-[26px] p-7 shadow-sm border border-gray-50">
    <h2 class="text-xl font-black mb-5 text-pink-500 flex items-center gap-2">
      <i class="fa-solid fa-heart"></i>
      Favorites
    </h2>

    <div class="space-y-4">
      <div class="flex items-center gap-4 group cursor-pointer">
        <div class="w-12 h-12 bg-pink-50 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">🎨</div>
        <div>
          <h3 class="font-black text-gray-800 group-hover:text-pink-500 transition-colors">Draw with Code</h3>
          <p class="text-xs text-gray-400 font-bold">Lesson 7</p>
        </div>
      </div>

      <div class="flex items-center gap-4 group cursor-pointer">
        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">🎮</div>
        <div>
          <h3 class="font-black text-gray-800 group-hover:text-blue-500 transition-colors">Binary Card Game</h3>
          <p class="text-xs text-gray-400 font-bold">Unplugged</p>
        </div>
      </div>
    </div>
  </section>

</aside>

    </main>
  </div>
  <section v-if="isGameVisible" class="fixed inset-0 z-[100] bg-[#edf6ff] overflow-y-auto">
    <div class="max-w-[1200px] mx-auto px-8 py-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-10">
            <div class="flex items-center gap-5">
                <button @click="isGameVisible = false" class="w-14 h-14 bg-white rounded-2xl shadow-sm text-gray-400 hover:text-red-500 transition-all flex items-center justify-center">
                    <i class="fa-solid fa-house text-xl"></i>
                </button>
                <div>
                    <h1 class="text-3xl font-black text-gray-800">DandeCode Lab 🤖</h1>
                    <nav class="text-sm text-gray-400 font-bold mt-1">
                        Sequences & Patterns <span class="mx-1">›</span> <span class="text-blue-500">Lesson daily</span>
                    </nav>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

            <!-- LEFT COLUMN: GOAL & CODE BLOCKS -->
            <div class="lg:col-span-4 space-y-6">

                <!-- GOAL SECTION (بناءً على image_4d0dfa.png) -->
                <div class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-white">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="text-2xl text-orange-500">💡</span>
                        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Goal</h2>
                    </div>

                    <p class="text-gray-600 font-medium leading-relaxed mb-8">
                        Help the robot move to the star! Use the code blocks to create a sequence of moves.
                    </p>

                    <!-- TIP BOX -->
                    <div class="bg-blue-50/50 border border-blue-100 rounded-3xl p-6 relative overflow-hidden">
                        <div class="flex gap-4 items-start relative z-10">
                            <div class="bg-white p-2 rounded-xl shadow-sm text-xl">💡</div>
                            <p class="text-blue-800 font-bold leading-tight pt-1">
                                <span class="block text-xs text-blue-400 mb-1 uppercase tracking-wider">Tip:</span>
                                The robot needs to move forward 3 times, then turn right!
                            </p>
                        </div>
                    </div>
                </div>

                <!-- CODE BLOCKS SECTION -->
                <div class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-white">
                    <h2 class="text-xl font-black mb-8 text-gray-700 uppercase text-sm tracking-widest">Code Blocks</h2>
                    <div class="space-y-4">
                        <button @click="addCommand('move', 'Move Forward', 'fa-arrow-right', 'bg-blue-600')"
                                class="w-full flex items-center gap-4 bg-blue-600 text-white p-5 rounded-2xl font-bold hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-blue-100">
                            <i class="fa-solid fa-arrow-right w-6"></i> Move Forward
                        </button>

                        <button @click="addCommand('turn', 'Turn Right', 'fa-rotate-right', 'bg-green-600')"
                                class="w-full flex items-center gap-4 bg-green-600 text-white p-5 rounded-2xl font-bold hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-green-100">
                            <i class="fa-solid fa-rotate-right w-6"></i> Turn Right
                        </button>

                        <button @click="addCommand('repeat', 'Repeat 3 times', 'fa-arrows-rotate', 'bg-purple-500')"
                                class="w-full flex items-center gap-4 bg-purple-500 text-white p-5 rounded-2xl font-bold hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-purple-100">
                            <i class="fa-solid fa-arrows-rotate w-6"></i> Repeat 3 times
                        </button>

                        <button @click="addCommand('wait', 'Wait 1 second', 'fa-clock', 'bg-amber-500')"
                                class="w-full flex items-center gap-4 bg-amber-500 text-white p-5 rounded-2xl font-bold hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-amber-100">
                            <i class="fa-solid fa-clock w-6"></i> Wait 1 second
                        </button>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: STAGE & WORKSPACE -->
            <div class="lg:col-span-8 space-y-8">

                <!-- STAGE (Grid) -->
                <div class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-white">
                    <h2 class="text-2xl font-black text-gray-800 mb-6">Stage</h2>
                    <div id="gameGrid" class="grid grid-cols-5 gap-3 bg-slate-50 p-8 rounded-[2.5rem] aspect-video max-h-[450px] mx-auto shadow-inner">
                        <!-- Cells generate via JS buildGrid() -->
                    </div>
                </div>

                <!-- YOUR CODE (Workspace) -->
                <div class="bg-white rounded-[2.5rem] p-8 shadow-xl">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-black text-gray-800">Your Code</h2>
                        <button @click="myCommands = []" class="bg-slate-100 px-4 py-2 rounded-xl text-sm font-bold text-gray-400 hover:text-red-500 transition-colors">
                            <i class="fa-solid fa-trash-can mr-2"></i> Clear
                        </button>
                    </div>

                    <div class="flex flex-wrap gap-3 min-h-[140px] p-8 bg-slate-50 rounded-[2.5rem] border-4 border-dashed border-slate-200">
                        <div v-if="myCommands.length === 0" class="w-full flex items-center justify-center text-slate-300 font-bold">
                            Drag or click blocks to start coding...
                        </div>
                        <div v-for="(cmd, index) in myCommands" :key="index"
                             :class="[cmd.color, cmd.active ? 'ring-4 ring-yellow-400 scale-110 shadow-2xl' : '']"
                             class="text-white px-6 py-4 rounded-2xl font-black shadow-md flex items-center gap-3 transition-all cursor-default">
                            <i :class="['fa-solid', cmd.icon]"></i>
                            {{ cmd.label }}
                        </div>
                    </div>

                    <button @click="runCode" :disabled="isRunning || myCommands.length === 0"
                            class="w-full mt-8 bg-green-500 hover:bg-green-600 disabled:bg-slate-200 text-white py-6 rounded-[2rem] font-black text-2xl shadow-xl shadow-green-100 transition-all active:scale-[0.98]">
                        <span v-if="!isRunning">▶ Run My Program</span>
                        <span v-else class="flex items-center justify-center gap-3">
                             <i class="fa-solid fa-spinner animate-spin"></i> Running...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Feedback Modal للأطفال -->
<transition name="pop">
    <div v-if="showResultModal" class="fixed inset-0 z-[200] flex items-center justify-center p-6 bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-[3rem] p-10 max-w-sm w-full text-center shadow-2xl">

            <div :class="resultStatus === 'success' ? 'bg-green-100 text-green-500' : 'bg-orange-100 text-orange-500'"
                 class="w-24 h-24 rounded-full flex items-center justify-center text-5xl mx-auto mb-6 animate-bounce">
                {{ resultStatus === 'success' ? '🏆' : '🤔' }}
            </div>

            <!-- هنا يظهر اسم الطفل الحقيقي -->
            <h3 class="text-3xl font-black text-gray-800 mb-4">
                {{ resultStatus === 'success' ? 'عمل رائع يا ' + user.name + '!' : 'محاولة جيدة يا ' + user.name + '!' }}
            </h3>

            <p class="text-gray-600 font-bold mb-8">
                {{ resultStatus === 'success' ? 'لقد أثبتّ أنك مهندس برمجيات ذكي جداً.' : 'الروبوت يحتاج مساعدة إضافية، هل يمكنك إضافة أمر آخر؟' }}
            </p>

            <button @click="closeFeedback"
                    :class="resultStatus === 'success' ? 'bg-green-500 shadow-green-200' : 'bg-orange-500 shadow-orange-200'"
                    class="w-full py-4 rounded-2xl text-white font-black text-xl shadow-lg hover:scale-105 transition-transform">
                {{ resultStatus === 'success' ? 'التحدي التالي ➔' : 'سأحاول مجدداً 💪' }}
            </button>
        </div>
    </div>
</transition>
















<div class="mt-12 px-4 md:px-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">🗓️</span>
                    <h2 class="text-2xl font-bold text-slate-800">ورشاتنا وحصصنا القادمة</h2>
                </div>
                <span class="bg-purple-50 text-purple-600 text-sm font-semibold px-4 py-1.5 rounded-full shadow-sm">
                    {{ sessions?.length || 0 }} ورشات بانتظارك
                </span>
            </div>

            <div v-if="!sessions || sessions.length === 0" class="bg-white border border-slate-100 rounded-3xl p-8 text-center shadow-sm">
                <span class="text-5xl block mb-3">🎉</span>
                <p class="text-slate-500 font-medium">لا يوجد ورشات مجدولة قريبة حالياً، استمتع بإنهاء التحديات المفتوحة!</p>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="session in sessions" :key="session.id"
                     class="bg-white border border-indigo-50/60 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">

                    <div class="absolute top-4 left-4">
                        <span :class="session.type?.toLowerCase() === 'plugged'
                            ? 'bg-blue-50 text-blue-600 border border-blue-100'
                            : 'bg-emerald-50 text-emerald-600 border border-emerald-100'"
                              class="text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ session.type?.toLowerCase() === 'plugged' ? '💻 متصل الأجهزة' : '🧩 أنشطة يدوية' }}
                        </span>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-lg font-bold text-slate-800 group-hover:text-indigo-600 transition-colors duration-200 mb-4">
                            {{ session.title }}
                        </h3>

                        <div class="space-y-2.5 text-sm text-slate-500">
                            <div class="flex items-center gap-2">
                                <span class="text-base">⏰</span>
                                <span class="font-medium text-slate-600">{{ session.time }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-base">📍</span>
                                <span class="font-medium text-slate-600">{{ session.location }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="absolute bottom-0 right-0 left-0 h-1.5 bg-gradient-to-r"
                         :class="session.type?.toLowerCase() === 'plugged' ? 'from-blue-400 to-indigo-500' : 'from-emerald-400 to-teal-500'">
                    </div>
                </div>
            </div>
        </div>
<div v-if="props.resources && props.resources.length > 0" class="mt-12 animate-fade-in">
  <div class="flex items-center gap-3 mb-6 justify-start select-none" style="direction: rtl;">
    <span class="text-[28px]">🎈</span>
    <h3 class="text-[24px] font-extrabold text-slate-800 tracking-tight">حقيبة الألعاب والأنشطة الممتعة</h3>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" style="direction: rtl;">
    <div
      v-for="res in props.resources"
      :key="res.id"
      class="bg-white rounded-[32px] p-6 shadow-sm border-2 border-purple-50/60 flex flex-col justify-between transition-all duration-300 hover:scale-[1.02] hover:shadow-md hover:border-purple-100 text-right group"
    >
      <div>
        <div class="flex flex-wrap gap-2 mb-4">
          <span :class="[
            'px-4 py-1.5 rounded-full text-[13px] font-bold text-white shadow-sm transition-colors',
            res.track_type === 'unplugged' ? 'bg-amber-500' : 'bg-emerald-500'
          ]">
            {{ res.track_type === 'unplugged' ? '🧩 ذكاء بدون حاسوب' : '💻 متعة برمجية' }}
          </span>

          <span class="bg-purple-50 text-purple-600 px-3 py-1.5 rounded-full text-[12px] font-bold uppercase tracking-wide">
            {{ res.resource_type === 'worksheet' ? '📝 ورقة عمل' : res.resource_type === 'guide' ? '📖 دليل تفاعلي' : '📋 خطة دراسية' }}
          </span>
        </div>

        <h4 class="text-[21px] font-black text-slate-800 mb-2 group-hover:text-purple-600 transition-colors duration-200">
          {{ res.title }}
        </h4>
        <p class="text-slate-500 text-[15px] leading-relaxed mb-6 font-medium">
          {{ res.description }}
        </p>
      </div>

      <a
        :href="res.file_path"
        :download="res.title + '.pdf'"
        class="w-full text-center bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-4 rounded-[22px] font-bold text-[16px] block shadow-md shadow-purple-200 hover:shadow-lg hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 active:scale-[0.99]"
      >
        احصل على كتيب الألعاب الفخم (PDF) 🚀
      </a>
    </div>
  </div>
</div>













<div v-if="feedbacks && feedbacks.length > 0" class="mt-10">
  <h3 class="text-[22px] font-bold text-slate-800 mb-6 flex items-center gap-2">
    <span>💌</span> رسائل وتوجيهات المعلمة
  </h3>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div v-for="fb in feedbacks" :key="fb.id" class="bg-gradient-to-br from-purple-50 to-white border border-purple-100 rounded-[24px] p-6 shadow-sm relative overflow-hidden transition-all hover:shadow-md">

      <div class="flex items-center gap-3 mb-4 relative z-10">
        <div class="w-10 h-10 rounded-full bg-purple-600 flex items-center justify-center text-white text-[18px] font-bold">
          👩‍🏫
        </div>
        <div>
          <h4 class="font-bold text-slate-800 text-[16px]">{{ fb.teacher_name }}</h4>
          <span class="text-[12px] text-gray-400">{{ fb.date }}</span>
        </div>
      </div>

      <p class="text-slate-600 text-[15px] leading-relaxed bg-white/75 p-4 rounded-2xl border border-purple-50/50 relative z-10 text-right" style="direction: rtl;">
        {{ fb.comment }}
      </p>
    </div>
  </div>
</div>



</template>

<style scoped>
.soft-shadow {
  box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.04);
}

/* الألوان الديناميكية لـ Tailwind أحياناً تحتاج تعريف صريح إذا كانت تأتي من Variable */
.bg-purple-500 { background-color: #a855f7; }
.bg-blue-500 { background-color: #3b82f6; }
.bg-green-500 { background-color: #22c55e; }
.bg-gray-400 { background-color: #9ca3af; }
</style>
