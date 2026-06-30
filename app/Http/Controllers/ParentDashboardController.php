<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message; // 1. أضيفي هذا الـ Import
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ParentDashboardController extends Controller
{
    public function index()
    {
        $parent = Auth::user();


        $messages = Message::where(function($q) use ($parent) {
            $q->where('sender_id', $parent->id)->where('receiver_id', 1);
        })->orWhere(function($q) use ($parent) {
            $q->where('sender_id', 1)->where('receiver_id', $parent->id);
        })->orderBy('created_at', 'asc')->get();

        $children = $parent->children()->get();

        $formattedChildren = $children->map(function ($child) {
            return [
                'id' => $child->id,
                'name' => $child->name,
                'points' => $child->points ?? 0,
                'submissions' => AssignmentSubmission::where('student_id', $child->id)->get(),
                'progress_percentage' => 68,
            ];
        });

        return Inertia::render('Dashboard/ParentDashboard', [
            'children' => $formattedChildren,
            'parentName' => $parent->name,
            'messages' => $messages, 
            'auth' => ['user' => $parent]
        ]);
    }
}
