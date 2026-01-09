<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MypageController extends Controller
{
    /**
     * マイページを表示
     */
    public function index(): View
    {
        $user = Auth::user();

        if ($user->role === 'model') {
            // モデル側のマイページ
            $modelProfile = $user->modelProfile;
            $applications = JobApplication::where('model_id', $user->id)
                ->with(['job.painter'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            $unreadMessages = Message::where('receiver_id', $user->id)
                ->whereNull('read_at')
                ->count();

            return view('mypage.model', [
                'modelProfile' => $modelProfile,
                'applications' => $applications,
                'unreadMessages' => $unreadMessages,
            ]);
        } else {
            // 画家側のマイページ
            $painterProfile = $user->painterProfile;
            $jobs = Job::where('painter_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            $totalApplications = JobApplication::whereHas('job', function($query) use ($user) {
                $query->where('painter_id', $user->id);
            })->count();
            
            $unreadMessages = Message::where('receiver_id', $user->id)
                ->whereNull('read_at')
                ->count();

            return view('mypage.painter', [
                'painterProfile' => $painterProfile,
                'jobs' => $jobs,
                'totalApplications' => $totalApplications,
                'unreadMessages' => $unreadMessages,
            ]);
        }
    }
}

