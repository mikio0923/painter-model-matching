<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InformationController extends Controller
{
    /**
     * お知らせ一覧を表示
     */
    public function index(Request $request): View
    {
        $query = Information::published();

        // タイプで絞り込み
        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        $informations = $query->orderBy('published_at', 'desc')
            ->paginate(20);

        return view('information.index', [
            'informations' => $informations,
        ]);
    }

    /**
     * お知らせ詳細を表示
     */
    public function show(Information $information): View
    {
        if (!$information->is_published) {
            abort(404);
        }

        return view('information.show', [
            'information' => $information,
        ]);
    }
}
