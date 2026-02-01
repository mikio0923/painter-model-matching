<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Message;
use App\Models\Favorite;
use App\Models\Review;
use App\Models\Information;
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
                ->with(['job.painter.painterProfile'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            $unreadMessages = Message::where('receiver_id', $user->id)
                ->whereNull('read_at')
                ->count();

            // 統計情報
            $totalApplications = JobApplication::where('model_id', $user->id)->count();
            $acceptedApplications = JobApplication::where('model_id', $user->id)
                ->where('status', 'accepted')
                ->count();
            $completedJobs = Job::whereHas('applications', function($query) use ($user) {
                $query->where('model_id', $user->id)
                      ->where('status', 'accepted');
            })->where('status', 'done')->count();
            $averageRating = Review::where('reviewed_user_id', $user->id)
                ->avg('rating');
            $totalFavorites = Favorite::where('favoritable_type', \App\Models\ModelProfile::class)
                ->where('favoritable_id', $modelProfile?->id ?? 0)
                ->count();

            // 最近の依頼（おすすめのお仕事）
            $recentJobs = Job::where('status', 'open')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // お知らせ
            $information = Information::orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // サイトからのお知らせ（モデル向け）
            $siteNotices = Information::published()
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            return view('mypage.model', [
                'modelProfile' => $modelProfile,
                'applications' => $applications,
                'unreadMessages' => $unreadMessages,
                'totalApplications' => $totalApplications,
                'acceptedApplications' => $acceptedApplications,
                'completedJobs' => $completedJobs,
                'averageRating' => $averageRating,
                'totalFavorites' => $totalFavorites,
                'recentJobs' => $recentJobs,
                'information' => $information,
                'siteNotices' => $siteNotices,
            ]);
        } else {
            // 画家側のマイページ
            $painterProfile = $user->painterProfile;
            $jobs = Job::where('painter_id', $user->id)
                ->with(['painter.painterProfile', 'applications.model.modelProfile'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            $totalApplications = JobApplication::whereHas('job', function($query) use ($user) {
                $query->where('painter_id', $user->id);
            })->count();
            
            $unreadMessages = Message::where('receiver_id', $user->id)
                ->whereNull('read_at')
                ->count();

            // 統計情報
            $totalJobs = Job::where('painter_id', $user->id)->count();
            $openJobs = Job::where('painter_id', $user->id)
                ->where('status', 'open')
                ->count();
            $completedJobs = Job::where('painter_id', $user->id)
                ->where('status', 'done')
                ->count();
            $acceptedApplications = JobApplication::whereHas('job', function($query) use ($user) {
                $query->where('painter_id', $user->id);
            })->where('status', 'accepted')->count();
            $averageRating = Review::where('reviewed_user_id', $user->id)
                ->avg('rating');
            $totalFavorites = Favorite::where('favoritable_type', \App\Models\Job::class)
                ->whereIn('favoritable_id', Job::where('painter_id', $user->id)->pluck('id'))
                ->count();

            return view('mypage.painter', [
                'painterProfile' => $painterProfile,
                'jobs' => $jobs,
                'totalApplications' => $totalApplications,
                'unreadMessages' => $unreadMessages,
                'totalJobs' => $totalJobs,
                'openJobs' => $openJobs,
                'completedJobs' => $completedJobs,
                'acceptedApplications' => $acceptedApplications,
                'averageRating' => $averageRating,
                'totalFavorites' => $totalFavorites,
            ]);
        }
    }
}

