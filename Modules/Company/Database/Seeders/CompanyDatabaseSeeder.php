<?php

namespace Modules\Company\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Companies
        $companyId = DB::table('companies')->insertGetId([
            'name' => 'Tổng Công Ty ERB',
            'code' => 'ERB_CORP',
            'tax_code' => '0101234567',
            'phone' => '0243999999',
            'email' => 'info@erb.vn',
            'address' => 'Tòa nhà ERB, Cầu Giấy, Hà Nội',
            'status' => 1,
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('companies')->insert([
            'name' => 'Công Ty Con ERB Tech',
            'code' => 'ERB_TECH',
            'tax_code' => '0101234568',
            'phone' => '0243888888',
            'email' => 'tech@erb.vn',
            'address' => 'Tòa nhà ERB, Cầu Giấy, Hà Nội',
            'status' => 1,
            'parent_id' => $companyId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Seed Branches
        $branchId1 = DB::table('branches')->insertGetId([
            'company_id' => $companyId,
            'name' => 'Chi Nhánh Hà Nội',
            'code' => 'CN_HN',
            'tax_code' => '0101234567-001',
            'phone' => '0243999111',
            'email' => 'hn@erb.vn',
            'address' => 'Cầu Giấy, Hà Nội',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('branches')->insert([
            'company_id' => $companyId,
            'name' => 'Chi Nhánh TP.HCM',
            'code' => 'CN_HCM',
            'tax_code' => '0101234567-002',
            'phone' => '0283999222',
            'email' => 'hcm@erb.vn',
            'address' => 'Quận 1, TP.HCM',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Branch for child company (ERB Tech)
        $branchId3 = DB::table('branches')->insertGetId([
            'company_id' => 2, // ID of ERB Tech
            'name' => 'Chi Nhánh Đà Nẵng (Tech)',
            'code' => 'CN_DN_TECH',
            'tax_code' => '0101234568-001',
            'phone' => '02363999999',
            'email' => 'dn.tech@erb.vn',
            'address' => 'Hải Châu, Đà Nẵng',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Seed Departments
        $deptId1 = DB::table('departments')->insertGetId([
            'branch_id' => $branchId1,
            'parent_id' => null,
            'name' => 'Ban Giám Đốc',
            'code' => 'BGD',
            'description' => 'Ban điều hành công ty',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('departments')->insert([
            [
                'branch_id' => $branchId1,
                'parent_id' => $deptId1,
                'name' => 'Phòng Kỹ Thuật',
                'code' => 'PKT',
                'description' => 'Phòng phát triển phần mềm',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'branch_id' => $branchId1,
                'parent_id' => $deptId1,
                'name' => 'Phòng Nhân Sự',
                'code' => 'PNS',
                'description' => 'Phòng quản lý nhân sự',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // Department for child company branch
        DB::table('departments')->insert([
            'branch_id' => 3, // ID of Chi Nhánh Đà Nẵng (Tech)
            'parent_id' => null,
            'name' => 'Phòng Nghiên Cứu & Phát Triển (R&D)',
            'code' => 'RND',
            'description' => 'Phòng phát triển giải pháp công nghệ cao',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Seed Positions
        DB::table('positions')->insert([
            [
                'name' => 'Giám Đốc',
                'description' => 'Quản lý điều hành toàn diện',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Trưởng Phòng',
                'description' => 'Quản lý và điều hành cấp phòng ban',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nhân Viên',
                'description' => 'Thực thi các nghiệp vụ chuyên môn',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
