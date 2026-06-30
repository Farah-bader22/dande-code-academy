<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. إذا كان المستخدم معلماً، اسمحي له بالمرور
        if (auth()->check() && auth()->user()->role === 'teacher') {
            return $next($request);
        }

        // 2. إذا لم يكن مسجلاً للدخول، وجهيه لصفحة تسجيل الدخول
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 3. إذا كان المستخدم ليس معلماً (طالب، ولي أمر، إلخ..)
        // بدلاً من إرساله لصفحة الطالب إجبارياً، نعيده للـ dashboard العام
        // الذي سيقوم بدوره بتوجيهه للوجهة الصحيحة بناءً على الـ role الخاص به
        return redirect()->route('dashboard');
    }
}
