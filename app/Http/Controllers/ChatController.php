<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index($receiver_id = null)
    {
        // 1. جلب قائمة المحادثات الفريدة
        $conversations = Message::where('receiver_id', Auth::id())
            ->orWhere('sender_id', Auth::id())
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->unique(fn($item) => $item->sender_id === Auth::id() ? $item->receiver_id : $item->sender_id);

        // 2. جلب الرسائل
        $messages = $receiver_id ? Message::where(function($q) use ($receiver_id) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $receiver_id);
            })->orWhere(function($q) use ($receiver_id) {
                $q->where('sender_id', $receiver_id)->where('receiver_id', Auth::id());
            })->orderBy('created_at', 'asc')->get() : [];

        return Inertia::render('Dashboard/TeacherChats', [
            'conversations' => $conversations,
            'messages' => $messages,
            'selectedId' => $receiver_id ? (int)$receiver_id : null,
            'currentUserId' => Auth::id()
        ]);
    }

 
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);

        return redirect()->back();
    }
}
