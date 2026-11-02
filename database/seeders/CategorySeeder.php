<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['code' => 'CT001', 'name' => 'Electronics', 'description' => 'Computer and electronic accessories'],
            ['code' => 'CT002', 'name' => 'Office Supplies', 'description' => 'Paper, pens and stationery'],
            ['code' => 'CT003', 'name' => 'Furniture', 'description' => 'Chairs, lamps and storage'],
            ['code' => 'CT004', 'name' => 'Cleaning', 'description' => 'Cleaning products and supplies'],
        ];

        // updateOrCreate: finds the row by code, or creates it. Safe to run more than once.
        foreach ($categories as $data) {
            Category::updateOrCreate(['code' => $data['code']], $data);
        }
    }
}
