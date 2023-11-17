<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'napa123@gmail.com';
        // Điều kiện để xác định xem bản ghi có tồn tại hay không
        $condition = ['email' => $email];

        // Dữ liệu mới hoặc cập nhật
        $data = [
            'first_name' => 'Napa',
            'last_name' => 'Global',
            'email' => 'napa123@gmail.com',
            'password' => Hash::make('password'),
            'number_phone' => '1234567890',
        ];

        SuperAdmin::updateOrCreate($condition, $data);
    }
}
