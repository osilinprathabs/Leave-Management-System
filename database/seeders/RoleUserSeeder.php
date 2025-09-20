<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get existing roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);

        // Create or get existing admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'superadmin',
                'password' => bcrypt('Test@123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $admin->assignRole('Admin');

        // Create or get existing employee user
        $employee = User::firstOrCreate(
            ['email' => 'employee@gmail.com'],
            [
                'name' => 'ramesh',
                'password' => bcrypt('Test@123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $employee->assignRole('Employee');
    }
}
