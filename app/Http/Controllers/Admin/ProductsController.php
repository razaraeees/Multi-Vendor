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
use App\Models\ProductsFilter;
use App\Models\ProductsAttribute;
// use App\Models\ProductsImage;
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

        // ✅ Include 'images' relationship
        $products = Product::with([
            'section' => function ($query) {
                $query->select('id', 'name');
            },
            'category' => function ($query) {
                $query->select('id', 'category_name');
            },
            'images' // ✅ Add this line
        ]);

        // If vendor, show only their products
        if ($adminType == 'vendor') {
            $products = $products->where('vendor_id', $vendor_id);
        }

        $products = $products->get()->toArray();

        return view('admin.products.products', compact('products'));
    }

    public function updateProductStatus(Request $request)
    {
        if ($request->ajax()) { // if the request is coming via an AJAX call
            $data = $request->all(); // Getting the name/value pairs array that are sent from the AJAX request (AJAX call)
            // dd($data);

            if ($data['status'] == 'Active') { // $data['status'] comes from the 'data' object inside the $.ajax() method    // reverse the 'status' from (ative/inactive) 0 to 1 and 1 to 0 (and vice versa)
                $status = 0;
            } else {
                $status = 1;
            }


            Product::where('id', $data['product_id'])->update(['status' => $status]); // $data['product_id'] comes from the 'data' object inside the $.ajax() method
            // echo '<pre>', var_dump($data), '</pre>';

            return response()->json([ // JSON Responses: https://laravel.com/docs/9.x/responses#json-responses
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
                $product = Product::with('product_images')->find($id);
                if (!$product) {
                    return $request->ajax()
                        ? response()->json(['success' => false, 'message' => 'Product not found!'], 404)
                        : redirect('admin/products')->with('error_message', 'Product not found!');
                }
                $title = 'Edit Product';
                $message = 'Product updated successfully!';
            } else {
                $product = new Product();
                $title = 'Add Product';
                $message = 'Product added successfully!';
            }

            // Categories for dropdown
            $categoriesData = Category::with('parent')->where('status', 1)->get();
            $categoriesDropdown = $categoriesData->map(function ($cat) {
                return [
                    'id'   => $cat->id,
                    'path' => $this->buildCategoryPath($cat)
                ];
            });

            // Brands list
            $brands = Brand::where('status', 1)->get();

            // Handle AJAX form submission
            if ($request->ajax()) {
                try {
                    $request->validate([
                        'category_id'   => 'required|exists:categories,id',
                        'product_name'  => 'required|string|max:255',
                        'product_code'  => [
                            'required',
                            'string',
                            'max:50',
                            Rule::unique('products', 'product_code')->ignore($id)
                        ],
                        'product_price' => 'required|numeric|min:0',
                    ]);

                    $data = $request->all();
                    $user = Auth::guard('admin')->user();

                    // Assign product data
                    $product->admin_id   = $user->id;
                    $product->admin_type = $user->type;
                    $product->vendor_id  = $user->vendor_id ?? null;

                    $category = Category::findOrFail($data['category_id']);
                    $product->section_id       = $category->section_id;
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
                    $product->status           = 1;
                    $product->save();

                    // Handle Image Upload
                    if ($request->hasFile('product_images')) {
                        foreach ($request->file('product_images') as $image) {
                            if ($image->isValid()) {
                                try {
                                    $extension = $image->getClientOriginalExtension();
                                    $imageName = 'product_' . time() . '_' . rand(1000, 9999) . '.' . $extension;

                                    $imagePath  = public_path('front/images/product_images');
                                    $smallPath  = $imagePath . '/small';
                                    $mediumPath = $imagePath . '/medium';
                                    $largePath  = $imagePath . '/large';

                                    foreach ([$imagePath, $smallPath, $mediumPath, $largePath] as $path) {
                                        if (!file_exists($path)) {
                                            mkdir($path, 0755, true);
                                        }
                                    }

                                    $image->move($imagePath, $imageName);
                                    copy($imagePath . '/' . $imageName, $smallPath . '/' . $imageName);
                                    copy($imagePath . '/' . $imageName, $mediumPath . '/' . $imageName);
                                    copy($imagePath . '/' . $imageName, $largePath . '/' . $imageName);

                                    ProductsImage::create([
                                        'product_id' => $product->id,
                                        'image'      => $imageName,
                                        'status'     => 1
                                    ]);
                                } catch (\Exception $e) {
                                    Log::error('Image Upload Error: ' . $e->getMessage());
                                    continue;
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

                                if (!file_exists($videoPath)) {
                                    mkdir($videoPath, 0755, true);
                                }

                                $video->move($videoPath, $videoName);
                                $product->product_video = $videoName;
                                $product->save();
                            } catch (\Exception $e) {
                                Log::error('Video Upload Error: ' . $e->getMessage());
                            }
                        }
                    }

                    return response()->json([
                        'success'  => true,
                        'message'  => $message,
                        'redirect' => url('admin/products')
                    ]);
                } catch (\Illuminate\Validation\ValidationException $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors'  => $e->errors()
                    ], 422);
                } catch (\Exception $e) {
                    Log::error('Product Save Error: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Something went wrong. Please try again.',
                        'error'   => $e->getMessage()
                    ], 500);
                }
            }

            // Non-AJAX Request → Show form
            return view('admin.products.add_edit_product', compact('title', 'product', 'categoriesDropdown', 'brands'));
        } catch (\Exception $e) {
            Log::error('Controller Error: ' . $e->getMessage());

            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'System error occurred. Please try again.', 'error' => $e->getMessage()], 500)
                : redirect('admin/products')->with('error_message', 'Error loading product form.');
        }
    }

    private function buildCategoryPath($category)
    {
        $path = $category->category_name;
        $parent = $category->parent;
        while ($parent) {
            $path = $parent->category_name . ' > ' . $path;
            $parent = $parent->parent;
        }
        return $path;
    }


    // public function deleteProductImage($id)
    // {
    //     $image = ProductsImage::findOrFail($id); // Find the image from products_images table

    //     $paths = [
    //         public_path('front/images/product_images/small/' . $image->image),
    //         public_path('front/images/product_images/medium/' . $image->image),
    //         public_path('front/images/product_images/large/' . $image->image),
    //     ];

    //     foreach ($paths as $path) {
    //         if (file_exists($path)) {
    //             unlink($path);
    //         }
    //     }

    //     $image->delete();


    //     return redirect()->back()->with('success_message', $message);
    // }

    public function deleteProductVideo($id)
    { // AJAX call from admin/js/custom.js    // Delete the product video from BOTH SERVER (FILESYSTEM) & DATABASE    // $id is passed as a Route Parameter    
        // Get the product video record stored in the database
        $productVideo = Product::select('product_video')->where('id', $id)->first();
        // dd($productVideo);

        // Get the product video path on the server (filesystem)
        $product_video_path = 'front/videos/product_videos/';

        // Delete the product videos on server (filesystem) (from the the 'product_videos' folder)
        if (file_exists($product_video_path . $productVideo->product_video)) {
            unlink($product_video_path . $productVideo->product_video);
        }

        // Delete the product video name (record) from the `products` database table (Note: We won't use delete() method because we're not deleting a complete record (entry) (we're just deleting a one column `product_video` value), we will just use update() method to update the `product_video` name to an empty string value '')
        Product::where('id', $id)->update(['product_video' => '']);

        $message = 'Product Video has been deleted successfully!';

        return redirect()->back()->with('success_message', $message);
    }

    public function addAttributes(Request $request, $id)
    {
        Session::put('page', 'products');

        $product = Product::select('id', 'product_name', 'product_code', 'product_color', 'product_price', 'product_image')->with('attributes')->find($id); // with('attributes') is the relationship method name in the Product.php model

        if ($request->isMethod('post')) { // When the <form> is submitted
            $data = $request->all();
            // dd($data);

            foreach ($data['sku'] as $key => $value) { // or instead could be: $data['size'], $data['price'] or $data['stock']
                // echo '<pre>', var_dump($key), '</pre>';
                // echo '<pre>', var_dump($value), '</pre>';

                if (!empty($value)) {
                    // Validation:
                    // SKU duplicate check (Prevent duplicate SKU) because SKU is UNIQUE for every product
                    $skuCount = ProductsAttribute::where('sku', $value)->count();
                    if ($skuCount > 0) { // if there's an SKU for the product ALREADY EXISTING
                        return redirect()->back()->with('error_message', 'SKU already exists! Please add another SKU!');
                    }

                    // Size duplicate check (Prevent duplicate Size) because Size is UNIQUE for every product
                    $sizeCount = ProductsAttribute::where(['product_id' => $id, 'size' => $data['size'][$key]])->count();
                    if ($sizeCount > 0) { // if there's an SKU for the product ALREADY EXISTING
                        return redirect()->back()->with('error_message', 'Size already exists! Please add another Size!');
                    }


                    $attribute = new ProductsAttribute;

                    $attribute->product_id = $id; // $id is passed in up there to the addAttributes() method
                    $attribute->sku        = $value;
                    $attribute->size       = $data['size'][$key];  // $key denotes the iteration/loop cycle number (0, 1, 2, ...), e.g. $data['size'][0]
                    $attribute->price      = $data['price'][$key]; // $key denotes the iteration/loop cycle number (0, 1, 2, ...), e.g. $data['price'][0]
                    $attribute->stock      = $data['stock'][$key]; // $key denotes the iteration/loop cycle number (0, 1, 2, ...), e.g. $data['stock'][0]
                    $attribute->status     = 1;

                    $attribute->save();
                }
            }
            return redirect()->back()->with('success_message', 'Product Attributes have been addded successfully!');
        }


        return view('admin.attributes.add_edit_attributes')->with(compact('product'));
    }

    public function updateAttributeStatus(Request $request)
    { // Update Attribute Status using AJAX in add_edit_attributes.blade.php
        if ($request->ajax()) { // if the request is coming via an AJAX call
            $data = $request->all(); // Getting the name/value pairs array that are sent from the AJAX request (AJAX call)
            // dd($data);

            if ($data['status'] == 'Active') { // $data['status'] comes from the 'data' object inside the $.ajax() method    // reverse the 'status' from (ative/inactive) 0 to 1 and 1 to 0 (and vice versa)
                $status = 0;
            } else {
                $status = 1;
            }


            ProductsAttribute::where('id', $data['attribute_id'])->update(['status' => $status]); // $data['attribute_id'] comes from the 'data' object inside the $.ajax() method

            return response()->json([ // JSON Responses: https://laravel.com/docs/9.x/responses#json-responses
                'status'       => $status,
                'attribute_id' => $data['attribute_id']
            ]);
        }
    }

    public function editAttributes(Request $request)
    {
        Session::put('page', 'products');

        if ($request->isMethod('post')) { // if the <form> is submitted
            $data = $request->all();
            // dd($data);

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

    // public function addImages(Request $request, $id)
    // { // $id is the URL Paramter (slug) passed from the URL
    //     Session::put('page', 'products');

    //     $product = Product::select('id', 'product_name', 'product_code', 'product_color', 'product_price', 'product_image')->with('images')->find($id); // with('images') is the relationship method name in the Product.php model


    //     if ($request->isMethod('post')) { // if the <form> is submitted
    //         $data = $request->all();
    //         // dd($data);

    //         if ($request->hasFile('images')) {
    //             $images = $request->file('images');
    //             // dd($images);

    //             foreach ($images as $key => $image) {
    //                 // Uploading the images:
    //                 // Generate Temp Image
    //                 $image_tmp = Image::make($image);

    //                 // Get image name
    //                 $image_name = $image->getClientOriginalName();
    //                 // dd($image_tmp);

    //                 // Get image extension
    //                 $extension = $image->getClientOriginalExtension();

    //                 // Generate a new random name for the uploaded image (to avoid that the image might get overwritten if its name is repeated)
    //                 $imageName = $image_name . rand(111, 99999) . '.' . $extension; // e.g. 5954.png

    //                 // Assigning the uploaded images path inside the 'public' folder
    //                 // We will have three folders: small, medium and large, depending on the images sizes
    //                 $largeImagePath  = 'front/images/product_images/large/'  . $imageName; // 'large'  images folder
    //                 $mediumImagePath = 'front/images/product_images/medium/' . $imageName; // 'medium' images folder
    //                 $smallImagePath  = 'front/images/product_images/small/'  . $imageName; // 'small'  images folder

    //                 // Upload the image using the 'Intervention' package and save it in our THREE paths (folders) inside the 'public' folder
    //                 Image::make($image_tmp)->resize(1000, 1000)->save($largeImagePath);  // resize the 'large'  image size then store it in the 'large'  folder
    //                 Image::make($image_tmp)->resize(500,   500)->save($mediumImagePath); // resize the 'medium' image size then store it in the 'medium' folder
    //                 Image::make($image_tmp)->resize(250,   250)->save($smallImagePath);  // resize the 'small'  image size then store it in the 'small'  folder

    //                 // Insert the image name in the database table `products_images`
    //                 $image = new ProductsImage;

    //                 $image->image      = $imageName;
    //                 $image->product_id = $id;
    //                 $image->status     = 1;

    //                 $image->save();
    //             }
    //         }

    //         return redirect()->back()->with('success_message', 'Product Images have been added successfully!');
    //     }


    //     return view('admin.images.add_images')->with(compact('product'));
    // }

    public function updateImageStatus(Request $request)
    { // Update Image Status using AJAX in add_images.blade.php
        if ($request->ajax()) { // if the request is coming via an AJAX call
            $data = $request->all(); // Getting the name/value pairs array that are sent from the AJAX request (AJAX call)
            // dd($data);

            if ($data['status'] == 'Active') { // $data['status'] comes from the 'data' object inside the $.ajax() method    // reverse the 'status' from (ative/inactive) 0 to 1 and 1 to 0 (and vice versa)
                $status = 0;
            } else {
                $status = 1;
            }


            ProductsImage::where('id', $data['image_id'])->update(['status' => $status]); // $data['image_id'] comes from the 'data' object inside the $.ajax() method

            return response()->json([ // JSON Responses: https://laravel.com/docs/9.x/responses#json-responses
                'status'   => $status,
                'image_id' => $data['image_id']
            ]);
        }
    }

    public function deleteImage($id)
    { // AJAX call from admin/js/custom.js    // Delete the product image from BOTH SERVER (FILESYSTEM) & DATABASE    // $id is passed as a Route Parameter    
        // Get the product image record stored in the database
        $productImage = ProductsImage::select('image')->where('id', $id)->first();
        // dd($productImage);

        // Get the product image three paths on the server (filesystem) ('small', 'medium' and 'large' folders)
        $small_image_path  = 'front/images/product_images/small/';
        $medium_image_path = 'front/images/product_images/medium/';
        $large_image_path  = 'front/images/product_images/large/';

        // Delete the product images on server (filesystem) (from the the THREE folders)
        // First: Delete from the 'small' folder
        if (file_exists($small_image_path . $productImage->image)) {
            unlink($small_image_path . $productImage->image);
        }

        // Second: Delete from the 'medium' folder
        if (file_exists($medium_image_path . $productImage->image)) {
            unlink($medium_image_path . $productImage->image);
        }

        // Third: Delete from the 'large' folder
        if (file_exists($large_image_path . $productImage->image)) {
            unlink($large_image_path . $productImage->image);
        }


        // Delete the product image name (record) from the `products_images` database table
        ProductsImage::where('id', $id)->delete();

        $message = 'Product Image has been deleted successfully!';

        return redirect()->back()->with('success_message', $message);
    }
}
