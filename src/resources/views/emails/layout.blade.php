<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f5f3f0; font-family: 'Helvetica Neue', Arial, 'Hiragino Kaku Gothic ProN', 'Hiragino Sans', Meiryo, sans-serif; }
        .wrapper { max-width: 600px; margin: 0 auto; padding: 24px 16px; }
        .card { background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #3b0764, #7c3aed); padding: 24px 32px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 20px; margin: 0; letter-spacing: 0.05em; }
        .body { padding: 32px; color: #374151; font-size: 14px; line-height: 1.8; }
        .body h2 { font-size: 16px; color: #1f2937; margin: 0 0 16px; }
        .cta { display: inline-block; padding: 12px 32px; background: #7c3aed; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px; margin: 16px 0; }
        .footer { padding: 20px 32px; border-top: 1px solid #e5e7eb; font-size: 11px; color: #9ca3af; text-align: center; line-height: 1.6; }
        .footer a { color: #7c3aed; text-decoration: none; }
        .highlight { background: #f3f0ff; border-left: 3px solid #7c3aed; padding: 12px 16px; border-radius: 0 8px 8px 0; margin: 16px 0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <h1>{{ config('app.name', 'Palette') }}</h1>
            </div>
            <div class="body">
                @yield('content')
            </div>
            <div class="footer">
                <p>このメールは {{ config('app.name') }} から自動送信されています。</p>
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
