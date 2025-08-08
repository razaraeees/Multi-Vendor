<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $fillable = ['section_id', 'parent_id', 'category_name', 'description', 'url', 'meta_title', 'meta_description', 'meta_keywords', 'status'];

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->where('status', 1)
            ->with('subcategories'); // recursive call for nested categories
    }
    public static function categories()
    {
        $getCategories = Category::with('subcategories.subcategories')
            ->where(['parent_id' => 0, 'status' => 1])
            ->get();

        return $getCategories;
    }

    // Multi-level categories (and subcategories (children)) relationships
    public function parentCategory()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id')
            ->where('id', '>', 0); // parent_id = 0 ko ignore karo
    }

    public function children()
    { // this method could be better named 'children'    // This relationship brings the categories that point to the current category (using their `parent_id`) (Example: If the current category with `id` = 4, i.e. \App\Models\Category::find(4), the relationship brings all the categories that their `parent_id` = 4)    // A one category can have many subcategories (this is a relationship inside the same table `categories` (not between two different tables))    
        return $this->hasMany('App\Models\Category', 'parent_id')->where('status', 1);
    }


    public static function categoryDetails($url)
    {
        // Get category with level field
        $categoryDetails = Category::select('id', 'parent_id', 'category_name', 'url', 'description', 'level', 'meta_title', 'meta_description', 'meta_keywords')
            ->with([
                'subCategories' => function ($query) {
                    $query->select('id', 'parent_id', 'category_name', 'url', 'description', 'level', 'meta_title', 'meta_description', 'meta_keywords');
                }
            ])
            ->where('url', $url)
            ->first();

        // Check if category exists
        if (!$categoryDetails) {
            abort(404, 'Category not found');
        }

        $categoryDetails = $categoryDetails->toArray();
        $catIds = array();
        $catIds[] = $categoryDetails['id'];

        // Build breadcrumbs based on level
        $breadcrumbs = '';

        if ($categoryDetails['level'] == 1) {
            // Main category (level 1)
            $breadcrumbs = '
            <li class="is-marked"><a href="' . url($categoryDetails['url']) . '">' . $categoryDetails['category_name'] . '</a></li>
        ';
        } elseif ($categoryDetails['level'] == 2) {
            // Sub category (level 2) - show parent + current
            $parentCategory = Category::select('category_name', 'url', 'level')
                ->where('id', $categoryDetails['parent_id'])
                ->first();

            if ($parentCategory) {
                $breadcrumbs = '
                <li class="has-separator"><a href="' . url($parentCategory->url) . '">' . $parentCategory->category_name . '</a></li>
                <li class="is-marked"><a href="' . url($categoryDetails['url']) . '">' . $categoryDetails['category_name'] . '</a></li>
            ';
            }
        } elseif ($categoryDetails['level'] == 3) {
            // Sub-sub category (level 3) - show grandparent + parent + current
            $parentCategory = Category::select('id', 'parent_id', 'category_name', 'url', 'level')
                ->where('id', $categoryDetails['parent_id'])
                ->first();

            if ($parentCategory) {
                $grandParentCategory = Category::select('category_name', 'url')
                    ->where('id', $parentCategory->parent_id)
                    ->first();

                if ($grandParentCategory) {
                    $breadcrumbs = '
                    <li class="has-separator"><a href="' . url($grandParentCategory->url) . '">' . $grandParentCategory->category_name . '</a></li>
                    <li class="has-separator"><a href="' . url($parentCategory->url) . '">' . $parentCategory->category_name . '</a></li>
                    <li class="is-marked"><a href="' . url($categoryDetails['url']) . '">' . $categoryDetails['category_name'] . '</a></li>
                ';
                } else {
                    $breadcrumbs = '
                    <li class="has-separator"><a href="' . url($parentCategory->url) . '">' . $parentCategory->category_name . '</a></li>
                    <li class="is-marked"><a href="' . url($categoryDetails['url']) . '">' . $categoryDetails['category_name'] . '</a></li>
                ';
                }
            }
        }

        // Get all subcategory IDs
        if (isset($categoryDetails['sub_categories']) && is_array($categoryDetails['sub_categories'])) {
            foreach ($categoryDetails['sub_categories'] as $subcat) {
                $catIds[] = $subcat['id'];
            }
        }

        $resp = array(
            'catIds'          => $catIds,
            'categoryDetails' => $categoryDetails,
            'breadcrumbs'     => $breadcrumbs
        );

        return $resp;
    }


    // this method is called in admin\filters\filters.blade.php to be able to translate the filter cat_ids column to category names to show them in the table in filters.blade.php in the Admin Panel    
    public static function getCategoryName($category_id)
    {
        return Category::where('id', $category_id)
            ->value('category_name') ?? 'Unknown';
    }

    // Note: We also prevent making orders of the products of the Categories that are disabled (`status` = 0) (whether the Category is a Child Category or a Parent Category (Root Category) is disabled) in admin/categories/categories.blade.php
    public static function getCategoryStatus($category_id)
    {
        $getCategoryStatus = Category::select('status')->where('id', $category_id)->first();

        return $getCategoryStatus->status;
    }
    public function products()
    {
        return $this->hasMany(\App\Models\Product::class, 'category_id');
    }
    // app/Models/Category.php


}
