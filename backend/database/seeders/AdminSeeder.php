<?php

namespace Database\Seeders;

use App\Modules\SuperAdmin\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'first_name' => 'Thinh',
            'last_name' => 'Tran',
            'email' => 'thinhtran1@gmail.com',
            'password' => Hash::make('password'),
            'number_phone' => '1234567890',
        ]);
    }
}
