<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    // Run after CategorySeeder and SupplierSeeder: every item needs a category and a supplier.
    public function run(): void
    {
        // [category code, supplier code, sku, name, quantity, reorder level, price]
        $items = [
            ['CT001', 'SP001', 'ITM-001', 'USB Keyboard', 25, 10, 350.00],
            ['CT001', 'SP001', 'ITM-002', 'Wireless Mouse', 4, 10, 250.00],
            ['CT001', 'SP001', 'ITM-003', 'HDMI Cable', 40, 15, 120.00],
            ['CT001', 'SP001', 'ITM-004', 'USB Flash Drive 32GB', 0, 10, 180.00],

            ['CT002', 'SP002', 'ITM-005', 'A4 Paper (ream)', 120, 50, 95.00],
            ['CT002', 'SP002', 'ITM-006', 'Ballpoint Pen (box)', 0, 5, 60.00],
            ['CT002', 'SP002', 'ITM-007', 'Stapler', 18, 5, 85.00],
            ['CT002', 'SP002', 'ITM-008', 'Sticky Notes (pack)', 7, 10, 45.00],

            ['CT003', 'SP003', 'ITM-009', 'Office Chair', 12, 3, 1890.00],
            ['CT003', 'SP003', 'ITM-010', 'Desk Lamp', 3, 4, 420.00],
            ['CT003', 'SP003', 'ITM-011', 'Filing Cabinet', 6, 2, 2450.00],

            ['CT004', 'SP004', 'ITM-012', 'Hand Soap (bottle)', 60, 20, 55.00],
            ['CT004', 'SP004', 'ITM-013', 'Floor Cleaner 5L', 9, 10, 210.00],
            ['CT004', 'SP004', 'ITM-014', 'Trash Bags (roll)', 45, 20, 70.00],
        ];

        foreach ($items as [$categoryCode, $supplierCode, $sku, $name, $quantity, $reorderLevel, $price]) {
            // Finds the item by sku, or makes a new one (not saved yet).
            $item = Item::firstOrNew(['sku' => $sku]);
            $item->fill([
                'name' => $name,
                'quantity' => $quantity,
                'reorder_level' => $reorderLevel,
                'price' => $price,
            ]);

            // category_id and supplier_id are not fillable, so we set them with associate().
            $item->category()->associate(Category::where('code', $categoryCode)->firstOrFail());
            $item->supplier()->associate(Supplier::where('code', $supplierCode)->firstOrFail());

            $item->save();
        }
    }
}
