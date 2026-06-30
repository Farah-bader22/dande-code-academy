<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

// تمت إضافة currentUserId لاستقبال معرف المستخدم الحالي من الكنترولر
const props = defineProps(['conversations', 'messages', 'selectedId', 'currentUserId']);
const replyContent = ref('');

const selectUser = (id) => {
    router.visit(route('chat.index', { receiver_id: id }), { preserveState: true });
};

const sendReply = () => {
    if (!replyContent.value.trim() || !props.selectedId) return;
    router.post(route('chat.send'), {
        receiver_id: props.selectedId,
        content: replyContent.value
    }, { onSuccess: () => replyContent.value = '' });
};
</script>

<template>
  <div class="min-h-screen bg-gray-100 p-4 md:p-6">
    <div class="max-w-5xl mx-auto flex h-[85vh] bg-white rounded-3xl shadow-2xl overflow-hidden">

      <div :class="selectedId ? 'hidden md:flex' : 'flex'" class="w-full md:w-1/3 flex-col border-r bg-gray-50">
        <div class="p-6 font-bold text-xl border-b bg-white">الرسائل</div>
        <div class="flex-1 overflow-y-auto">
          <div v-for="conv in conversations" :key="conv.id"
               @click="selectUser(conv.receiver_id)"
               class="p-4 border-b cursor-pointer hover:bg-purple-100 transition-colors">
            <p class="font-bold text-gray-800">{{ conv.receiver?.name || 'محادثة' }}</p>
          </div>
        </div>
      </div>

      <div :class="selectedId ? 'flex' : 'hidden md:flex'" class="w-full md:w-2/3 flex-col bg-white">
        <div v-if="selectedId" class="p-4 border-b flex items-center">
            <button @click="selectedId = null" class="md:hidden mr-2 text-purple-600">← عودة</button>
            <span class="font-bold">المحادثة</span>
        </div>

   <div v-if="selectedId" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50 flex flex-col">

  <div v-for="msg in messages" :key="msg.id"
       :style="{ 'align-self': msg.sender_id == currentUserId ? 'flex-end' : 'flex-start' }"
       class="flex w-full">

    <div class="px-5 py-3 rounded-2xl max-w-[80%] shadow-sm"
         :class="msg.sender_id == currentUserId
            ? 'bg-purple-600 text-white rounded-tr-none'
            : 'bg-white text-gray-800 border border-gray-200 rounded-tl-none'">
      {{ msg.content }}
    </div>
  </div>

</div>

        <div v-else class="flex-1 flex items-center justify-center text-gray-400">
            اختر محادثة من القائمة للبدء
        </div>

        <div v-if="selectedId" class="p-4 border-t bg-white flex gap-2">
          <input v-model="replyContent"
                 @keyup.enter="sendReply"
                 class="flex-1 border-gray-200 border rounded-full px-5 focus:ring-2 focus:ring-purple-500 outline-none"
                 placeholder="اكتب رسالتك...">
          <button @click="sendReply"
                  class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-full transition-all">
            إرسال
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
