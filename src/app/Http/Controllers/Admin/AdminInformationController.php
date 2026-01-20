<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\Http\Request;

class AdminInformationController extends Controller
{
    public function index(Request $request)
    {
        $query = Information::query();

        // タイプでフィルタ
        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        // 公開状態でフィルタ
        if ($request->filled('is_published')) {
            $query->where('is_published', $request->is_published === '1');
        }

        $informations = $query->latest('published_at')->paginate(20);

        return view('admin.information.index', compact('informations'));
    }

    public function create()
    {
        return view('admin.information.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:information,press_release',
            'published_at' => 'nullable|date',
            'is_published' => 'boolean',
        ]);

        Information::create($validated);

        return redirect()->route('admin.information.index')
            ->with('success', 'お知らせを作成しました。');
    }

    public function edit(Information $information)
    {
        return view('admin.information.edit', compact('information'));
    }

    public function update(Request $request, Information $information)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:information,press_release',
            'published_at' => 'nullable|date',
            'is_published' => 'boolean',
        ]);

        $information->update($validated);

        return redirect()->route('admin.information.index')
            ->with('success', 'お知らせを更新しました。');
    }

    public function destroy(Information $information)
    {
        $information->delete();

        return redirect()->route('admin.information.index')
            ->with('success', 'お知らせを削除しました。');
    }
}

