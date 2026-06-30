<template>
  <div class="min-h-screen bg-[#f8fafc] p-6 sm:p-8 font-sans">
    <div class="max-w-7xl mx-auto">

      <div class="mb-10">
        <Link :href="route('teacher.dashboard')" class="text-gray-500 hover:text-gray-700 flex items-center gap-2 mb-3 text-[16px] font-medium transition-colors">
          ← Back to Dashboard
        </Link>
        <h1 class="text-[28px] sm:text-[34px] font-extrabold text-gray-900">
          Schedule Learning Sessions
        </h1>
        <p class="text-gray-500 mt-1 text-[16px]">Plan future classes, assign tracks (Plugged/Unplugged), and set times for DandeCode kids.</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 h-fit">
          <h2 class="text-[20px] font-bold text-gray-800 mb-4">New Session Info</h2>

          <form @submit.prevent="submitSession" class="space-y-4">
            <div>
              <label class="block text-sm font-bold text-gray-600 mb-1.5">Session Title</label>
              <input type="text" v-model="form.title" placeholder="e.g., Robot Grid Movement" class="w-full px-4 py-3 rounded-[14px] border border-gray-200 outline-none focus:border-[#22c55e] text-sm" required />
            </div>

            <div>
              <label class="block text-sm font-bold text-gray-600 mb-1.5">Select Classroom</label>
              <select v-model="form.classroom_id" class="w-full px-4 py-3 rounded-[14px] border border-gray-200 outline-none focus:border-[#22c55e] text-sm bg-white" required>
                <option value="" disabled>Choose a class</option>
                <option v-for="cls in classes" :key="cls.id" :value="cls.id">{{ cls.name }}</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-bold text-gray-600 mb-1.5">Track Type</label>
              <div class="grid grid-cols-2 gap-3">
                <button type="button" @click="form.track_type = 'plugged'" :class="['py-2.5 ' + 'rounded-[12px] font-bold text-sm transition-all border', form.track_type === 'plugged' ? 'bg-emerald-50 border-emerald-500 text-emerald-600' : 'bg-gray-50 border-gray-100 text-gray-500']">Plugged</button>
                <button type="button" @click="form.track_type = 'unplugged'" :class="['py-2.5 ' + 'rounded-[12px] font-bold text-sm transition-all border', form.track_type === 'unplugged' ? 'bg-orange-50 border-orange-500 text-orange-600' : 'bg-gray-50 border-gray-100 text-gray-500']">Unplugged</button>
              </div>
            </div>

            <div>
              <label class="block text-sm font-bold text-gray-600 mb-1.5">Date & Time</label>
              <input type="datetime-local" v-model="form.scheduled_at" class="w-full px-4 py-3 rounded-[14px] border border-gray-200 outline-none focus:border-[#22c55e] text-sm" required />
            </div>

            <div>
              <label class="block text-sm font-bold text-gray-600 mb-1.5">Location / Room</label>
              <input type="text" v-model="form.location_or_link" placeholder="e.g., Computer Lab B" class="w-full px-4 py-3 rounded-[14px] border border-gray-200 outline-none focus:border-[#22c55e] text-sm" />
            </div>

            <button type="submit" class="w-full bg-[#22c55e] text-white py-3.5 rounded-[16px] font-bold text-[16px] hover:bg-[#16a34a] transition-all shadow-sm mt-2">
              Save & Broadcast Session
            </button>
          </form>
        </div>

        <div class="lg:col-span-2 space-y-4">
          <h2 class="text-[20px] font-bold text-gray-800 mb-4">Upcoming Schedule</h2>

          <div v-if="sessions.length === 0" class="text-center py-12 bg-white rounded-[24px] border border-gray-100 text-gray-400">
            No upcoming sessions scheduled yet. Use the form to add one!
          </div>

          <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div v-for="session in sessions" :key="session.id" class="bg-white rounded-[24px] p-5 border border-gray-100 shadow-sm flex flex-col justify-between transition-all duration-300 hover:shadow-md">
              <div>
                <div class="flex justify-between items-start mb-3">
                  <span :class="['px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider', session.track_type?.toLowerCase() === 'unplugged' ? 'bg-orange-50 text-orange-600' : 'bg-emerald-50 text-emerald-600']">
                    {{ session.track_type }}
                  </span>
                  <span class="text-sm font-bold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-md">
                    {{ session.classroom?.name || 'Smart Kids' }}
                  </span>
                </div>
                <h3 class="text-[18px] font-bold text-gray-800 mb-1 capitalize">{{ session.title }}</h3>
                <p class="text-gray-400 text-xs flex items-center gap-1 mb-4">
                  📍 {{ session.location_or_link || 'Main Classroom' }}
                </p>
              </div>

              <div class="border-t border-gray-50 pt-3 mt-2 flex items-center justify-between text-gray-500 text-sm">
                <div class="flex flex-col gap-0.5">
                  <span class="font-medium text-gray-700 text-xs">🗓️ {{ new Date(session.scheduled_at).toLocaleDateString() }}</span>
                  <span class="font-bold text-[#22c55e] text-xs">⏰ {{ new Date(session.scheduled_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}</span>
                </div>

          <button @click.prevent="deleteSession(session)"
        type="button"
        class="text-red-500 hover:text-red-700 text-[15px] font-bold flex items-center gap-1 transition active:scale-95 mt-1">
  🗑️ الحذف
</button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { Link, useForm, router } from '@inertiajs/vue3'; // 🌟 أضفنا router هنا للتحكم بطلبات الحذف

defineProps({
  sessions: Array,
  classes: Array
});

const form = useForm({
  title: '',
  classroom_id: '',
  track_type: 'plugged',
  scheduled_at: '',
  location_or_link: ''
});

const submitSession = () => {
  form.post(route('teacher.sessions.store'), {
    onSuccess: () => {
      form.reset(); // تصفير الحقول بعد نجاح الحفظ
    },
  });
};

/**
 * 🌟 دالة الحذف المباشرة والآمنة لـ Inertia
 * تمنع تكرار الـ Method وتضمن تصفير كاش الصفحة تلقائياً
 */
const deleteSession = (sessionData) => {
  // 1️⃣ استخراج الـ ID الذكي: لو ممرر رقم هيدخل، ولو ممرر كائن كامل حيدور على الـ id جواه
  let realId = null;

  if (typeof sessionData === 'object' && sessionData !== null) {
    // الفحص الشامل لكل مسميات الـ ID الممكنة في جدول لارافيل والـ API
    realId = sessionData.id || sessionData.session_id || sessionData.id_session;
  } else {
    realId = sessionData;
  }

  // طباعة فحص هندسي دقيق في الكونسول
  console.log("🎯 الكائن المستلم كامل:", sessionData);
  console.log("🆔 الـ ID الذكي المستخرج النهائي:", realId);

  // إذا فشل تماماً في العثور على الرقم المعرف
  if (!realId || realId === 'undefined') {
    alert("🚨 خطأ: لم نتمكن من الوصول لرقم الجلسة (undefined). يرجى مراجعة الكونسول لمعاينة البيانات القادمة من لارافيل.");
    return;
  }

  if (!confirm('⚠️ هل أنتِ متأكدة من حذف هذه الجلسة التعليمية نهائياً؟')) return;

  // بناء الرابط الصافي والمضمون مية بالمية
  const deleteUrl = '/teacher/sessions/' + realId;

  router.delete(deleteUrl, {
    preserveScroll: true,
    preserveState: false,
    onSuccess: () => {
      alert('✨ تم حذف الجلسة بنجاح وتحديث الجدول فوراً!');
    },
    onError: (errors) => {
      console.error("❌ أخطاء السيرفر:", errors);
    }
  });
};
</script>
