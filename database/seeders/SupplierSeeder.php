<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['code' => 'SP001', 'name' => 'North IT Supply', 'phone' => '053-000-001', 'email' => 'sales@north-it.example'],
            ['code' => 'SP002', 'name' => 'City Office Mart', 'phone' => '053-000-002', 'email' => 'orders@city-office.example'],
            ['code' => 'SP003', 'name' => 'Lanna Furniture', 'phone' => '053-000-003', 'email' => 'contact@lanna-furniture.example'],
            ['code' => 'SP004', 'name' => 'Clean Pro Trading', 'phone' => '053-000-004', 'email' => 'info@clean-pro.example'],
        ];

        foreach ($suppliers as $data) {
            Supplier::updateOrCreate(['code' => $data['code']], $data);
        }
    }
}
