<template>
  <div class="min-h-screen bg-[#f8fafc] p-6 sm:p-8 font-sans">
    <div class="max-w-7xl mx-auto">

      <div class="mb-8">
        <Link :href="route('teacher.dashboard')" class="text-gray-500 hover:text-gray-700 flex items-center gap-2 mb-3 text-[16px] font-medium transition-colors">
          ← Back to Dashboard
        </Link>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h1 class="text-[28px] sm:text-[34px] font-extrabold text-gray-900">
              Manage Classes & Students
            </h1>
            <p class="text-gray-500 mt-1 text-[16px]">Monitor your active classrooms, tracks, and view registered students.</p>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div v-for="classroom in classes" :key="classroom.id" class="bg-white rounded-[24px] p-6 shadow-sm border border-gray-100 flex flex-col transition-all duration-300 hover:shadow-md">

          <div class="flex items-start justify-between border-b border-gray-100 pb-4 mb-4">
            <div>
              <h2 class="text-[22px] font-bold text-gray-800">{{ classroom.name }}</h2>
              <p class="text-sm text-gray-400 mt-0.5">Created at: {{ new Date(classroom.created_at).toLocaleDateString() }}</p>
            </div>
            <div class="flex flex-col items-end gap-1.5">
              <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[12px] font-bold uppercase tracking-wider">
                {{ classroom.students_count }} Students
              </span>
            </div>
          </div>

          <div class="flex-grow">
            <h3 class="text-[15px] font-bold text-gray-400 uppercase tracking-wider mb-3">Student Roster</h3>

            <div v-if="!classroom.users || classroom.users.length === 0" class="text-center py-6 bg-gray-50 rounded-[16px] text-gray-400 text-sm">
              No students registered in this class yet.
            </div>

            <div v-else class="space-y-2 max-h-[250px] overflow-y-auto pr-1">
              <div v-for="student in classroom.users" :key="student.id" class="flex items-center justify-between p-3 bg-gray-50 rounded-[16px] border border-gray-100">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-sm uppercase">
                    {{ student.name.charAt(0) }}
                  </div>
                  <div>
                    <h4 class="text-[15px] font-bold text-gray-800">{{ student.name }}</h4>
                    <p class="text-[12px] text-gray-500">@{{ student.username }}</p>
                  </div>
                </div>
                <span class="text-[13px] text-gray-400 font-medium">Active Student</span>
              </div>
            </div>

          </div>

        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  classes: Array
});
</script>

<style scoped>
/* تحسين شكل الـ Scrollbar لقائمة الطلاب */
::-webkit-scrollbar {
  width: 5px;
}
::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}
::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>
