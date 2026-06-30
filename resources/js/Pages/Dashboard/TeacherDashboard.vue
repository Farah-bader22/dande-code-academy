
<template>
  <div class="min-h-screen bg-slate-50">
    <!-- Header -->
    <div class="w-full bg-[#e8f5e9] border-b border-[#d7eadf]">
      <div class="max-w-[1400px] mx-auto px-6 py-5 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="w-[46px] h-[46px] rounded-[14px] bg-[#2e7d32] flex items-center justify-center shadow-sm">
            <i data-feather="star" class="w-6 h-6 text-white"></i>
          </div>
          <div>
            <h1 class="text-[20px] font-bold text-[#1b3d2f]">Teacher Dashboard</h1>
            <!-- عرض اسم المعلم الحقيقي من قاعدة البيانات -->
            <p class="text-[14px] text-[#5e7f70]">Welcome back, Ms. {{ teacherName }}!</p>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <button @click="handleAction('create')" class="bg-[#2e7d32] hover:bg-[#1b5e20] transition-colors text-white px-6 py-2 rounded-full text-[15px] font-medium shadow-sm">
            + New Assignment
          </button>
          <Link href="/dashboard" class="w-[38px] h-[38px] rounded-full bg-[#dff5e6] flex items-center justify-center text-[#2e7d32] hover:bg-[#c8e6c9] transition-colors">
            <i data-feather="home" class="w-4 h-4"></i>
          </Link>
        </div>
      </div>
    </div>

    <div class="max-w-[1400px] mx-auto px-6 py-8">
      <!-- Stats -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Total Students -->
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
          <div class="w-[52px] h-[52px] rounded-full bg-[#eee6ff] flex items-center justify-center mb-6">
            <i data-feather="users" class="w-6 h-6 text-[#9333ea]"></i>
          </div>
          <h2 class="text-[36px] font-bold text-slate-800">{{ stats.total_students }}</h2>
          <p class="text-[16px] text-gray-500 mt-1">Total Students</p>
        </div>

        <!-- Active Assignments -->
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
          <div class="w-[52px] h-[52px] rounded-full bg-[#e0ecff] flex items-center justify-center mb-6">
            <i data-feather="book-open" class="w-6 h-6 text-[#2563eb]"></i>
          </div>
          <h2 class="text-[36px] font-bold text-slate-800">{{ stats.active_assignments }}</h2>
          <p class="text-[16px] text-gray-500 mt-1">Active Assignments</p>
        </div>

        <!-- Avg Completion Rate -->
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
          <div class="w-[52px] h-[52px] rounded-full bg-[#def7ec] flex items-center justify-center mb-6">
            <i data-feather="trending-up" class="w-6 h-6 text-[#16a34a]"></i>
          </div>
          <h2 class="text-[36px] font-bold text-slate-800">{{ stats.avg_completion }}%</h2>
          <p class="text-[16px] text-gray-500 mt-1">Avg Completion Rate</p>
        </div>

        <!-- Sessions This Week -->
        <div class="bg-white rounded-[24px] p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
          <div class="w-[52px] h-[52px] rounded-full bg-[#fde7d7] flex items-center justify-center mb-6">
            <i data-feather="clock" class="w-6 h-6 text-[#f97316]"></i>
          </div>
          <h2 class="text-[36px] font-bold text-slate-800">{{ stats.weekly_sessions }}</h2>
          <p class="text-[16px] text-gray-500 mt-1">Sessions This Week</p>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="mb-10">
        <h2 class="text-[22px] font-bold mb-6 text-slate-800">Quick Actions</h2>

     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

  <button
    @click="handleAction('create')"
    class="group bg-gradient-to-r from-[#8b5cf6] to-[#a855f7] text-white p-6 rounded-[20px] shadow-sm hover:shadow-lg transition-all text-left outline-none focus:ring-2 focus:ring-[#8b5cf6] focus:ring-offset-2"
  >
    <i data-feather="plus" class="w-7 h-7 mb-5 group-hover:scale-110 transition-transform"></i>
    <p class="text-[18px] font-medium">Create Assignment</p>
  </button>

  <button
    @click="handleAction('manage')"
    class="group bg-gradient-to-r from-[#3b82f6] to-[#2563eb] text-white p-6 rounded-[20px] shadow-sm hover:shadow-lg transition-all text-left outline-none focus:ring-2 focus:ring-[#3b82f6] focus:ring-offset-2"
  >
    <i data-feather="users" class="w-7 h-7 mb-5 group-hover:scale-110 transition-transform"></i>
    <p class="text-[18px] font-medium">Manage Classes</p>
  </button>

  <button
    @click="handleAction('schedule')"
    class="group bg-gradient-to-r from-[#22c55e] to-[#16a34a] text-white p-6 rounded-[20px] shadow-sm hover:shadow-lg transition-all text-left outline-none focus:ring-2 focus:ring-[#22c55e] focus:ring-offset-2"
  >
    <i data-feather="calendar" class="w-7 h-7 mb-5 group-hover:scale-110 transition-transform"></i>
    <p class="text-[18px] font-medium">Schedule Session</p>
  </button>

  <button
    @click="handleAction('feedback')"
    class="group bg-gradient-to-r from-[#f97316] to-[#ea580c] text-white p-6 rounded-[20px] shadow-sm hover:shadow-lg transition-all text-left outline-none focus:ring-2 focus:ring-[#f97316] focus:ring-offset-2"
  >
    <i data-feather="message-square" class="w-7 h-7 mb-5 group-hover:scale-110 transition-transform"></i>
    <p class="text-[18px] font-medium">Send Feedback</p>
  </button>

</div>
      </div>
    </div>


<div class="bg-white rounded-[30px] p-6 sm:p-8 shadow mb-10">
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-[22px] font-bold text-indigo-950">My Classes</h2>
    <Link :href="route('teacher.classes.index')" class="text-[#9333ea] text-[16px] font-semibold hover:underline">
      View All
    </Link>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div v-for="classroom in classes" :key="classroom.id" class="bg-[#f7f7fb] rounded-[24px] p-6 flex flex-col justify-between">

      <div class="flex items-start justify-between mb-6">
        <div class="flex items-center gap-5">
          <div class="w-[78px] h-[78px] sm:w-[88px] sm:h-[88px] rounded-[28px] bg-gradient-to-br from-[#c026ff] to-[#7c3aed] text-white flex items-center justify-center text-[24px] sm:text-[28px] font-bold shadow-md">
            {{ classroom.short_name }}
          </div>
          <div>
            <h3 class="text-[20px] sm:text-[22px] font-black text-indigo-950">Grade {{ classroom.name }}</h3>
            <p class="text-[14px] sm:text-[16px] text-gray-500 mt-1">{{ classroom.students_count }} students</p>
          </div>
        </div>
        <button class="text-gray-400 hover:text-indigo-600 transition-colors">
          <i class="fa-solid fa-chevron-right text-xl"></i>
        </button>
      </div>

      <div class="grid grid-cols-2 gap-4 sm:gap-6">
        <div class="bg-white rounded-[20px] p-4 sm:p-6 shadow-sm">
          <h4 class="text-[22px] sm:text-[26px] font-black text-indigo-900">{{ classroom.active_assignments_count }}</h4>
          <p class="text-[12px] sm:text-[14px] text-gray-400 mt-1 font-medium leading-tight">Active Assignments</p>
        </div>
        <div class="bg-white rounded-[20px] p-4 sm:p-6 shadow-sm">
          <h4 class="text-[22px] sm:text-[26px] font-black text-emerald-600">{{ classroom.avg_progress }}%</h4>
          <p class="text-[12px] sm:text-[14px] text-gray-400 mt-1 font-medium leading-tight">Avg Progress</p>
        </div>
      </div>

    </div>
  </div>
</div>





  </div>

<div class="bg-white rounded-[30px] p-8 shadow mb-10">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-[22px] font-bold">Recent Submissions</h2>
        <span class="text-[#9333ea] text-[16px] font-medium cursor-pointer">View All</span>
      </div>

      <div class="space-y-6">
        <div v-if="submissions.length === 0" class="text-gray-400 text-center py-6 text-[18px]">
          No recent submissions yet.
        </div>

        <div v-for="sub in submissions" :key="sub.id" class="bg-[#f7f7fb] rounded-[24px] p-6 flex items-center justify-between">
          <div class="flex items-center gap-5">
            <div class="w-[88px] h-[88px] rounded-[28px] bg-[#ecebff] flex items-center justify-center text-[36px]">
              {{ sub.student_gender === 'male' ? '👦' : '👧' }}
            </div>
            <div>
              <h3 class="text-[22px] font-bold">{{ sub.student_name }}</h3>
              <p class="text-[18px] text-gray-500 mt-1">{{ sub.assignment_title }}</p>
            </div>
          </div>

          <div class="flex items-center gap-5">
            <div v-if="sub.status === 'graded'" class="flex items-center gap-5">
              <div class="text-center">
                <h4 class="text-[30px] font-bold text-[#22c55e]">{{ sub.score }}%</h4>
                <p class="text-[14px] text-gray-500">Score</p>
              </div>
              <i data-feather="check-circle" class="w-12 h-12 text-[#22c55e]"></i>
            </div>

            <div v-else class="bg-[#f4eeb3] text-[#9a6700] rounded-[24px] px-10 py-6 text-[20px] font-medium">
              Pending Review
            </div>
          </div>
        </div>
      </div>
    </div>

<div class="bg-white rounded-[30px] p-8 shadow mb-10">
  <div class="flex items-center gap-4 mb-8">
    <i data-feather="calendar" class="w-9 h-9 text-[#9333ea]"></i>
    <h2 class="text-[22px] font-bold">Upcoming Sessions</h2>
  </div>

  <div class="space-y-6">
    <div v-if="sessions.length === 0" class="text-gray-400 text-center py-6 text-[18px]">
      No upcoming sessions scheduled.
    </div>

    <div v-for="session in sessions" :key="session.id" class="bg-[#f2f2fc] rounded-[26px] p-8">
      <div class="flex items-start justify-between mb-4">
        <div>
          <h3 class="text-[22px] font-bold">{{ session.title }}</h3>
          <p class="text-[18px] text-gray-500 mt-3">Grade {{ session.class_name || 'DandeCode Smart Kids' }}</p>
        </div>

        <div class="flex flex-col items-end gap-3">
          <span class="bg-[#eed8ff] text-[#9333ea] px-6 py-2 rounded-full text-[16px] font-semibold">
            {{ session.type }}
          </span>

          <button @click.prevent="deleteSession(session.id)"
                  type="button"
                  class="text-red-500 hover:text-red-700 text-[15px] font-bold flex items-center gap-1 transition active:scale-95 mt-1">
            🗑️ الحذف
          </button>
        </div>
      </div>

      <div class="flex items-center gap-3 text-gray-500 text-[18px]">
        <i data-feather="clock" class="w-7 h-7"></i>
        <span>{{ session.time }}</span>
      </div>
    </div>
  </div>

  <button @click="handleAction('schedule')" class="w-full mt-8 bg-gradient-to-r from-[#a21caf] to-[#c026ff] text-white py-5 rounded-[24px] text-[24px] font-medium hover:opacity-90 transition">
    View Calendar
  </button>
</div>




<div class="bg-white rounded-[30px] p-6 sm:p-8 shadow mb-10 transition-all duration-300 hover:shadow-md">
      <div class="flex items-center gap-4 mb-8">
        <i data-feather="award" class="w-9 h-9 text-[#eab308]"></i>
        <h2 class="text-[20px] sm:text-[22px] font-bold">Top Performers</h2>
      </div>

      <div class="space-y-6 sm:space-y-8">
        <div v-for="student in topPerformers" :key="student.rank" class="flex items-center justify-between border-b border-gray-50 pb-4 last:border-none last:pb-0">
          <div class="flex items-center gap-4 sm:gap-5">
            <div :class="[
              'w-14 h-14 sm:w-[78px] sm:h-[78px] rounded-full text-white flex items-center justify-center text-[24px] sm:text-[32px] font-bold transition-transform duration-300 hover:scale-105 shadow-sm',
              student.rank === 1 ? 'bg-[#facc15]' : '',
              student.rank === 2 ? 'bg-[#a8b0c6]' : '',
              student.rank === 3 ? 'bg-[#fb923c]' : ''
            ]">
              {{ student.rank }}
            </div>
            <div>
              <h3 class="text-[18px] sm:text-[20px] font-bold text-gray-800">{{ student.name }}</h3>
              <p class="text-[14px] sm:text-[16px] text-gray-500 mt-1">{{ student.class_name }}</p>
            </div>
          </div>
          <div class="text-[24px] sm:text-[30px] font-extrabold text-[#08ad51]">{{ student.score }}%</div>
        </div>
      </div>
    </div>

    <div class="bg-[#08ad51] rounded-[30px] p-6 sm:p-8 shadow mb-10 text-white transition-all duration-300 hover:bg-[#079947]">
      <h2 class="text-[20px] sm:text-[22px] font-bold mb-6 sm:mb-8 flex items-center gap-3">
        <span>Teaching Resources</span>
      </h2>
      <p class="text-[16px] sm:text-[18px] leading-8 sm:leading-9 max-w-[780px] mb-8 sm:mb-10 text-emerald-50">
        Access lesson plans, worksheets, and interactive teaching guides designed for kids coding tracks (Plugged & Unplugged).
      </p>

   <button @click="handleAction('browse')" class="w-full bg-white text-[#08ad51] py-4 sm:py-5 rounded-[24px] text-[20px] sm:text-[24px] font-bold shadow-sm transition-all duration-300 hover:bg-emerald-50 active:scale-[0.99]">
  Browse Resources
