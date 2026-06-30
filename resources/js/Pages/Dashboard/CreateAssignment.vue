<template>
    <div class="min-h-screen bg-[#f8fafc] p-3 sm:p-6 md:p-8 selection:bg-purple-100">
        <!-- الهيدر العلوي المتجاوب -->
        <div class="max-w-7xl mx-auto mb-6 md:mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 sm:p-6 rounded-[24px] border border-slate-100 shadow-sm">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-[#1e293b] tracking-tight">Create Coding Challenge</h1>
                <p class="text-slate-500 text-sm mt-1">Design an interactive, logical layout for your little engineers.</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <button v-if="form.id" @click="deleteAssignment"
                        class="flex-1 sm:flex-none bg-rose-50 border border-rose-200 text-rose-600 px-5 py-3 rounded-xl font-bold hover:bg-rose-100 active:scale-95 transition-all flex items-center justify-center gap-2 text-sm">
                    <i data-feather="trash-2" class="w-4 h-4"></i>
                    Delete
                </button>

                <button @click="saveAssignment"
                        class="flex-1 sm:flex-none bg-gradient-to-r from-[#8b5cf6] to-[#a855f7] text-white px-7 py-3 rounded-xl font-bold shadow-lg shadow-purple-100 hover:shadow-purple-200 active:scale-95 transition-all flex items-center justify-center gap-2 text-sm">
                    <i data-feather="send" class="w-4 h-4"></i>
                    Publish to Kids
                </button>
            </div>
        </div>

        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
            <!-- العمود الأيسر: لوحة الإعدادات والأدوات -->
            <div class="lg:col-span-1 space-y-6">
                <!-- 1. البيانات الأساسية -->
                <div class="bg-white p-5 sm:p-6 rounded-[24px] shadow-sm border border-slate-100">
                    <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <span class="p-1.5 bg-purple-50 text-purple-600 rounded-lg"><i data-feather="edit-3" class="w-4 h-4"></i></span>
                        Basic Information
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Challenge Title</label>
                            <input v-model="form.title" type="text" placeholder="e.g., The Robot's First Steps 🤖"
                                   class="w-full p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none transition-all text-sm text-slate-700 font-medium placeholder:text-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Mission Instructions</label>
                            <textarea v-model="form.description" rows="3" placeholder="Write a fun story or instructions for the kids..."
                                      class="w-full p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none transition-all text-sm text-slate-700 font-medium placeholder:text-slate-300 resize-none"></textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. التحكم بحجم المتاهة (الميزة الاحترافية الجديدة) -->
                <div class="bg-white p-5 sm:p-6 rounded-[24px] shadow-sm border border-slate-100">
                    <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <span class="p-1.5 bg-blue-50 text-blue-600 rounded-lg"><i data-feather="grid" class="w-4 h-4"></i></span>
                        Grid Dimensions
                    </h2>
                    <div class="grid grid-cols-3 gap-2">
                        <button v-for="size in gridSizes" :key="size.label"
                                @click="changeGridSize(size.rows, size.cols)"
                                :class="['p-3 rounded-xl border-2 font-bold text-xs transition-all text-center flex flex-col items-center justify-center gap-1',
                                         gridRows === size.rows ? 'border-purple-500 bg-purple-50 text-purple-700' : 'border-slate-100 bg-slate-50 text-slate-500 hover:border-slate-200']">
                            <span class="text-sm">{{ size.label }}</span>
                            <span class="opacity-60 font-normal">{{ size.rows }}x{{ size.cols }} Blocks</span>
                        </button>
                    </div>
                </div>

                <!-- 3. أدوات وعناصر اللعبة المحدثة -->
                <div class="bg-white p-5 sm:p-6 rounded-[24px] shadow-sm border border-slate-100">
                    <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <span class="p-1.5 bg-pink-50 text-pink-600 rounded-lg"><i data-feather="cpu" class="w-4 h-4"></i></span>
                        Game Elements
                    </h2>
                    <div class="grid grid-cols-2 gap-3">
                        <button v-for="tool in tools" :key="tool.id"
                                @click="selectedTool = tool.id"
                                :class="['p-3.5 rounded-2xl border-2 transition-all flex flex-col items-center gap-1.5 active:scale-95',
                                         selectedTool === tool.id ? 'border-purple-500 bg-purple-50 text-purple-700 shadow-sm' : 'border-slate-100 bg-slate-50 text-slate-500 hover:border-purple-200']">
                            <span class="text-3xl filter drop-shadow-sm transform group-hover:scale-110 transition-transform">{{ tool.icon }}</span>
                            <span class="text-xs font-bold tracking-wide">{{ tool.name }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- العمود الأيمن والأكبر: لوحة الرسم والمحاكاة الفخمة -->
            <div class="lg:col-span-2">
                <div class="bg-white p-4 sm:p-6 md:p-8 rounded-[32px] shadow-sm border border-slate-100 flex flex-col items-center justify-center min-h-[450px]">
                    <!-- دليل الخريطة اللطيف للأطفال والمعلمين -->
                    <div class="mb-6 flex flex-wrap justify-center gap-4 text-xs font-bold text-slate-500">
                        <span class="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-full"><div class="w-2.5 h-2.5 bg-blue-500 rounded-full animate-pulse"></div> Start</span>
                        <span class="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-full"><div class="w-2.5 h-2.5 bg-yellow-400 rounded-full animate-pulse"></div> Goal</span>
                        <span class="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-full"><div class="w-2.5 h-2.5 bg-slate-800 rounded-full"></div> Wall</span>
                        <span class="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-full"><div class="w-2.5 h-2.5 bg-pink-400 rounded-full"></div> Treat</span>
                    </div>

                    <!-- الـ Grid المتجاوب ذكياً حسب الأبعاد والأعمدة المحددة -->
                    <div class="w-full max-w-[500px] aspect-square bg-slate-50 p-3 rounded-2xl border-2 border-dashed border-slate-200 shadow-inner flex items-center justify-center">
                        <div class="grid w-full h-full gap-2" :style="gridStyle">
                            <div v-for="(cell, index) in grid" :key="index"
                                 @click="placeObject(index)"
                                 class="bg-white rounded-xl border border-slate-150 cursor-pointer flex items-center justify-center text-2xl sm:text-3xl hover:bg-purple-50 hover:border-purple-300 hover:rotate-2 select-none active:scale-90 transition-all shadow-sm relative group">

                                <!-- رسم العناصر بداخل المربعات -->
                                <span v-if="cell === 'robot'">🤖</span>
                                <span v-else-if="cell === 'star'">⭐</span>
                                <span v-else-if="cell === 'wall'">🧱</span>
                                <span v-else-if="cell === 'candy'">🍦</span>
                                <span v-else-if="cell === 'enemy'">👾</span>

                                <!-- تلميح صغير يظهر عند الهوفر فارغ -->
                                <span v-if="!cell" class="opacity-0 group-hover:opacity-30 text-xs font-black transition-opacity text-purple-600">
                                    +
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 text-center text-slate-400 text-xs font-medium flex items-center gap-1.5 bg-slate-50 px-4 py-2 rounded-xl">
                        <i data-feather="info" class="w-3.5 h-3.5 text-purple-500"></i>
                        Tap any block on the map to paint with the active game item.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import feather from 'feather-icons';

// أحجام الخرائط المتاحة
const gridSizes = [
    { label: 'Easy 🚀', rows: 4, cols: 4 },
    { label: 'Medium 🧩', rows: 6, cols: 6 },
    { label: 'Hard 🏆', rows: 8, cols: 8 }
];

const gridRows = ref(6);
const gridCols = ref(6);

// تهيئة عناصر اللعبة الاحترافية والموسعة
const tools = [
    { id: 'robot', name: 'Start Robot', icon: '🤖' },
    { id: 'star', name: 'Target Star', icon: '⭐' },
    { id: 'wall', name: 'Solid Wall', icon: '🧱' },
    { id: 'candy', name: 'Bonus Sweet', icon: '🍦' },
    { id: 'enemy', name: 'Bug Monster', icon: '👾' },
    { id: 'clear', name: 'Magic Erase', icon: '🧼' },
];

const selectedTool = ref('robot');

// بناء مصفوفة الشبكة ديناميكياً
const grid = ref(Array(gridRows.value * gridCols.value).fill(null));

// ستايل الـ Grid المتجاوب الديناميكي لحساب الأعمدة بدقة
const gridStyle = computed(() => {
    return {
        gridTemplateColumns: `repeat(${gridCols.value}, minmax(0, 1fr))`,
        gridTemplateRows: `repeat(${gridRows.value}, minmax(0, 1fr))`
    };
});

// دالة لتغيير أبعاد المتاهة ومسح المحتوى القديم تلافياً للأخطاء
const changeGridSize = (rows, cols) => {
    gridRows.value = rows;
    gridCols.value = cols;
    grid.value = Array(rows * cols).fill(null);
};

const form = useForm({
    id: null,
    title: '',
    description: '',
    game_data: null
});

// توزيع العناصر على الخريطة بذكاء هندسي يمنع تكرار اللاعب أو الهدف
const placeObject = (index) => {
    if (selectedTool.value === 'clear') {
        grid.value[index] = null;
        return;
    }

    // الروبوت والنجمة يجب أن يتواجد منهم نسخة واحدة فقط في المتاهة بأكملها
    if (selectedTool.value === 'robot') {
        grid.value = grid.value.map(cell => cell === 'robot' ? null : cell);
    }
    if (selectedTool.value === 'star') {
        grid.value = grid.value.map(cell => cell === 'star' ? null : cell);
    }

    grid.value[index] = selectedTool.value;
};

// إرسال البيانات للباك إند
const saveAssignment = () => {
    if (!form.title.trim()) {
        alert('Please add an inspiring title for the assignment! 🌟');
        return;
    }

    // التثبت من وجود روبوت وهدف على الأقل قبل الحفظ لمنع كراش اللعبة عند الطلاب
    if (!grid.value.includes('robot') || !grid.value.includes('star')) {
        alert('Every map needs exactly 1 Robot to start and 1 Target Star to win! 🎮');
        return;
    }

    form.game_data = {
        layout: grid.value,
        dimensions: { rows: gridRows.value, cols: gridCols.value }
    };

    form.post(route('teacher.assignments.store'), {
        onSuccess: () => {
            alert('Challenge published beautifully to all kids! 🚀✨');
            form.reset();
            grid.value = Array(gridRows.value * gridCols.value).fill(null);
        },
        onError: () => alert('Make sure all text inputs are valid and try again.')
    });
};

// دالة الحذف المرتبطة بالـ Controller
const deleteAssignment = () => {
    if (confirm('Are you sure you want to permanently delete this challenge? It will vanish from the kids\' screens! ⚠️')) {
        router.delete(route('teacher.assignments.destroy', form.id), {
            onSuccess: () => {
                alert('Challenge cleared smoothly from the system. 🧼');
                router.visit(route('teacher.dashboard'));
            },
            onError: (err) => {
                console.error(err);
                alert('Could not delete. Check backend logs.');
            }
        });
    }
};

onMounted(() => {
    nextTick(() => {
        feather.replace();
    });
});
</script>

<style scoped>
/* حركات إضافية ناعمة لتجربة مستخدم ملوكية */
textarea, input {
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
