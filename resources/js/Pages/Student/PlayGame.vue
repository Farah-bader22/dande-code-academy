<template>
    <div class="min-h-screen bg-[#EEF2FF] p-3 sm:p-6 flex flex-col items-center selection:bg-indigo-100">
        <div class="w-full max-w-6xl flex flex-col sm:flex-row justify-between items-center gap-4 mb-6 bg-white p-5 sm:p-6 rounded-[30px] shadow-sm">
            <div class="text-center sm:text-left">
                <h1 class="text-xl sm:text-2xl font-black text-indigo-900 tracking-tight">{{ assignment.title }}</h1>
                <p class="text-indigo-500 font-medium text-sm mt-0.5">ساعد الروبوت للوصول إلى النجمة! 🚀</p>
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <button @click="resetGame"
                        class="flex-1 sm:flex-none px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 active:scale-95 transition-all text-sm">
                    إعادة تعيين 🧼
                </button>
                <button @click="runCode" :disabled="isRunning || program.length === 0"
                        class="flex-1 sm:flex-none px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold shadow-md shadow-indigo-100 hover:shadow-indigo-200 active:scale-95 transition-all disabled:opacity-40 disabled:scale-100 text-sm">
                    {{ isRunning ? 'جاري التشغيل... ⚙️' : 'تشغيل الكود ▶' }}
                </button>
            </div>
        </div>

        <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <div class="lg:col-span-5 bg-white p-4 sm:p-6 rounded-[36px] shadow-sm border border-slate-100 flex flex-col items-center justify-center">
                <div class="w-full max-w-[420px] aspect-square bg-indigo-50/70 p-3 rounded-2xl shadow-inner flex items-center justify-center">
                    <div class="grid w-full h-full gap-1.5" :style="gridStyle">
                        <div v-for="(cell, index) in assignment.game_data.layout" :key="index"
                             class="bg-white rounded-xl border border-indigo-50 flex items-center justify-center text-2xl sm:text-3xl shadow-sm relative overflow-hidden select-none">

                            <span v-if="cell === 'star'" class="filter drop-shadow-sm animate-bounce">⭐</span>
                            <span v-else-if="cell === 'wall'">🧱</span>
                            <span v-else-if="cell === 'candy'">🍦</span>
                            <span v-else-if="cell === 'enemy'">👾</span>

                            <div v-if="index === robotPosition"
                                 class="absolute inset-0 flex items-center justify-center transform transition-all duration-300 ease-out z-10 select-none text-3xl sm:text-4xl"
                                 :style="{ transform: `rotate(${robotRotation}deg)` }">
                                🤖
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 bg-white p-5 sm:p-8 rounded-[36px] shadow-sm border border-slate-100">
                <h3 class="font-extrabold text-indigo-900 mb-4 flex items-center gap-2 text-lg">
                    <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">⚙️</span>
                    منطقة البرمجة المرئية
                </h3>

                <div class="grid grid-cols-3 gap-2.5 mb-6">
                    <button v-for="cmd in commands" :key="cmd.id"
                            @click="addCommand(cmd.id)"
                            :disabled="isRunning"
                            class="p-3 bg-indigo-50/60 text-indigo-700 rounded-2xl font-bold border-2 border-transparent hover:border-indigo-400 hover:bg-indigo-50 active:scale-95 disabled:opacity-50 transition-all flex flex-col sm:flex-row items-center justify-center gap-1.5 text-xs sm:text-sm">
                        <span class="text-xl sm:text-2xl">{{ cmd.icon }}</span>
                        <span>{{ cmd.label }}</span>
                    </button>
                </div>

                <div class="space-y-2 min-h-[240px] max-h-[360px] overflow-y-auto border-2 border-dashed border-indigo-100 rounded-2xl p-4 bg-slate-50/50">
                    <transition-group name="list">
                        <div v-for="(action, idx) in program" :key="idx"
                             class="p-3 bg-white border border-indigo-100 rounded-xl flex justify-between items-center shadow-sm">
                            <span class="font-bold text-indigo-700 text-sm">
                                <span class="text-indigo-300 ml-1">#{{ idx + 1 }}</span>
                                {{ getCommandIcon(action) }} {{ getCommandLabel(action) }}
                            </span>
                            <button @click="removeCommand(idx)" :disabled="isRunning" class="text-slate-300 hover:text-red-500 disabled:opacity-30 p-1 font-bold">✕</button>
                        </div>
                    </transition-group>

                    <div v-if="program.length === 0" class="flex flex-col items-center justify-center pt-12 text-slate-400 gap-2">
                        <span class="text-3xl animate-pulse">🧩</span>
                        <p class="text-xs browser-default sm:text-sm font-medium">اضغط على الأزرار في الأعلى لبناء خطوات الروبوت مسبقاً!</p>
                    </div>
                </div>
            </div>

        </div>
<button @click="router.post('/student/assignments/' + props.assignment.id + '/submit', { score: 100 })"
        class="bg-red-500 text-white p-2">
    اختبار إرسال النقاط (Test)
</button>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
// تأكدي إنكِ مستوردة route إذا كنتِ بتستخدميها، وإلا استخدمي الرابط مباشرة

const props = defineProps({ assignment: Object });

// استخراج الأبعاد ديناميكياً من الخريطة المحفوظة تلافياً للمشاكل
const rows = computed(() => props.assignment.game_data.dimensions?.rows || 6);
const cols = computed(() => props.assignment.game_data.dimensions?.cols || 6);

// إدارة الحالة
const robotPosition = ref(0);
const robotRotation = ref(0); // تبدأ من صفر وتساوي الاتجاه للأعلى دائماً
const isRunning = ref(false);
const program = ref([]);

