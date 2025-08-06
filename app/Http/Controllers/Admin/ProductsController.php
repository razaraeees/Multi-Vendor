<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Section;
use App\Models\Product;
use App\Models\ProductsImage;
use App\Models\Attribute;
use App\Models\ProductsAttribute;
use Illuminate\Support\Facades\Log;

class ProductsController extends Controller
{
    public function products()
    {
        Session::put('page', 'products');
        $adminType = Auth::guard('admin')->user()->type;
        $vendor_id = Auth::guard('admin')->user()->vendor_id;

        if ($adminType == 'vendor') {
            $vendorStatus = Auth::guard('admin')->user()->status;
            if ($vendorStatus == 0) {
                return redirect('admin/update-vendor-details/personal')
                    ->with('error_message', 'Your Vendor Account is not approved yet. Please make sure to fill your valid personal, business and bank details.');
            }
        }

        // ✅ Fixed relationships
        $products = Product::with([
            'category' => function ($query) {
                $query->select('id', 'category_name');
            },
            'images' => function ($query) {
                $query->select('id', 'product_id', 'image', 'status');
            }
        ]);


        // If vendor, show only their products
        if ($adminType == 'vendor') {
            $products = $products->where('vendor_id', $vendor_id);
        }

        $products = $products->get()->toArray();

        return view('admin.products.products', compact('products'));
    }
    public function getValues($id)
    {
        $attribute = Attribute::with('attributeValues')->find($id);

        if (!$attribute) {
            return response()->json(['error' => 'Attribute not found'], 404);
        }

        return response()->json([
            'values' => $attribute->attributeValues->map(function ($v) {
                return ['id' => $v->id, 'value' => $v->value];
            })
        ]);
    }

