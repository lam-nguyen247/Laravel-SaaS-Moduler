<?php

namespace Database\Seeders;

use App\Models\SupperAdmin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class SupperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SupperAdmin::create([
            'first_name' => 'Thinh',
            'last_name' => 'Thinh',
            'email' => 'thinhtran@gmail.com',
            'password' => Hash::make('password'),
            'number_phone' => '1234567890'
        ]);

        $permissions = config('constants.permissions');
        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'guard_name' => $permission['guard_name'],
            ]);
        }
    }
}
