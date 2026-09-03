<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            ['emp_id' => 'E006', 'full_name' => 'Vũ Thị Phượng', 'email' => 'phuong.vt@company.com', 'base_salary' => 6200000, 'actual_salary' => 28000000, 'birthday' => '1991-11-30', 'department_id' => 3, 'position_id' => 2],
            ['emp_id' => 'E007', 'full_name' => 'Đặng Văn Gh', 'email' => 'gh.dv@company.com', 'base_salary' => 4800000, 'actual_salary' => 15000000, 'birthday' => '1999-04-12', 'department_id' => 2, 'position_id' => 3],
            ['emp_id' => 'E008', 'full_name' => 'Bùi Thị Hà', 'email' => 'ha.bt@company.com', 'base_salary' => 5200000, 'actual_salary' => 20000000, 'birthday' => '1996-08-22', 'department_id' => 1, 'position_id' => 2],
            ['emp_id' => 'E009', 'full_name' => 'Đỗ Văn Ích', 'email' => 'ich.dv@company.com', 'base_salary' => 6500000, 'actual_salary' => 35000000, 'birthday' => '1988-02-14', 'department_id' => 3, 'position_id' => 1],
            ['emp_id' => 'E010', 'full_name' => 'Ngô Thị J', 'email' => 'j.nt@company.com', 'base_salary' => 4700000, 'actual_salary' => 14000000, 'birthday' => '2001-09-05', 'department_id' => 2, 'position_id' => 3],
            ['emp_id' => 'E011', 'full_name' => 'Trịnh Văn K', 'email' => 'k.tv@company.com', 'base_salary' => 5800000, 'actual_salary' => 24000000, 'birthday' => '1994-06-18', 'department_id' => 1, 'position_id' => 2],
            ['emp_id' => 'E012', 'full_name' => 'Lương Thị L', 'email' => 'l.lt@company.com', 'base_salary' => 5100000, 'actual_salary' => 17000000, 'birthday' => '1997-12-03', 'department_id' => 3, 'position_id' => 3],
            ['emp_id' => 'E013', 'full_name' => 'Phan Văn M', 'email' => 'm.pv@company.com', 'base_salary' => 7000000, 'actual_salary' => 42000000, 'birthday' => '1985-01-28', 'department_id' => 3, 'position_id' => 1],
            ['emp_id' => 'E014', 'full_name' => 'Hứa Thị N', 'email' => 'n.ht@company.com', 'base_salary' => 4600000, 'actual_salary' => 13000000, 'birthday' => '2002-05-15', 'department_id' => 2, 'position_id' => 3],
            ['emp_id' => 'E015', 'full_name' => 'Triệu Văn O', 'email' => 'o.tv@company.com', 'base_salary' => 5600000, 'actual_salary' => 26000000, 'birthday' => '1993-07-21', 'department_id' => 1, 'position_id' => 2],
            ['emp_id' => 'E016', 'full_name' => 'Đinh Thị P', 'email' => 'p.dt@company.com', 'base_salary' => 4900000, 'actual_salary' => 16000000, 'birthday' => '1998-10-09', 'department_id' => 3, 'position_id' => 3],
            ['emp_id' => 'E017', 'full_name' => 'Bạch Văn Q', 'email' => 'q.bv@company.com', 'base_salary' => 6100000, 'actual_salary' => 29000000, 'birthday' => '1990-03-17', 'department_id' => 2, 'position_id' => 1],
            ['emp_id' => 'E018', 'full_name' => 'Tôn Thất R', 'email' => 'r.tt@company.com', 'base_salary' => 4400000, 'actual_salary' => 11000000, 'birthday' => '2004-08-25', 'department_id' => 1, 'position_id' => 3],
            ['emp_id' => 'E019', 'full_name' => 'Nguyễn Thị S', 'email' => 's.nt@company.com', 'base_salary' => 5400000, 'actual_salary' => 23000000, 'birthday' => '1995-11-11', 'department_id' => 3, 'position_id' => 2],
            ['emp_id' => 'E020', 'full_name' => 'Chu Văn T', 'email' => 't.cv@company.com', 'base_salary' => 5300000, 'actual_salary' => 19000000, 'birthday' => '1997-02-28', 'department_id' => 2, 'position_id' => 3],
            ['emp_id' => 'E021', 'full_name' => 'Phùng Thị U', 'email' => 'u.pt@company.com', 'base_salary' => 5700000, 'actual_salary' => 25000000, 'birthday' => '1994-04-07', 'department_id' => 1, 'position_id' => 2],
            ['emp_id' => 'E022', 'full_name' => 'Vương Văn V', 'email' => 'v.vv@company.com', 'base_salary' => 6300000, 'actual_salary' => 32000000, 'birthday' => '1989-06-14', 'department_id' => 3, 'position_id' => 1],
            ['emp_id' => 'E023', 'full_name' => 'Đào Thị W', 'email' => 'w.dt@company.com', 'base_salary' => 4550000, 'actual_salary' => 12500000, 'birthday' => '2001-12-20', 'department_id' => 2, 'position_id' => 3],
            ['emp_id' => 'E024', 'full_name' => 'Hồ Văn X', 'email' => 'x.hv@company.com', 'base_salary' => 5900000, 'actual_salary' => 27000000, 'birthday' => '1992-09-03', 'department_id' => 1, 'position_id' => 2],
            ['emp_id' => 'E025', 'full_name' => 'Trần Văn Y', 'email' => 'y.tv@company.com', 'base_salary' => 5050000, 'actual_salary' => 17500000, 'birthday' => '1996-01-19', 'department_id' => 3, 'position_id' => 3],
            ['emp_id' => 'E026', 'full_name' => 'Lê Thị Z', 'email' => 'z.lt@company.com', 'base_salary' => 5550000, 'actual_salary' => 21000000, 'birthday' => '1993-08-08', 'department_id' => 2, 'position_id' => 2],
            ['emp_id' => 'E027', 'full_name' => 'Nguyễn Văn AA', 'email' => 'aa.nv@company.com', 'base_salary' => 7200000, 'actual_salary' => 45000000, 'birthday' => '1987-05-22', 'department_id' => 3, 'position_id' => 1],
            ['emp_id' => 'E028', 'full_name' => 'Trịnh Thị BB', 'email' => 'bb.tt@company.com', 'base_salary' => 4750000, 'actual_salary' => 14500000, 'birthday' => '2000-03-11', 'department_id' => 1, 'position_id' => 3],
            ['emp_id' => 'E029', 'full_name' => 'Phạm Văn CC', 'email' => 'cc.pv@company.com', 'base_salary' => 5650000, 'actual_salary' => 25500000, 'birthday' => '1994-10-16', 'department_id' => 2, 'position_id' => 2],
            ['emp_id' => 'E030', 'full_name' => 'Hoàng Thị DD', 'email' => 'dd.ht@company.com', 'base_salary' => 4850000, 'actual_salary' => 15500000, 'birthday' => '1999-07-04', 'department_id' => 3, 'position_id' => 3],
        ];

        foreach ($employees as $emp) {
            Employee::create($emp);
        }
    }
}