    public function updateProductStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();

            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }

            Product::where('id', $data['product_id'])->update(['status' => $status]);

            return response()->json([
                'status'     => $status,
                'product_id' => $data['product_id']
            ]);
        }
    }

    public function deleteProduct($id)
    {
        Product::where('id', $id)->delete();

        $message = 'Product has been deleted successfully!';

        return redirect()->back()->with('success_message', $message);
    }

    public function addEditProduct(Request $request, $id = null)
    {
        try {
            // Check if editing existing product
            if ($id) {
                $product = Product::with('images')->find($id);
                if (!$product) {
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Product not found!'
                        ], 404);
                    }
                    return redirect('admin/products')->with('error_message', 'Product not found!');
                }
                $title = 'Edit Product';
                $message = 'Product updated successfully!';
            } else {
                $product = new Product();
                $title = 'Add Product';
                $message = 'Product added successfully!';
            }

            if ($request->ajax()) {
                try {
                    // Simple validation
                    $request->validate([
                        'category_id'   => 'required|exists:categories,id',
                        'product_name'  => 'required|string|max:255',
                        'product_code'  => 'required|string|max:50',
                        'product_price' => 'required|numeric|min:0',
                        'stock'         => 'required|numeric|min:0',
                        'stock_status'  => 'required|in:0,1',
                        // ✅ Attribute validation (optional)
                        'attribute_id'         => 'nullable|exists:attributes,id',
                        'attribute_value_id'   => 'nullable|array',
                        'attribute_value_id.*' => 'nullable|exists:attributes_values,id',
                    ]);

                    $data = $request->all();

                    // Get category details
                    $category = Category::find($data['category_id']);
                    if (!$category) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid category selected!'
                        ], 422);
                    }

                    $user = Auth::guard('admin')->user();

                    // ✅ Set admin info
                    $product->admin_id   = $user->id;
                    $product->admin_type = $user->type;

                    // ✅ Set vendor info (if applicable)
                    $product->vendor_id = $user->vendor_id ?? null;
                    $product->category_id      = $data['category_id'];
                    $product->brand_id         = $data['brand_id'] ?? null;
                    $product->product_name     = $data['product_name'];
                    $product->product_code     = $data['product_code'];
                    $product->product_color    = $data['product_color'] ?? '';
                    $product->product_price    = $data['product_price'];
                    $product->product_discount = $data['product_discount'] ?? 0;
                    $product->product_weight   = $data['product_weight'] ?? 0;
                    $product->group_code       = $data['group_code'] ?? '';
                    $product->description      = $data['description'] ?? '';
                    $product->meta_title       = $data['meta_title'] ?? '';
                    $product->meta_description = $data['meta_description'] ?? '';
                    $product->meta_keywords    = $data['meta_keywords'] ?? '';
                    $product->is_featured      = $data['is_featured'] ?? 'No';
                    $product->is_bestseller    = $data['is_bestseller'] ?? 'No';

                    // ✅ Stock fields
                    $product->stock            = $data['stock'] ?? 0;
                    $product->stock_status     = $data['stock_status'] ?? 0;
                    $product->status           = 1;

                    // ✅ Save to database
                    $product->save();

                    // ✅ Handle Attributes Saving
                    if (!empty($data['attribute_id']) && !empty($data['attribute_value_id'])) {
                        Log::info('Saving attributes for product: ' . $product->id);
                        Log::info('Attribute ID: ' . $data['attribute_id']);
                        Log::info('Attribute Values: ' . json_encode($data['attribute_value_id']));

                        // Delete existing attributes if editing
                        if ($id) {
                            ProductsAttribute::where('product_id', $product->id)->delete();
                            Log::info('Deleted existing attributes for product: ' . $product->id);
                        }

                        // Save new attributes
                        foreach ($data['attribute_value_id'] as $valueId) {
                            $productAttribute = new ProductsAttribute();
                            $productAttribute->product_id = $product->id;
                            $productAttribute->attribute_id = $data['attribute_id'];
                            $productAttribute->attribute_value_id = $valueId;
                            $productAttribute->save();

                            Log::info("Saved attribute - Product: {$product->id}, Attribute: {$data['attribute_id']}, Value: {$valueId}");
                        }
                    }

                    // Handle Image Upload
                    if ($request->hasFile('product_images')) {
                        foreach ($request->file('product_images') as $image) {
                            if ($image->isValid()) {
                                try {
                                    $extension = $image->getClientOriginalExtension();
                                    $imageName = 'product_' . time() . '_' . rand(1000, 9999) . '.' . $extension;

                                    // Create directories if they don't exist
                                    $imagePath = public_path('front/images/product_images');
                                    $smallPath = public_path('front/images/product_images/small');
                                    $mediumPath = public_path('front/images/product_images/medium');
                                    $largePath = public_path('front/images/product_images/large');

                                    if (!file_exists($imagePath)) {
                                        mkdir($imagePath, 0755, true);
                                    }
                                    if (!file_exists($smallPath)) {
                                        mkdir($smallPath, 0755, true);
                                    }
                                    if (!file_exists($mediumPath)) {
                                        mkdir($mediumPath, 0755, true);
                                    }
                                    if (!file_exists($largePath)) {
                                        mkdir($largePath, 0755, true);
                                    }

                                    // Move original image
                                    $image->move($imagePath, $imageName);

                                    // Copy to other sizes (you can customize this)
                                    copy($imagePath . '/' . $imageName, $smallPath . '/' . $imageName);
                                    copy($imagePath . '/' . $imageName, $mediumPath . '/' . $imageName);
                                    copy($imagePath . '/' . $imageName, $largePath . '/' . $imageName);

                                    // Save to database
                                    $productImage = new ProductsImage();
                                    $productImage->product_id = $product->id;
                                    $productImage->image = $imageName;
                                    $productImage->status = 1;
                                    $productImage->save();
                                } catch (\Exception $e) {
                                    Log::error('Image Upload Error: ' . $e->getMessage());
                                    continue; // Skip this image and continue
                                }
                            }
                        }
                    }

                    // Handle Video Upload
                    if ($request->hasFile('product_video')) {
                        $video = $request->file('product_video');
                        if ($video->isValid()) {
                            try {
                                $extension = $video->getClientOriginalExtension();
                                $videoName = 'product_video_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
                                $videoPath = public_path('front/videos/product_videos');

                                // Create directory if it doesn't exist
                                if (!file_exists($videoPath)) {
                                    mkdir($videoPath, 0755, true);
                                }

                                $video->move($videoPath, $videoName);
                                $product->product_video = $videoName;
                                $product->save();
                            } catch (\Exception $e) {
                                Log::error('Video Upload Error: ' . $e->getMessage());
                                // Don't return error, just log it
                            }
                        }
                    }

                    return response()->json([
                        'success' => true,
                        'message' => $message,
                        'redirect' => url('admin/products')
                    ]);
                } catch (\Illuminate\Validation\ValidationException $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $e->errors()
                    ], 422);
                } catch (\Exception $e) {
                    Log::error('Product Save Error: ' . $e->getMessage());

                    return response()->json([
                        'success' => false,
                        'message' => 'Something went wrong. Please try again.',
                        'error' => $e->getMessage()
                    ], 500);
                }
            }

            // Load data for form (Non-AJAX request)
            try {
                $categories = $this->getCategoriesWithPath();
                $brands = Brand::where('status', 1)->get()->toArray();
                $attributes = Attribute::with('attributeValues')->where('status', 1)->get();

                // ✅ Load existing product attributes if editing
                if ($id && $product) {
                    $product->selected_attributes = ProductsAttribute::where('product_id', $id)
                        ->with(['attribute', 'attributeValue'])
                        ->get();
                    Log::info('Loaded existing attributes: ' . $product->selected_attributes->count());
                }
            } catch (\Exception $e) {
                Log::error('Data Load Error: ' . $e->getMessage());
                $categories = [];
                $brands = [];
                $attributes = collect();
            }

            return view('admin.products.add_edit_product', compact('title', 'product', 'categories', 'brands', 'attributes'));
        } catch (\Exception $e) {
            Log::error('Controller Error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'System error occurred. Please try again.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect('admin/products')->with('error_message', 'Error loading product form.');
        }
    }// End Method
    private function getCategoriesWithPath()
    {
        // Sirf leaf categories (jo categories ke andar koi sub-category nahi hai)
        $categories = Category::with(['children', 'parentCategory'])
            ->where('status', 1)
            ->get();

        $categoriesWithPath = [];

        foreach ($categories as $category) {
            // Check if category has no children (leaf node)
            if ($category->children->isEmpty()) {
                $categoriesWithPath[] = [
                    'id' => $category->id,
                    'path' => $this->buildCategoryPath($category),
                ];
            }
        }

        // Sort by path
        usort($categoriesWithPath, function ($a, $b) {
            return strcmp($a['path'], $b['path']);
        });

        return $categoriesWithPath;
    }

    private function buildCategoryPath($category)
    {
        if ($category->parentCategory && $category->parent_id != 0) {
            return $this->buildCategoryPath($category->parentCategory) . ' > ' . $category->category_name;
        }

        return $category->category_name;
    }

    public function deleteProductVideo($id)
    {
        // Get the product video record stored in the database
        $productVideo = Product::select('product_video')->where('id', $id)->first();

        // Get the product video path on the server (filesystem)
        $product_video_path = 'front/videos/product_videos/';

        // Delete the product videos on server (filesystem) (from the the 'product_videos' folder)
        if (file_exists($product_video_path . $productVideo->product_video)) {
            unlink($product_video_path . $productVideo->product_video);
        }

        // Delete the product video name (record) from the `products` database table
        Product::where('id', $id)->update(['product_video' => '']);

        $message = 'Product Video has been deleted successfully!';

        return redirect()->back()->with('success_message', $message);
    }

    public function addAttributes(Request $request, $id)
    {
        Session::put('page', 'products');

        $product = Product::select('id', 'product_name', 'product_code', 'product_color', 'product_price', 'product_image')->with('attributes')->find($id);

        if ($request->isMethod('post')) {
            $data = $request->all();

            foreach ($data['sku'] as $key => $value) {
                if (!empty($value)) {
                    // Validation:
                    // SKU duplicate check
                    $skuCount = ProductsAttribute::where('sku', $value)->count();
                    if ($skuCount > 0) {
                        return redirect()->back()->with('error_message', 'SKU already exists! Please add another SKU!');
                    }

                    // Size duplicate check
                    $sizeCount = ProductsAttribute::where(['product_id' => $id, 'size' => $data['size'][$key]])->count();
                    if ($sizeCount > 0) {
                        return redirect()->back()->with('error_message', 'Size already exists! Please add another Size!');
                    }

                    $attribute = new ProductsAttribute;

                    $attribute->product_id = $id;
                    $attribute->sku        = $value;
                    $attribute->size       = $data['size'][$key];
                    $attribute->price      = $data['price'][$key];
                    $attribute->stock      = $data['stock'][$key];
                    $attribute->status     = 1;

                    $attribute->save();
                }
            }
            return redirect()->back()->with('success_message', 'Product Attributes have been addded successfully!');
        }

        return view('admin.attributes.add_edit_attributes')->with(compact('product'));
    }

    public function updateAttributeStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();

            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }

            ProductsAttribute::where('id', $data['attribute_id'])->update(['status' => $status]);

            return response()->json([
                'status'       => $status,
                'attribute_id' => $data['attribute_id']
            ]);
        }
    }

    public function editAttributes(Request $request)
    {
        Session::put('page', 'products');

        if ($request->isMethod('post')) {
            $data = $request->all();

            foreach ($data['attributeId'] as $key => $attribute) {
                if (!empty($attribute)) {
                    ProductsAttribute::where([
                        'id' => $data['attributeId'][$key]
                    ])->update([
                        'price' => $data['price'][$key],
                        'stock' => $data['stock'][$key]
                    ]);
                }
            }

            return redirect()->back()->with('success_message', 'Product Attributes have been updated successfully!');
        }
    }

    public function updateImageStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();

            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }

            ProductsImage::where('id', $data['image_id'])->update(['status' => $status]);

            return response()->json([
                'status'   => $status,
                'image_id' => $data['image_id']
            ]);
        }
    }

    public function deleteImage($id)
    {
        // Get the product image record stored in the database
        $productImage = ProductsImage::select('image')->where('id', $id)->first();

        // Get the product image three paths on the server (filesystem)
        $small_image_path  = 'front/images/product_images/small/';
        $medium_image_path = 'front/images/product_images/medium/';
        $large_image_path  = 'front/images/product_images/large/';

        // Delete the product images on server (filesystem)
        if (file_exists($small_image_path . $productImage->image)) {
            unlink($small_image_path . $productImage->image);
        }

        if (file_exists($medium_image_path . $productImage->image)) {
            unlink($medium_image_path . $productImage->image);
        }

        if (file_exists($large_image_path . $productImage->image)) {
            unlink($large_image_path . $productImage->image);
        }

        // Delete the product image name (record) from the database
        ProductsImage::where('id', $id)->delete();

        $message = 'Product Image has been deleted successfully!';

        return redirect()->back()->with('success_message', $message);
    }
}
