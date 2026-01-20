<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateFileUpload
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ファイルアップロードの検証
        if ($request->hasFile('profile_image') || $request->hasFile('images')) {
            $files = $request->hasFile('profile_image') 
                ? [$request->file('profile_image')] 
                : $request->file('images', []);

            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    // ファイルサイズチェック（10MBまで）
                    if ($file->getSize() > 10 * 1024 * 1024) {
                        return back()->withErrors(['file' => 'ファイルサイズは10MB以下にしてください。'])->withInput();
                    }

                    // MIMEタイプチェック
                    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    if (!in_array($file->getMimeType(), $allowedMimes)) {
                        return back()->withErrors(['file' => '許可されていないファイル形式です。JPEG、PNG、GIF、WebPのみアップロード可能です。'])->withInput();
                    }

                    // ファイル名の検証（危険な文字列をチェック）
                    $filename = $file->getClientOriginalName();
                    if (preg_match('/[<>:"|?*\\\\]/', $filename)) {
                        return back()->withErrors(['file' => 'ファイル名に無効な文字が含まれています。'])->withInput();
                    }
                }
            }
        }

        return $next($request);
    }
}

