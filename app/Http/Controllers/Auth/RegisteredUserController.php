<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/UserRegister');
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. التحقق من البيانات مع إضافة التحقق من parent_username
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|lowercase|max:255|unique:users',
            'email' => 'required|string|lowercase|email|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
            'avatar' => 'nullable|string',
            'role' => 'required|string|in:student,parent,teacher',
            // التحقق أن ولي الأمر موجود فعلاً في قاعدة البيانات إذا تم إدخال اسمه
            'parent_username' => 'nullable|exists:users,username',
        ]);

        // 2. البحث عن ولي الأمر للحصول على الـ ID الخاص به
        $parentId = null;
        if ($request->filled('parent_username')) {
            $parent = User::where('username', $request->parent_username)->first();
            if ($parent) {
                $parentId = $parent->id;
            }
        }

        // 3. إنشاء المستخدم مع تمرير الـ parent_id إذا وجد
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $request->avatar,
            'role' => $request->role,
            'parent_id' => $parentId, // الربط الحقيقي هنا
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