</button>
    </div>



<div v-if="showFeedbackModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
  <div class="bg-white rounded-[24px] max-w-md w-full p-6 shadow-xl border border-slate-100 animate-in fade-in zoom-in duration-200">
    <h3 class="text-[20px] font-bold text-slate-800 mb-4 flex items-center gap-2">
      <span>💌</span> إرسال ملاحظة وتقييم للطالب
    </h3>

    <form @submit.prevent="submitFeedback">
      <div class="mb-4">
        <label class="block text-[14px] font-medium text-slate-600 mb-2">اختر الطالب</label>
        <select v-model="selectedStudent" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none text-slate-700">
          <option value="" disabled>-- اختر من طلابك المبدعين --</option>

          <option v-for="student in props.students" :key="student.id" :value="student.id">
            {{ student.name }}
          </option>
        </select>
      </div>

      <div class="mb-6">
        <label class="block text-[14px] font-medium text-slate-600 mb-2">اكتب توجيهك الفخم</label>
        <textarea v-model="feedbackMessage" required rows="4" placeholder="مثال: أبدعتِ اليوم في تحدي التكرار (Loops)! استمري يا بطلة..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none text-slate-700 resize-none"></textarea>
      </div>

      <div class="flex items-center justify-end gap-3">
        <button type="button" @click="showFeedbackModal = false" class="px-4 py-2.5 rounded-xl text-slate-500 hover:bg-slate-50 font-medium transition-colors">
          إلغاء
        </button>
        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl font-medium shadow-sm hover:shadow-md transition-all">
          إرسال الملاحظة فوراً 🚀
        </button>
      </div>
    </form>
  </div>
