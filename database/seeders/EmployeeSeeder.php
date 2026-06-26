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
        Employee::create([
            'emp_id'        => 'E001',
            'full_name'     => 'Nguyen Van A',
            'email'         => 'a@gmail.com',
            'base_salary'   => 5800000,
            'actual_salary' => 25000000,
            'birthday'      => '1995-01-01',
            'department_id' => 1,
            'position_id'   => 1
        ]);
        Employee::create([
            'emp_id'        => 'E002',
            'full_name'     => 'Tran Thi B',
            'email'         => 'b@gmail.com',
            'base_salary'   => 6000000,
            'actual_salary' => 22000000,
            'birthday'      => '1998-05-05',
            'department_id' => 2,
            'position_id'   => 3
        ]);
        Employee::create([
            'emp_id'        => 'E003',
            'full_name'     => 'Le Van C',
            'email'         => 'c@gmail.com',
            'base_salary'   => 5000000,
            'actual_salary' => 18000000,
            'birthday'      => '2000-10-10',
            'department_id' => 2,
            'position_id'   => 3
        ]);
        Employee::create([
            'emp_id'        => 'E004',
            'full_name'     => 'Pham Thi D',
            'email'         => 'd@gmail.com',
            'base_salary'   => 5500000,
            'actual_salary' => 30000000,
            'birthday'      => '1992-03-15',
            'department_id' => 3,
            'position_id'   => 1
        ]);
        Employee::create([
            'emp_id'        => 'E005',
            'full_name'     => 'Hoang Van E',
            'email'         => 'e@gmail.com',
            'base_salary'   => 4500000,
            'actual_salary' => 12000000,
            'birthday'      => '2003-07-20',
            'department_id' => 1,
            'position_id'   => 3
        ]);
    }
}
