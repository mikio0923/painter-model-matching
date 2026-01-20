<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Contact;
use App\Models\Review;
use App\Models\Message;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 統計情報
        $stats = [
            'total_users' => User::count(),
            'total_models' => User::where('role', 'model')->count(),
            'total_painters' => User::where('role', 'painter')->count(),
            'total_jobs' => Job::count(),
            'open_jobs' => Job::where('status', 'open')->count(),
            'total_applications' => JobApplication::count(),
            'pending_applications' => JobApplication::where('status', 'pending')->count(),
            'total_contacts' => Contact::count(),
            'unread_contacts' => Contact::where('is_read', false)->orWhereNull('is_read')->count(),
            'total_reviews' => Review::count(),
            'total_messages' => Message::count(),
        ];

        // 最近のアクティビティ
        $recentJobs = Job::with('painter.painterProfile')
            ->latest()
            ->take(5)
            ->get();

        $recentApplications = JobApplication::with(['job', 'model.modelProfile'])
            ->latest()
            ->take(5)
            ->get();

        $recentContacts = Contact::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentJobs', 'recentApplications', 'recentContacts'));
    }
}

