<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\PainterProfile;
use Illuminate\View\View;

class PainterProfileController extends Controller
{
    /**
     * 画家プロフィール公開ページ
     */
    public function show(PainterProfile $painterProfile): View
    {
        $painterProfile->load('user');

        // この画家が出している公開中の依頼
        $openJobs = Job::where('painter_id', $painterProfile->user_id)
            ->where('status', 'open')
            ->latest()
            ->take(8)
            ->get();

        return view('painters.show', [
            'painterProfile' => $painterProfile,
            'painter'        => $painterProfile->user,
            'openJobs'       => $openJobs,
        ]);
    }
}
