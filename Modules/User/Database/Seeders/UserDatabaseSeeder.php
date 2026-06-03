<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Roles
        $roleIdAdmin = DB::table('roles')->insertGetId([
            'name' => 'Super Admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $roleIdManager = DB::table('roles')->insertGetId([
            'name' => 'Quản Lý',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $roleIdEmployee = DB::table('roles')->insertGetId([
            'name' => 'Nhân Viên',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Seed Permissions
        $permId1 = DB::table('permissions')->insertGetId([
            'name' => 'quan_ly_he_thong',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $permId2 = DB::table('permissions')->insertGetId([
            'name' => 'quan_ly_nhan_su',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $permId3 = DB::table('permissions')->insertGetId([
            'name' => 'xem_bao_cao',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Seed Role Has Permissions (Pivot)
        // Admin gets all permissions
        DB::table('role_has_permissions')->insert([
            ['role_id' => $roleIdAdmin, 'permissions_id' => $permId1],
            ['role_id' => $roleIdAdmin, 'permissions_id' => $permId2],
            ['role_id' => $roleIdAdmin, 'permissions_id' => $permId3],
        ]);

        // Manager gets manage human resources and view reports
        DB::table('role_has_permissions')->insert([
            ['role_id' => $roleIdManager, 'permissions_id' => $permId2],
            ['role_id' => $roleIdManager, 'permissions_id' => $permId3],
        ]);

        // Employee only gets view reports
        DB::table('role_has_permissions')->insert([
            ['role_id' => $roleIdEmployee, 'permissions_id' => $permId3],
        ]);

        // 4. Seed a default Admin User
        // Note: Assumes company_id=1, branch_id=1, department_id=1, position_id=1 exist
        DB::table('users')->insert([
            'company_id' => 1,
            'branch_id' => 1,
            'department_id' => 1, // Ban Giám Đốc
            'position_id' => 1,   // Giám Đốc
            'role_id' => $roleIdAdmin, // Super Admin
            'code' => 'ADMIN01',
            'name' => 'Super Admin',
            'email' => 'admin@erb.vn',
            'phone' => '0987654321',
            'password' => Hash::make('12345678'),
            'gender' => 0, // Male
            'birthday' => '1995-10-15',
            'address' => 'Tòa nhà ERB, Cầu Giấy, Hà Nội',
            'status' => 1, // active
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. Seed a default Employee User belonging to the Child Company
        DB::table('users')->insert([
            'company_id' => 2, // Công Ty Con ERB Tech
            'branch_id' => 3,  // Chi Nhánh Đà Nẵng (Tech)
            'department_id' => 4, // Phòng Nghiên Cứu & Phát Triển (R&D)
            'position_id' => 3,   // Nhân Viên
            'role_id' => $roleIdEmployee, // Nhân Viên role
            'code' => 'NV002',
            'name' => 'Nguyễn Văn B',
            'email' => 'dev@erb.vn',
            'phone' => '0912345678',
            'password' => Hash::make('12345678'),
            'gender' => 0, // Male
            'birthday' => '1998-05-20',
            'address' => 'Hải Châu, Đà Nẵng',
            'status' => 1, // active
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
