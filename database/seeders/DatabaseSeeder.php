<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = new User;
        $user->first_name = 'Minh Huan';
        $user->last_name = 'Nguyen';
        $user->username = 'minhhuan';
        $user->email = 'minhhuan.web@gmail.com';
        $user->password = Hash::make('123456');
        $user->is_admin = true;
        $user->save();
    }
}
