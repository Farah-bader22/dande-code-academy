<template>
  <div class="min-h-screen bg-[#f8fafc] p-6 sm:p-8 font-sans">
    <div class="max-w-7xl mx-auto">

      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-10">
        <div>
          <Link :href="route('teacher.dashboard')" class="text-gray-500 hover:text-gray-700 flex items-center gap-2 mb-3 text-[16px] font-medium">
             Back to Dashboard
          </Link>
          <h1 class="text-[28px] sm:text-[34px] font-extrabold text-gray-900 flex items-center gap-3">
            Teaching Resources
          </h1>
          <p class="text-gray-500 mt-2 text-[16px]">Access plans, worksheets, and guides for DandeCode tracks.</p>
        </div>

        <button @click="showUploadModal = true" class="bg-gradient-to-r from-emerald-600 to-green-600 text-white px-6 py-3 rounded-[16px] font-bold shadow-sm hover:shadow-md transition-all flex items-center gap-2">
          <span>➕</span> إضافة مصدر تعليمي جديد
        </button>
      </div>

      <div v-if="filteredResources && filteredResources.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="res in filteredResources" :key="res.id" class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 flex flex-col justify-between transition-all duration-300 hover:shadow-md">
          <div>
            <div class="flex gap-2 mb-4">
              <span :class="[
                'px-3 py-1 rounded-full text-[12px] font-bold uppercase tracking-wider',
                res.track_type === 'unplugged' ? 'bg-orange-50 text-orange-600' : 'bg-emerald-50 text-emerald-600'
              ]">
                {{ res.track_type }}
              </span>
              <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[12px] font-bold uppercase tracking-wider">
                {{ res.resource_type ? res.resource_type.replace('_', ' ') : 'Worksheet' }}
              </span>
            </div>

            <h3 class="text-[20px] font-bold text-gray-800 mb-2">{{ res.title }}</h3>
            <p class="text-gray-500 text-[15px] leading-relaxed mb-6">{{ res.description }}</p>
          </div>

          <a :href="res.file_path" download class="w-full text-center bg-[#08ad51] text-white py-3.5 rounded-[16px] font-bold text-[16px] block transition-all duration-300 hover:bg-[#079947] shadow-sm">
            Download Resource (PDF)
          </a>

          <button @click.prevent="deleteResource(res)"
                  type="button"
                  class="w-full mt-3 text-center border border-red-200 hover:bg-red-50 text-red-500 py-2.5 rounded-[16px] font-bold text-[14px] flex items-center justify-center gap-1 transition-all active:scale-[0.98]">
            🗑️ حذف المصدر التعليمي
          </button>
        </div>
      </div>

    </div>

    <div v-if="showUploadModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-[24px] max-w-md w-full p-6 shadow-xl border border-slate-100 animate-in fade-in zoom-in duration-200 text-right" style="direction: rtl;">
        <h3 class="text-[20px] font-bold text-slate-800 mb-4 flex items-center gap-2">
          <span>📚</span> رفع ملف ومصدر تعليمي جديد للطلاب
        </h3>

        <form @submit.prevent="submitResource">
          <div class="mb-4">
            <label class="block text-[14px] font-medium text-slate-600 mb-2">عنوان المصدر (مثال: كراس التلوين الذكي)</label>
            <input v-model="form.title" type="text" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none text-slate-700" />
          </div>

          <div class="mb-4">
            <label class="block text-[14px] font-medium text-slate-600 mb-2">وصف مبسط للأطفال</label>
            <textarea v-model="form.description" required rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none text-slate-700 resize-none"></textarea>
          </div>

          <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-[14px] font-medium text-slate-600 mb-2">نوع المسار</label>
              <select v-model="form.track_type" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none text-slate-700">
                <option value="unplugged">🧩 Unplugged (بدون جهاز)</option>
                <option value="plugged">💻 Plugged (على الشاشة)</option>
              </select>
            </div>
            <div>
              <label class="block text-[14px] font-medium text-slate-600 mb-2">تصنيف الملف</label>
              <select v-model="form.resource_type" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none text-slate-700">
                <option value="worksheet">ورقة عمل</option>
                <option value="lesson_plan">خطة درس</option>
                <option value="guide">دليل إرشادي</option>
              </select>
            </div>
          </div>

          <div class="mb-6">
            <label class="block text-[14px] font-medium text-slate-600 mb-2">اختر ملف الـ PDF من جهازك</label>
            <input type="file" @input="form.file = $event.target.files[0]" accept=".pdf" required class="w-full text-slate-600 file:ml-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer" />
          </div>

          <div class="flex items-center justify-end gap-3">
            <button type="button" @click="showUploadModal = false" class="px-4 py-2.5 rounded-xl text-slate-500 hover:bg-slate-50 font-medium transition-colors">
              إلغاء
            </button>
            <button type="submit" :disabled="form.processing" class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-green-600 text-white rounded-xl font-medium shadow-sm hover:shadow-md transition-all">
              {{ form.processing ? 'جاري الرفع والتشفير...' : 'رفع للمنصة فوراً 🚀' }}
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  resources: Array
});

const showUploadModal = ref(false);

// 🌟 الـ Computed Property لحجب الملفات الإنجليزية القديمة برمجياً بشكل آمن تماماً
const filteredResources = computed(() => {
  if (!props.resources) return [];

  const excludedTitles = [
    'Introduction to Loops (Unplugged Activity)',
    'My First If-Condition Game',
    'Variables & Memory Boxes Lesson Plan'
  ];

  return props.resources.filter(res => !excludedTitles.includes(res.title));
});

const form = useForm({
  title: '',
  description: '',
  track_type: 'unplugged',
  resource_type: 'worksheet',
  file: null
});

const submitResource = () => {
  form.post(route('teacher.resources.store'), {
    onSuccess: () => {
      showUploadModal.value = false;
      form.reset();
    }
  });
};


const deleteResource = (resource) => {
  // 1️⃣ استخراج الـ ID الصافي للتأكد التام قبل الإرسال
  const id = resource.id;

  if (!id) {
    alert("🚨 خطأ: لم نتمكن من العثور على المعرف الذكي لهذا الملف.");
    return;
  }

  if (!confirm(`⚠️ هل أنتِ متأكدة من حذف المصدر "${resource.title}" نهائياً؟`)) return;

  // 2️⃣ استخدام الرابط المدمج الصريح لتفادي تلاعب المتصفحات
  const deleteUrl = '/teacher/resources/' + id;

  router.delete(deleteUrl, {
    preserveScroll: true,
    preserveState: false, // تنظيف حالة المكونات لجلب البيانات الجديدة مباشرة
    onSuccess: () => {
      alert('✨ تم حذف المصدر التعليمي وتحديث القائمة فوراً!');
    },
    onError: (errors) => {
      console.error("❌ تفاصيل الخطأ من السيرفر:", errors);
      alert('تعذر الحذف، يرجى مراجعة لوحة الـ Console.');
    }
  });
};
</script>
