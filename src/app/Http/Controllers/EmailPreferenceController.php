<?php

namespace App\Http\Controllers;

use App\Models\EmailPreference;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EmailPreferenceController extends Controller
{
    /**
     * メール配信設定画面
     */
    public function edit(): View
    {
        $user = Auth::user();
        $pref = $user->emailPreference ?? new EmailPreference([
            'application_emails' => true,
            'message_emails'     => true,
            'review_emails'      => true,
            'reminder_emails'    => true,
            'marketing_emails'   => true,
        ]);

        return view('account.email-preferences', [
            'pref' => $pref,
        ]);
    }

    /**
     * メール配信設定を保存
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'application_emails' => ['nullable', 'boolean'],
            'message_emails'     => ['nullable', 'boolean'],
            'review_emails'      => ['nullable', 'boolean'],
            'reminder_emails'    => ['nullable', 'boolean'],
            'marketing_emails'   => ['nullable', 'boolean'],
        ]);

        $data = [
            'application_emails' => (bool) $request->boolean('application_emails'),
            'message_emails'     => (bool) $request->boolean('message_emails'),
            'review_emails'      => (bool) $request->boolean('review_emails'),
            'reminder_emails'    => (bool) $request->boolean('reminder_emails'),
            'marketing_emails'   => (bool) $request->boolean('marketing_emails'),
        ];

        EmailPreference::updateOrCreate(
            ['user_id' => $user->id],
            $data,
        );

        return redirect()->route('account.email-preferences.edit')
            ->with('success', 'メール配信設定を保存しました。');
    }
}
