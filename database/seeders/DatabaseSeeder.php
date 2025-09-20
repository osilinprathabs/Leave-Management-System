<?php

namespace Database\Seeders;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        Permission::firstOrCreate(['name' => 'manage leaves']);
        Permission::firstOrCreate(['name' => 'view reports']);
        Permission::firstOrCreate(['name' => 'manage employees']);
        Permission::firstOrCreate(['name' => 'request leave']);
        Permission::firstOrCreate(['name' => 'view own leaves']);

        // Create Roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo(['manage leaves', 'view reports', 'manage employees']);

        $employeeRole = Role::firstOrCreate(['name' => 'Employee']);
        $employeeRole->givePermissionTo(['request leave', 'view own leaves']);

        // Create Admin User (your email)
        $admin = User::firstOrCreate(
            ['email' => 'oslinprathab@gmail.com'],
            [
                'name' => 'Prathab Admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('Admin');

        // Create another Admin User (backup)
        $admin2 = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'superadmin',
                'password' => Hash::make('password'),
            ]
        );
        $admin2->assignRole('Admin');

        // Create Employee User
        $employee = User::firstOrCreate(
            ['email' => 'employee@gmail.com'],
            [
                'name' => 'ramesh',
                'password' => Hash::make('password'),
            ]
        );
        $employee->assignRole('Employee');

        // Create some sample leave requests for the employee
        if ($employee->leaveRequests()->count() === 0) {
            $employee->leaveRequests()->create([
                'type' => LeaveRequest::TYPE_ANNUAL,
                'start_date' => '2025-10-01',
                'end_date' => '2025-10-05',
                'reason' => 'Annual vacation',
                'status' => LeaveRequest::STATUS_PENDING,
                'duration' => 5,
            ]);

            $employee->leaveRequests()->create([
                'type' => LeaveRequest::TYPE_SICK,
                'start_date' => '2025-11-10',
                'end_date' => '2025-11-10',
                'reason' => 'Fever',
                'status' => LeaveRequest::STATUS_APPROVED,
                'duration' => 1,
            ]);

            $employee->leaveRequests()->create([
                'type' => LeaveRequest::TYPE_CASUAL,
                'start_date' => '2025-12-20',
                'end_date' => '2025-12-22',
                'reason' => 'Family event',
                'status' => LeaveRequest::STATUS_REJECTED,
                'admin_remarks' => 'Too many requests during peak season.',
                'duration' => 3,
            ]);
        }
    }
}
