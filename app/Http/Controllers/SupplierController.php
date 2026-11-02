<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SupplierController extends Controller
{
    // หน้ารายการ supplier (ต้อง login) เรียงจากที่เพิ่มล่าสุด
    function list(): View
    {
        Gate::authorize('viewAny', Supplier::class);

        $suppliers = Supplier::withCount('items')->orderBy('created_at', 'desc')->paginate(10);

        return view('suppliers.list', [
            'suppliers' => $suppliers,
        ]);
    }

    // หน้าฟอร์มเพิ่ม supplier
    function showCreateForm(): View
    {
        Gate::authorize('create', Supplier::class);

        return view('suppliers.create-form');
    }

    // บันทึก supplier ใหม่
    function create(Request $request): RedirectResponse
    {
        Gate::authorize('create', Supplier::class);

        $data = $request->validate([
            'code' => 'required|max:64|unique:suppliers,code',
            'name' => 'required|max:255',
            'phone' => 'required|max:32',
            'email' => 'required|email|max:255',
        ]);

        try {
            $supplier = Supplier::create($data);

            return redirect()
                ->route('suppliers.view', $supplier)
                ->with('status', "Supplier {$supplier->code} was created.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    // หน้าดู supplier 1 ราย
    function view(Supplier $supplier): View
    {
        Gate::authorize('view', $supplier);

        $supplier->loadCount('items');

        return view('suppliers.view', [
            'supplier' => $supplier,
        ]);
    }

    // หน้าฟอร์มแก้ไข supplier
    function showUpdateForm(Supplier $supplier): View
    {
        Gate::authorize('update', $supplier);

        return view('suppliers.update-form', [
            'supplier' => $supplier,
        ]);
    }

    // บันทึกการแก้ไข supplier
    function update(Request $request, Supplier $supplier): RedirectResponse
    {
        Gate::authorize('update', $supplier);

        $data = $request->validate([
            'code' => 'required|max:64|unique:suppliers,code,' . $supplier->id,
            'name' => 'required|max:255',
            'phone' => 'required|max:32',
            'email' => 'required|email|max:255',
        ]);

        try {
            $supplier->update($data);

            return redirect()
                ->route('suppliers.view', $supplier)
                ->with('status', "Supplier {$supplier->code} was updated.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }

    // ลบ supplier (ถ้ายังมีสินค้าอยู่จะลบไม่ได้)
    function delete(Supplier $supplier): RedirectResponse
    {
        Gate::authorize('delete', $supplier);

        try {
            $supplier->delete();

            return redirect()
                ->route('suppliers.list')
                ->with('status', "Supplier {$supplier->code} was deleted.");
        } catch (QueryException $excp) {
            return redirect()->back()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }
}