</div>



<div class="mt-8 bg-white p-6 rounded-[24px] shadow-sm border border-slate-100">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-slate-800">تحديات البرمجة المنشورة 🧩</h3>
                <p class="text-slate-400 text-sm mt-0.5">تابع التحديات الحالية النشطة في مصفوفة روبوت داندي كود.</p>
            </div>

            <div v-if="assignments.length === 0" class="text-center py-8 text-slate-400 font-medium">
                <span class="text-3xl block mb-2">📦</span>
                لم تقومي بنشر أي تحديات برمجة بعد.
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="item in assignments" :key="item.id"
                     class="bg-slate-50/60 p-5 rounded-[20px] border border-slate-100 flex flex-col justify-between hover:border-indigo-100 transition-all">

                    <div>
                        <div class="flex justify-between items-start gap-2 mb-2">
                            <h4 class="font-bold text-indigo-950 text-base">{{ item.title }}</h4>
                            <span class="px-2.5 py-0.5 bg-purple-50 text-purple-600 rounded-lg text-xs font-bold whitespace-nowrap">
                                ⭐ {{ item.points || 100 }} نقطة
                            </span>
                        </div>
                        <p class="text-slate-500 text-sm line-clamp-2 mb-4 leading-relaxed">{{ item.description }}</p>
                    </div>

                    <div class="pt-3 border-t border-slate-200/60 flex items-center justify-end">
                    <button @click.prevent="deleteAssignment(item.id)"
        type="button"
        class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-bold text-xs transition-all active:scale-95 flex items-center gap-1">
    🗑️ حذف التحدي
