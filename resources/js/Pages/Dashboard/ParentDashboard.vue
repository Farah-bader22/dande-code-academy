<template>
  <div class="min-h-screen bg-gray-50 p-4 md:p-6 lg:p-12 font-sans text-gray-900">

    <header class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
      <div>
        <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 tracking-tight">أهلاً {{ parentName }} 👋</h1>
        <p class="text-gray-500 mt-2 text-base md:text-lg">لوحة متابعة أبطال البرمجة - Dande Code</p>
      </div>
      <div class="bg-indigo-600 p-6 rounded-3xl shadow-xl shadow-indigo-200 w-full md:w-auto">
        <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest">إجمالي نقاط أبنائك</p>
        <p class="text-4xl font-black mt-1 text-white">⭐ {{ totalPoints }}</p>
      </div>
    </header>

    <main class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
      <div v-for="child in children" :key="child.id"
           class="bg-white p-6 md:p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-300 w-full">

        <div class="flex items-center gap-4 md:gap-6 mb-8">
          <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-3xl flex items-center justify-center text-2xl md:text-3xl text-white shadow-lg shrink-0">
            {{ child.name?.charAt(0) || '?' }}
          </div>
          <div>
            <h2 class="text-2xl md:text-3xl font-black text-gray-900">{{ child.name }}</h2>
            <span class="inline-block mt-2 bg-indigo-50 text-indigo-700 text-[10px] px-3 py-1 rounded-full font-bold uppercase tracking-wider">مستوى: مبرمج صاعد</span>
          </div>
        </div>

        <div class="grid grid-cols-3 gap-2 md:gap-4 mb-8">
          <div class="bg-gray-50 p-3 md:p-4 rounded-2xl border border-gray-100 text-center">
            <p class="text-gray-400 text-[9px] uppercase font-bold">المهام</p>
            <p class="text-lg md:text-xl font-black text-gray-800">{{ child.submissions?.length || 0 }}</p>
          </div>
          <div class="bg-gray-50 p-3 md:p-4 rounded-2xl border border-gray-100 text-center">
            <p class="text-gray-400 text-[9px] uppercase font-bold">المستوى</p>
            <p class="text-lg md:text-xl font-black text-gray-800">04</p>
          </div>
          <div class="bg-gray-50 p-3 md:p-4 rounded-2xl border border-gray-100 text-center">
            <p class="text-gray-400 text-[9px] uppercase font-bold">النقاط</p>
            <p class="text-lg md:text-xl font-black text-gray-800">{{ child.points || 0 }}</p>
          </div>
        </div>

        <div class="mb-8">
          <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase mb-2">
            <span>نسبة الإنجاز في الكورس</span>
            <span class="text-indigo-600">68%</span>
          </div>
          <div class="h-4 bg-gray-100 rounded-full overflow-hidden p-1">
            <div class="h-full bg-indigo-600 rounded-full w-[68%] shadow-lg"></div>
          </div>
        </div>

        <div class="chat-container flex flex-col h-[300px] md:h-[400px] border border-purple-100 rounded-2xl p-4 bg-white shadow-sm">
          <div class="flex-1 overflow-y-auto p-2 space-y-4">
            <div v-for="msg in messages" :key="msg.id"
                 :class="msg.sender_id === auth.user.id ? 'text-right' : 'text-left'">
              <div :class="msg.sender_id === auth.user.id ? 'bg-purple-600 text-white rounded-l-xl rounded-tr-xl' : 'bg-gray-100 text-slate-700 rounded-r-xl rounded-tl-xl'"
                   class="inline-block px-4 py-2 max-w-[90%] md:max-w-[80%] text-[14px] md:text-[15px]">
                {{ msg.content }}
              </div>
            </div>
          </div>

          <div class="mt-4 flex gap-2 border-t pt-4">
            <input v-model="newMessage"
                   @keyup.enter="sendMessage"
                   class="flex-1 border-none bg-slate-100 rounded-full px-4 md:px-5 py-2 md:py-3 text-sm md:text-base focus:ring-2 focus:ring-purple-400"
                   placeholder="اكتب رسالة للمعلمة...">
            <button @click="sendMessage"
                    class="bg-purple-600 text-white px-4 md:px-6 py-2 rounded-full font-bold text-sm md:text-base hover:bg-purple-700 transition">
              إرسال
            </button>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  children: { type: Array, default: () => [] },
  parentName: String,
  messages: { type: Array, default: () => [] },
  auth: Object
});

const newMessage = ref('');

const totalPoints = computed(() => {
  return props.children.reduce((sum, child) => sum + (child.points || 0), 0);
});

const sendMessage = () => {
  if (!newMessage.value.trim()) return;

  router.post('/chat/send', {
    content: newMessage.value,
    receiver_id: 1 // تأكدي من ضبط ID المعلمة الصحيح هنا
  }, {
    onSuccess: () => {
      newMessage.value = '';
    },
    preserveScroll: true // ليظل الأب في نفس مكان الشات بعد الإرسال
  });
};
</script>
