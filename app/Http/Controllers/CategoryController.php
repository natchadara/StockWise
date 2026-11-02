<?php
 
namespace App\Http\Controllers;
 
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
 
class CategoryController extends Controller
{
    // หน้ารายการหมวดหมู่ ทุกคนดูได้ (withCount นับจำนวนสินค้าในแต่ละหมวด)
    function list(): View
    {
        $categories = Category::withCount('items')->orderBy('name')->paginate(15);
 
        return view('categories.list', [
            'categories' => $categories,
        ]);
    }
 
    // หน้าฟอร์มเพิ่มหมวดหมู่
    function showCreateForm(): View
    {
        Gate::authorize('create', Category::class);
 
        return view('categories.create-form');
    }
 
    // บันทึกหมวดหมู่ใหม่
    function create(Request $request): RedirectResponse
    {
        Gate::authorize('create', Category::class);
 
        $data = $request->validate([
            'code' => 'required|max:64|unique:categories,code',
            'name' => 'required|max:255',
            'description' => 'nullable|max:2000',
        ]);
 
        try {
            $category = Category::create($data);
 
            return redirect()
                ->route('categories.view', $category)
                ->with('status', "Category {$category->code} was created.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }
 
    // หน้าดูหมวดหมู่ 1 อัน
    function view(Category $category): View
    {
        $category->loadCount('items');
 
        return view('categories.view', [
            'category' => $category,
        ]);
    }
 
    // หน้าฟอร์มแก้ไขหมวดหมู่
    function showUpdateForm(Category $category): View
    {
        Gate::authorize('update', $category);
 
        return view('categories.update-form', [
            'category' => $category,
        ]);
    }
 
    // บันทึกการแก้ไขหมวดหมู่
    function update(Request $request, Category $category): RedirectResponse
    {
        Gate::authorize('update', $category);
 
        $data = $request->validate([
            'code' => 'required|max:64|unique:categories,code,' . $category->id,
            'name' => 'required|max:255',
            'description' => 'nullable|max:2000',
        ]);
 
        try {
            $category->update($data);
 
            return redirect()
                ->route('categories.view', $category)
                ->with('status', "Category {$category->code} was updated.");
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }
 
    // ลบหมวดหมู่ (ถ้ายังมีสินค้าอยู่จะลบไม่ได้ เพราะติด foreign key)
    function delete(Category $category): RedirectResponse
    {
        Gate::authorize('delete', $category);
 
        try {
            $category->delete();
 
            return redirect()
                ->route('categories.list')
                ->with('status', "Category {$category->code} was deleted.");
        } catch (QueryException $excp) {
            return redirect()->back()->withErrors([
                'alert' => $excp->errorInfo[2],
            ]);
        }
    }
}
 