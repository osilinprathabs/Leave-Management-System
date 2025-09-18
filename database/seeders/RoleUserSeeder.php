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
         Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Employee']);

         $admin = User::create([
            'name' => 'superadmin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('Test@123'),
        ]);
        $admin->assignRole('Admin');

         $employee = User::create([
            'name' => 'ramesh',
            'email' => 'employee@gmail.com',
            'password' => bcrypt('Test@123'),
        ]);
        $employee->assignRole('Employee');
    }
}
