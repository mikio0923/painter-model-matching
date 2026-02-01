<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メールアドレス認証</title>
</head>
<body style="font-family: 'Hiragino Kaku Gothic ProN', 'Hiragino Sans', 'Meiryo', sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #2563eb; border-bottom: 2px solid #2563eb; padding-bottom: 10px;">
            ModelTown メールアドレス認証
        </h1>
        
        <p>この度は、ModelTownへのご登録ありがとうございます。</p>
        
        <p>以下のリンクをクリックして、会員登録を完了してください。</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('register.verify', ['email' => $email, 'token' => $token]) }}" 
               style="display: inline-block; background-color: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                会員登録を完了する
            </a>
        </div>
        
        <p style="font-size: 12px; color: #666;">
            このリンクは24時間有効です。<br>
            もしこのメールに心当たりがない場合は、このメールを無視してください。
        </p>
        
        <p style="font-size: 12px; color: #666; margin-top: 30px;">
            リンクがクリックできない場合は、以下のURLをコピーしてブラウザに貼り付けてください：<br>
            {{ route('register.verify', ['email' => $email, 'token' => $token]) }}
        </p>
    </div>
</body>
</html>
