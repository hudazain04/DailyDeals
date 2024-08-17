<?php

namespace App\Http\Controllers;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\ListCategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ListCategoryProductsResource;
use App\HttpResponse\HttpResponse;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use HttpResponse;

    public function list_all_categories()
    {
        $categories = Category::orderBy('priority')->get();
        return $this->success(ListCategoryResource::collection($categories),__('messages.CategoryController.List_All_Categories'));
    }

    public function list_visible_categories()
    {
        $categories = Category::where('visible',1)->orderBy('priority')->get();
        return $this->success(ListCategoryResource::collection($categories),__('messages.CategoryController.List_All_Categories'));
    }

    public function add_category(CategoryRequest $request)
    {
        $category = Category::create([
            'category' => $request->category,
            'parent_category' => $request->parent_category,
            'visible' => $request->visible,
            'priority' => $request->priority,
        ]);
        return $this->success(new ListCategoryResource($category),__('messages.CategoryController.Category_Added_Successfully'));
    }

    public function update_category(CategoryRequest $request)
    {
        $category = Category::findOrFail($request->category_id);
        
        $category->fill($request->only([
            'category', 
            'parent_category', 
            'visible', 
            'priority'
        ]));

        $category->save();

        return $this->success(new ListCategoryResource($category),__('messages.CategoryController.Category_Updated_Successfully'));
    }

    public function delete_category(Request $request)
    {
        $category = Category::findOrFail($request->category_id);
        $sub_categories = $category->children;
        if ($sub_categories) {
            foreach ($sub_categories as $sub_category) {
                $sub_category->parent_category = null;
                $sub_category->save();
            }
        }
        $category->delete();
        return $this->success(null,__('messages.CategoryController.Category_Deleted_Successfully'));
    }

    public function show_category(Request $request)
    {
        $category = Category::findOrFail($request->category_id);
        return $this->success(new ListCategoryResource($category),__('messages.CategoryController.Show_Category'));
    }

    public function show_category_with_products(Request $request)
    {
        $category = Category::findOrFail($request->category_id);
        return $this->success(new ListCategoryProductsResource($category),__('messages.CategoryController.Show_Category'));
    }
}
