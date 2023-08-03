<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('member_level')->insert([
            [
                'name' => 'Thành Viên bạc',
                'min_points' => 0
            ],
            [
                'name' => 'Thành viên Vàng',
                'min_points' => 1000
            ],
            [
                'name' => 'Thành viên Bạch Kim',
                'min_points' => 10000
            ],
            [
                'name' => 'Thành viên Kim Cương',
                'min_points' => 50000
            ]
        ]);
    }
}
