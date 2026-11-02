<?php
 
namespace App\Http\Controllers;
 
use App\Models\Item;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
 
class ReportController extends Controller
{
    // หน้ารายงานสินค้าใกล้หมด (quantity <= reorder_level)
    function lowStock(): View
    {
        Gate::authorize('viewLowStockReport', Item::class);
 
        $items = Item::with(['category', 'supplier'])
            ->whereColumn('quantity', '<=', 'reorder_level')
            ->orderBy('quantity')
            ->orderBy('name')
            ->get();
 
        return view('reports.low-stock', [
            'items' => $items,
        ]);
    }
 
    // ดาวน์โหลดรายงานเดียวกันเป็น PDF ด้วย barryvdh/laravel-dompdf
    function lowStockPdf(): Response
    {
        Gate::authorize('viewLowStockReport', Item::class);
 
        $items = Item::with(['category', 'supplier'])
            ->whereColumn('quantity', '<=', 'reorder_level')
            ->orderBy('quantity')
            ->orderBy('name')
            ->get();
 
        // loadView() แปลง Blade view เป็น PDF
        $pdf = Pdf::loadView('reports.low-stock-pdf', [
            'items' => $items,
            'generatedAt' => now(),
        ]);
        $pdf->setPaper('a4');
 
        // download() ส่งไฟล์ให้ browser ดาวน์โหลด
        return $pdf->download('low-stock-report.pdf');
    }
}
 