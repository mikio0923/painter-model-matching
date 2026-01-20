<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // 検索機能
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        // ロールでフィルタ
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->with(['modelProfile', 'painterProfile'])
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['modelProfile', 'painterProfile', 'jobs', 'jobApplications']);

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        // 管理者は削除できない
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', '管理者ユーザーは削除できません。');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'ユーザーを削除しました。');
    }
}

