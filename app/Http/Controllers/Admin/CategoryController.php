<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Category;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    public function categories()
    {
        Session::put('page', 'categories');
        $allCategories = Category::orderBy('parent_id')->orderBy('id')->get()->toArray();

        // Process to add level
        $categories = [];
        foreach ($allCategories as $category) {
            if ($category['parent_id'] == 0) {
                $category['level'] = 1; 
            } else {
                // Find parent
                $parent = collect($allCategories)->firstWhere('id', $category['parent_id']);
                if ($parent && $parent['parent_id'] == 0) {
                    $category['level'] = 2; // Sub-category
                } else {
                    $category['level'] = 3; // Sub-sub-category
                }
            }
            $categories[] = $category;
        }

        return view('admin.categories.categories')->with(compact('categories'));
    }

    public function addEditCategory(Request $request, $id = null)
    {
        if ($id) {
            $title = 'Edit Category';
            $category = Category::findOrFail($id);
            $message = 'Category updated successfully!';
        } else {
            $title = 'Add Category';
            $category = new Category();
            $message = 'Category added successfully!';
        }

        // ✅ Only process form on POST request
        if ($request->isMethod('post')) {
            $rules = [
                'category_name' => 'required|string|max:100',
                'url' => 'required|unique:categories,url,' . ($id ?? 'NULL') . ',id',
                'parent_id'     => 'nullable|exists:categories,id',
            ];

            $customMessages = [
                'category_name.required' => 'Category name is required.',
                'url.required'           => 'URL is required.',
                'parent_id.exists'       => 'Invalid parent category.',
            ];

            $request->validate($rules, $customMessages);

            $data = $request->all();
            $parent_id = $data['parent_id'] ?? 0;
            $category->parent_id = $parent_id;

            if ($parent_id == 0) {
                $category->level = 1;
            } else {
                $parent = Category::find($parent_id);
                $category->level = $parent ? ($parent->level + 1) : 2;
            }

            $category->category_name = $data['category_name'];
            if (!empty($data['url'])) {
                $category->url = Str::slug($data['url']);
            } else {
                $category->url = Str::slug($data['category_name']);
            }
            $category->category_discount = $data['category_discount'] ?? 0;
            $category->description = $data['description'] ?? '';
            $category->meta_title = $data['meta_title'] ?? '';
            $category->meta_description = $data['meta_description'] ?? '';
            $category->meta_keywords = $data['meta_keywords'] ?? '';
            $category->status = 1;

            // Handle image upload
            if ($request->hasFile('category_image')) {
                $image = $request->file('category_image');
                if ($image->isValid()) {
                    $extension = $image->getClientOriginalExtension();
                    $imageName = rand(11111, 99999) . '.' . $extension;
                    $path = public_path('front/images/category_images/');

                    if (!is_dir($path)) {
                        mkdir($path, 0755, true);
                    }

                    $image->move($path, $imageName);

                    // Delete old image
                    if ($category->category_image && file_exists($path . $category->category_image)) {
                        unlink($path . $category->category_image);
                    }

                    $category->category_image = $imageName;
                }
            }

            $category->save();
            return redirect()->back()->with('success_message', $message);
        }

        // ✅ For GET request, just show the form
        return view('admin.categories.add_edit_category', compact('title', 'category'));
    }

    public function updateCategoryStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            $category = Category::findOrFail($data['category_id']);
            $category->status = $data['status'] == 'Active' ? 0 : 1;
            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!',
                'status' => $category->status
            ]);
        }
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $hasSub = Category::where('parent_id', $id)->exists();
        if ($hasSub) {
            return redirect()->back()->with('error_message', 'Cannot delete category with subcategories!');
        }

        // Delete image if exists
        if ($category->category_image) {
            $imagePath = public_path('front/images/category_images/' . $category->category_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $category->delete();

        return redirect()->back()->with('success_message', 'Category deleted successfully!');
    }

    public function deleteCategoryImage($id)
    {
        $category = Category::findOrFail($id);

        if ($category->category_image) {
            $imagePath = public_path('front/images/category_images/' . $category->category_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $category->category_image = null;
            $category->save();
        }

        return redirect()->back()->with('success_message', 'Category image deleted!');
    } // End Method

    public function getParentCategories(Request $request)
    {

        $level = $request->query('level');

        // Level 2 ki parent → Level 1
        if ($level == 2) {
            return Category::where('parent_id', 0) // Main categories
                ->where('status', 1)
                ->select('id', 'category_name')
                ->get();
        }
        // Level 3 ki parent → Level 2
        elseif ($level == 3) {
            return Category::where('parent_id', '!=', 0) // Sub-categories
                ->whereHas('parentCategory', function ($q) {
                    $q->where('parent_id', 0); // Parent Level 1 ka ho
                })
                ->where('status', 1)
                ->select('id', 'category_name')
                ->get();
        }

        return [];
    }
}
