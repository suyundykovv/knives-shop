<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Создайте нового пользователя с правами администратора
        User::create([
            'name' => 'Admin User',          // Имя администратора
            'email' => 'admin@example.com',  // Электронная почта
            'password' => Hash::make('password123'), // Пароль
            'is_admin' => true,              // Установите флаг is_admin в true
        ]);
    }
}
