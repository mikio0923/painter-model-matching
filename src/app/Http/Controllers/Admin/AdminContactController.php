<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query();

        // 未読のみフィルタ
        if ($request->filled('unread')) {
            $query->where(function($q) {
                $q->where('is_read', false)->orWhereNull('is_read');
            });
        }

        $contacts = $query->latest()->paginate(20);

        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        // 既読にする（is_readカラムが存在する場合のみ）
        if (isset($contact->is_read) && !$contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    public function markAsRead(Contact $contact)
    {
        // is_readカラムが存在する場合のみ更新
        if (Schema::hasColumn('contacts', 'is_read')) {
            $contact->update(['is_read' => true]);
        }

        return redirect()->route('admin.contacts.index')
            ->with('success', 'お問い合わせを既読にしました。');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'お問い合わせを削除しました。');
    }
}