// تهيئة اللعبة وإيجاد موقع الروبوت الأصلي
onMounted(() => {
    resetGame();
});

const commands = [
    { id: 'forward', label: 'تحرك للأمام', icon: '🚀' },
    { id: 'right', label: 'استدر يميناً', icon: '↪️' },
    { id: 'left', label: 'استدر يساراً', icon: '↩️' },
];

const addCommand = (cmd) => program.value.push(cmd);
const removeCommand = (idx) => program.value.splice(idx, 1);
const getCommandLabel = (id) => commands.find(c => c.id === id).label;
const getCommandIcon = (id) => commands.find(c => c.id === id).icon;

// تصميم الـ Grid المتجاوب ذكياً حسب الأبعاد الديناميكية القادمة من قاعدة البيانات
const gridStyle = computed(() => {
    return {
        gridTemplateColumns: `repeat(${cols.value}, minmax(0, 1fr))`,
        gridTemplateRows: `repeat(${rows.value}, minmax(0, 1fr))`
    };
});

const resetGame = () => {
    robotPosition.value = props.assignment.game_data.layout.indexOf('robot');
    robotRotation.value = 0; // يرجع الروبوت ينظر للأعلى عند التصفير
    isRunning.value = false;
};

// المحرك المنطقي المطور لتنفيذ خطوات الطفل خطوة خطوة متزامنة وبدون مشاكل تدوير
const runCode = async () => {
    isRunning.value = true;

    for (const action of program.value) {
        // ننتظر 500 ملي ثانية لكي يلاحظ الطفل تسلسل الحركة البصرية
        await new Promise(resolve => setTimeout(resolve, 500));

        if (action === 'right') {
            robotRotation.value += 90;
        } else if (action === 'left') {
            robotRotation.value -= 90;
        } else if (action === 'forward') {
            const nextPos = calculateNextPosition(robotPosition.value, robotRotation.value);

            if (nextPos !== null && isValidMove(nextPos)) {
                robotPosition.value = nextPos;

                // ميزة تجميع المكافآت اللذيذة
                if (props.assignment.game_data.layout[robotPosition.value] === 'candy') {
                    alert("يممممي! حصلت على قطعة حلوى لزيزة 🍦😋");
                }

                // ميزة الاصطدام بوحش الأخطاء البرمجية
                if (props.assignment.game_data.layout[robotPosition.value] === 'enemy') {
                    alert("أوه لا! هناك وحش برمجيات هنا 👾 حاول مجدداً وتجنبه!");
                    resetGame();
                    return;
                }
            } else {
                alert("احترس يا بطل! لقد اصطدمت بالحافة أو بالحائط! 🧱🤕");
                resetGame();
                return;
            }
        }

        // فحص الفوز والوصول للهدف النهائي في كل خطوة حركية
        if (props.assignment.game_data.layout[robotPosition.value] === 'star') {
            await new Promise(resolve => setTimeout(resolve, 200));
// بدلاً من استخدام route(...)، سنستخدم الرابط النصي مباشرة
// لاحظي أننا أضفنا student إضافية في الرابط ليتطابق مع الـ route:list
router.post(`/student/student/assignments/${props.assignment.id}/submit`, {
    score: 100
});
    // ----------------------------------------

    alert("عبقري مذهل! 🥳🎉 لقد وصلت للنجمة وحللت التحدي بنجاح!");
    resetGame();
    return;
        }
    }

    // إذا انتهت الخطوات ولم يصل الروبوت للنجمة
    isRunning.value = false;
    alert("انتهت الخطوات ولم يصل الروبوت بعد! أعد التفكير وحاول مرة أخرى 🧭🤖");
};

const calculateNextPosition = (pos, rotation) => {
    // تحويل الزاوية دائماً إلى قيمة موجبة دائرية صحيحة بين 0 و 270
    const angle = ((rotation % 360) + 360) % 360;

    const currentRow = Math.floor(pos / cols.value);
    const currentCol = pos % cols.value;

    if (angle === 0) {
        // ⬆️ الروبوت ينظر للأعلى: التحرك للأمام يعني ينقص سطر كامل
        return currentRow > 0 ? pos - cols.value : null;
    }
    if (angle === 90) {
        // ➡️ الروبوت ينظر لليمين: التحرك للأمام يعني يزيد عمود واحد (+1)
        return currentCol < cols.value - 1 ? pos + 1 : null;
    }
    if (angle === 180) {
        // ⬇️ الروبوت ينظر للأسفل: التحرك للأمام يعني يزيد سطر كامل
        return currentRow < rows.value - 1 ? pos + cols.value : null;
    }
    if (angle === 270) {
        // ⬅️ الروبوت ينظر لليسار: التحرك للأمام يعني ينقص عمود واحد (-1)
        return currentCol > 0 ? pos - 1 : null;
    }
    return pos;
};

const isValidMove = (pos) => {
    if (pos === null) return false; // منع الحركة خارج نطاق حواف الشبكة المحددة
    const totalCells = rows.value * cols.value;
    return pos >= 0 && pos < totalCells && props.assignment.game_data.layout[pos] !== 'wall';

    router.post(route('student.submit', props.assignment.id), {
    score: 100
}, {
    onSuccess: () => {
        console.log("تم إرسال النقاط بنجاح!");
    },
    onError: (errors) => {
        console.error("حدث خطأ:", errors);
    }
});
};


</script>

<style scoped>
/* إضافة أنيميشن لطيف لتركيب وحذف البلوكات البرمجية بمرونة */
.list-enter-active, .list-leave-active {
    transition: all 0.3s ease;
}
.list-enter-from {
    opacity: 0;
    transform: translateY(-10px);
}
.list-leave-to {
    opacity: 0;
    transform: translateX(20px);
}
</style>
