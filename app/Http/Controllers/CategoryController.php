<?php

namespace App\Http\Controllers;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\ListCategoryResource;
use App\HttpResponse\HttpResponse;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use HttpResponse;

    public function list_all_categories()
    {
        $categories = Category::orderBy('priority')->get();
        return $this->success(ListCategoryResource::collection($categories),'all categories');
    }

    public function list_visible_categories()
    {
        $categories = Category::where('visible',1)->orderBy('priority')->get();
        return $this->success(ListCategoryResource::collection($categories),'all categories');
    }

    public function add_category(CategoryRequest $request)
    {
        $category = Category::create([
            'category' => $request->category,
            'parent_category' => $request->parent_category,
            'visible' => $request->visible,
            'priority' => $request->priority,
        ]);
        return $this->success(new ListCategoryResource($category),'category added successfully');
    }

    public function update_category(CategoryRequest $request)
    {
        $category = Category::findOrFail($request->category_id);
        
        $category->update([
            'category' => $request->category,
            'parent_category' => $request->parent_category,
            'visible' => $request->visible,
            'priority' => $request->priority,
        ]);
        return $this->success(new ListCategoryResource($category),'category updated successfully');
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
        return $this->success(null,'category deleted successfully');
    }


}