</button>
                    </div>
                </div>
            </div>
        </div>





</template>

<script setup>
import axios from 'axios';
import { onMounted, computed, nextTick, ref } from 'vue'; // 🌟 أضفنا ref للتحكم بالـ Modal والـ Form
import { Link, usePage, router } from '@inertiajs/vue3';
import feather from 'feather-icons';

// 1. تعريف الـ Props القادمة من Laravel Controller (مستقرة وكاملة 100%)
const props = defineProps({
  auth: Object,
  classes: {
    type: Array,
    default: () => []
  },
  submissions: {
    type: Array,
    default: () => []
  },
  sessions: {
    type: Array,
    default: () => []
  },
  topPerformers: {
    type: Array,
    default: () => []
  },
  stats: {
    type: Object,
    default: () => ({
      total_students: 0,
      active_assignments: 0,
      avg_completion: 0,
      weekly_sessions: 0
    })
  },
  students: {
    type: Array,
    default: () => []
  },
  // 🌟 هان ضفنا الـ assignments رسمي عشان يستقبل التحديات اللي بعثناها من الكنترولر!
  assignments: {
    type: Array,
    default: () => []
  }
});

// 🌟 2. متغيرات التحكم بنوافذ الـ Feedback والبيانات المدخلة (جديد ومحمي)
const showFeedbackModal = ref(false);
const selectedStudent = ref('');
const feedbackMessage = ref('');

