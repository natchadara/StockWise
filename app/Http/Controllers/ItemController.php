<?php
 
namespace App\Http\Controllers;
 
use App\Models\Category;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
 
class ItemController extends Controller
{
    // หน้ารายการสินค้า ทุกคนดูได้
    function list(): View
    {
        $items = Item::with(['category', 'supplier'])->orderBy('name')->paginate(15);
 
        return view('items.list', [
            'items' => $items,
        ]);
    }
 
    // หน้าฟอร์มเพิ่มสินค้า (เฉพาะ ADMIN ตาม ItemPolicy)
    function showCreateForm(): View
    {
        Gate::authorize('create', Item::class);
 
        return view('items.create-form', [
            'categories' => Category::orderBy('name')->get(),
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }
 
    // บันทึกสินค้าใหม่
    function create(Request $request): RedirectResponse
    {
        Gate::authorize('create', Item::class);
 
        $data = $request->validate([
            'sku' => 'required|max:64|unique:items,sku',
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);
 
        try {
            $item = new Item();
            $item->fill($data);
            $item->category()->associate(Category::find($data['category_id']));
            $item->supplier()->associate(Supplier::find($data['supplier_id']));
            $item->save();
 
            return redirect()
                ->route('items.view', $item)
                ->with('status', "Item {$item->sku} was created.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }
 
    // หน้าดูสินค้า 1 ชิ้น ทุกคนดูได้
    function view(Item $item): View
    {
        $item->load(['category', 'supplier']);
 
        return view('items.view', [
            'item' => $item,
        ]);
    }
 
    // หน้าฟอร์มแก้ไขสินค้า (ADMIN และ STAFF)
    function showUpdateForm(Item $item): View
    {
        Gate::authorize('update', $item);
 
        return view('items.update-form', [
            'item' => $item,
            'categories' => Category::orderBy('name')->get(),
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }
 
    // บันทึกการแก้ไขสินค้า
    function update(Request $request, Item $item): RedirectResponse
    {
        Gate::authorize('update', $item);
 
        // unique:items,sku,{id} = SKU ห้ามซ้ำ ยกเว้นซ้ำกับตัวเอง
        $data = $request->validate([
            'sku' => 'required|max:64|unique:items,sku,' . $item->id,
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);
 
        try {
            $item->fill($data);
            $item->category()->associate(Category::find($data['category_id']));
            $item->supplier()->associate(Supplier::find($data['supplier_id']));
            $item->save();
 
            return redirect()
                ->route('items.view', $item)
                ->with('status', "Item {$item->sku} was updated.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }
 
    // ลบสินค้า (เฉพาะ ADMIN)
    function delete(Item $item): RedirectResponse
    {
        Gate::authorize('delete', $item);
 
        try {
            $item->delete();
 
            return redirect()
                ->route('items.list')
                ->with('status', "Item {$item->sku} was deleted.");
        } catch (QueryException $excp) {
            return redirect()->back()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }
}
 