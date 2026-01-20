<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 管理者ユーザーが既に存在するか確認
        if (User::where('role', 'admin')->exists()) {
            $this->command->info('管理者ユーザーは既に存在します。');
            return;
        }

        // 管理者ユーザーを作成
        User::create([
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->command->info('管理者ユーザーを作成しました。');
        $this->command->info('メールアドレス: admin@example.com');
        $this->command->info('パスワード: password');
    }
}