// 3. استخراج الاسم الأول للمعلمة (مثل: Farah)
const teacherName = computed(() => {
  return props.auth?.user?.name ? props.auth.user.name.split(' ')[0] : 'Teacher';
});

/**
 * 4. وظائف الأزرار (Navigation)
 * تم تعديل حالة الـ feedback لتفتح الـ Modal مباشرة وتمنع الـ Crash
 */
const handleAction = (actionType) => {
  switch (actionType) {
    case 'create':
      router.visit(route('teacher.assignments.create'));
      break;
    case 'manage':
      router.visit(route('teacher.classes.index'));
      break;
    case 'schedule':
      router.visit(route('teacher.sessions.index'));
      break;
    case 'feedback':
      // 🌟 الحل السحري: نفتح الـ Modal هنا فوراً بدل توجيه الصفحة المفقودة
      showFeedbackModal.value = true;
      break;
    case 'browse':
      router.visit(route('teacher.resources.index'));
      break;
    default:
      console.warn(`Action "${actionType}" is not defined.`);
  }
};

/**
 * 🌟 5. دالة معالجة إرسال التقييم الحقيقي للباك إند (جديد بالكامل ومتناسق)
 */
const submitFeedback = () => {
  router.post(route('teacher.feedback.store'), {
    student_id: selectedStudent.value,
    classroom_id: 1, // الصف الافتراضي أو الديناميكي المعتمد
    message: feedbackMessage.value
  }, {
    onSuccess: () => {
      // إغلاق النافذة وتصفير الحقول بنجاح عند اكتمال الإرسال الحقيقي
      showFeedbackModal.value = false;
      selectedStudent.value = '';
      feedbackMessage.value = '';
      alert('تم إرسال الملاحظة بنجاح، وستظهر في داشبورد الطالب فوراً! 🎉');
    },
    onError: (errors) => {
      console.error('حدث خطأ أثناء الإرسال:', errors);
      alert('تأكدي من ملء الحقول واختيار الطالب بشكل صحيح.');
    }
  });
};



/**
 * 🌟 6. دالة حذف التحدي الفورية والمحدثة لمنع تعليق الـ URL بعد أول عملية حذف
 */
const deleteAssignment = (id) => {
    if (!confirm('⚠️ هل أنتِ متأكدة من حذف هذا التحدي؟ سيتم إزالته فوراً من عند الطلاب وسجلاتهم!')) return;

    // 🌟 استخدام axios الصافي يمنع Inertia تماماً من التخبيص أو استخدام عنوان الصفحة الحالية
    axios.delete(`/teacher/assignments/${id}`, {
        headers: {
            'X-Inertia': 'true', // عشان لارافيل يفهم إن الطلب جاي من تطبيق Inertia
        }
    })
    .then(response => {
        alert('✨ تم حذف التحدي بنجاح!');

        // 🔄 الحركة السحرية: إعادة تحميل الصفحة فوراً بشكل نظيف لتحديث كروت الواجبات والـ Props
        window.location.reload();
    })
    .catch(error => {
        console.error('تفاصيل الخطأ:', error);
        alert('تعذر الحذف، يرجى إعادة محاولة تحديث الصفحة يدوياً.');
    });
};

// 7. تفعيل الأيقونات (Feather Icons) بعد تحميل الـ DOM بالكامل
onMounted(() => {
  nextTick(() => {
    feather.replace();
  });
});

// بعد تعريف الـ props مباشرة
</script>

<style scoped>
/* إضافة لمسات Glassmorphism خفيفة تتناسب مع ذوقك */
.shadow-sm {
  box-shadow: 0 2px 4px rgba(0,0,0,0.02), 0 1px 0 rgba(0,0,0,0.02);
}
</style>
