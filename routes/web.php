<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

// FRONT CONTROLLERS
use App\Http\Controllers\Front\IndexController;
use App\Http\Controllers\Front\VendorController;
use App\Http\Controllers\Front\ProductsController as FrontProductsController;
use App\Http\Controllers\Front\UserController as FrontUserController;
use App\Http\Controllers\Front\NewsletterController as FrontNewsletterController;
use App\Http\Controllers\Front\RatingController as FrontRatingController;
use App\Http\Controllers\Front\OrderController as FrontOrderController;
use App\Http\Controllers\Front\CmsController;
use App\Http\Controllers\Front\AddressController;
use App\Http\Controllers\Front\PaypalController;
use App\Http\Controllers\Front\IyzipayController;

// ADMIN CONTROLLERS
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductsController as AdminProductsController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Admin\RatingController as AdminRatingController;
use App\Http\Controllers\Admin\BannersController;
use App\Http\Controllers\Admin\FilterController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\ShippingController;

require __DIR__.'/auth.php';

Route::prefix('/admin')->group(function() {
    Route::match(['get', 'post'], 'login', [AdminController::class, 'login']);

    Route::middleware(['admin'])->group(function () {
        Route::get('new-dashboard', [AdminController::class, 'newDashboard']);
        Route::get('dashboard', [AdminController::class, 'dashboard']);
        Route::get('logout', [AdminController::class, 'logout']);
        Route::match(['get', 'post'], 'update-admin-password', [AdminController::class, 'updateAdminPassword']);
        Route::post('check-admin-password', [AdminController::class, 'checkAdminPassword']);
        Route::match(['get', 'post'], 'update-admin-details', [AdminController::class, 'updateAdminDetails']);
        Route::match(['get', 'post'], 'update-vendor-details/{slug}', [AdminController::class, 'updateVendorDetails']);
        Route::post('update-vendor-commission', [AdminController::class, 'updateVendorCommission']);
        Route::get('admins/{type?}', [AdminController::class, 'admins']);
        Route::get('view-vendor-details/{id}', [AdminController::class, 'viewVendorDetails']);
        Route::post('update-admin-status', [AdminController::class, 'updateAdminStatus']);

        // Sections
        Route::resource('sections', SectionController::class)->only(['index'])->middleware('adminType:superadmin');
        Route::post('update-section-status', [SectionController::class, 'updateSectionStatus'])->middleware('adminType:superadmin');
        Route::get('delete-section/{id}', [SectionController::class, 'deleteSection'])->middleware('adminType:superadmin');
        Route::match(['get', 'post'], 'add-edit-section/{id?}', [SectionController::class, 'addEditSection'])->middleware('adminType:superadmin');

        // Categories
        Route::get('categories', [CategoryController::class, 'categories'])->middleware('adminType:superadmin');
        Route::post('update-category-status', [CategoryController::class, 'updateCategoryStatus'])->middleware('adminType:superadmin');
        Route::match(['get', 'post'], 'add-edit-category/{id?}', [CategoryController::class, 'addEditCategory'])->middleware('adminType:superadmin');
        Route::get('append-categories-level', [CategoryController::class, 'appendCategoryLevel'])->middleware('adminType:superadmin');
        Route::get('delete-category/{id}', [CategoryController::class, 'deleteCategory'])->middleware('adminType:superadmin');
        Route::get('delete-category-image/{id}', [CategoryController::class, 'deleteCategoryImage'])->middleware('adminType:superadmin');

        // Brands
        Route::get('brands', [BrandController::class, 'brands'])->middleware('adminType:superadmin');
        Route::post('update-brand-status', [BrandController::class, 'updateBrandStatus'])->middleware('adminType:superadmin');
        Route::get('delete-brand/{id}', [BrandController::class, 'deleteBrand'])->middleware('adminType:superadmin');
        Route::match(['get', 'post'], 'add-edit-brand/{id?}', [BrandController::class, 'addEditBrand'])->middleware('adminType:superadmin');

        // Products
        Route::get('products', [AdminProductsController::class, 'products']);
        Route::post('update-product-status', [AdminProductsController::class, 'updateProductStatus']);
        Route::get('delete-product/{id}', [AdminProductsController::class, 'deleteProduct']);
        Route::match(['get', 'post'], 'add-edit-product/{id?}', [AdminProductsController::class, 'addEditProduct']);
        Route::get('delete-product-image/{id}', [AdminProductsController::class, 'deleteProductImage']);
        Route::get('delete-product-video/{id}', [AdminProductsController::class, 'deleteProductVideo']);

        // Attributes
        Route::match(['get', 'post'], 'add-edit-attributes/{id}', [AdminProductsController::class, 'addAttributes']);  
        Route::post('update-attribute-status', [AdminProductsController::class, 'updateAttributeStatus']);
        Route::get('delete-attribute/{id}', [AdminProductsController::class, 'deleteAttribute']);
        Route::match(['get', 'post'], 'edit-attributes/{id}', [AdminProductsController::class, 'editAttributes']);

        // Images
        Route::match(['get', 'post'], 'add-images/{id}', [AdminProductsController::class, 'addImages']);
        Route::post('update-image-status', [AdminProductsController::class, 'updateImageStatus']);
        Route::get('delete-image/{id}', [AdminProductsController::class, 'deleteImage']);

        // Banners
        Route::get('banners', [BannersController::class, 'banners'])->middleware('adminType:superadmin');
        Route::post('update-banner-status', [BannersController::class, 'updateBannerStatus'])->middleware('adminType:superadmin');
        Route::get('delete-banner/{id}', [BannersController::class, 'deleteBanner'])->middleware('adminType:superadmin');
        Route::match(['get', 'post'], 'add-edit-banner/{id?}', [BannersController::class, 'addEditBanner'])->middleware('adminType:superadmin');

        // Filters
        Route::get('filters', [FilterController::class, 'filters'])->middleware('adminType:superadmin');
        Route::post('update-filter-status', [FilterController::class, 'updateFilterStatus'])->middleware('adminType:superadmin');
        Route::post('update-filter-value-status', [FilterController::class, 'updateFilterValueStatus'])->middleware('adminType:superadmin');
        Route::get('filters-values', [FilterController::class, 'filtersValues'])->middleware('adminType:superadmin');
        Route::match(['get', 'post'], 'add-edit-filter/{id?}', [FilterController::class, 'addEditFilter'])->middleware('adminType:superadmin');
        Route::match(['get', 'post'], 'add-edit-filter-value/{id?}', [FilterController::class, 'addEditFilterValue'])->middleware('adminType:superadmin');
        Route::post('category-filters', [FilterController::class, 'categoryFilters'])->middleware('adminType:superadmin');

        // Coupons
        Route::get('coupons', [CouponsController::class, 'coupons'])->middleware('adminType:superadmin');
        Route::post('update-coupon-status', [CouponsController::class, 'updateCouponStatus'])->middleware('adminType:superadmin');
        Route::get('delete-coupon/{id}', [CouponsController::class, 'deleteCoupon'])->middleware('adminType:superadmin');
        Route::match(['get', 'post'], 'add-edit-coupon/{id?}', [CouponsController::class, 'addEditCoupon'])->middleware('adminType:superadmin');

        // Users
        Route::get('users', [AdminUserController::class, 'users'])->middleware('adminType:superadmin');
        Route::post('update-user-status', [AdminUserController::class, 'updateUserStatus'])->middleware('adminType:superadmin');

        // Orders
        Route::get('orders', [AdminOrderController::class, 'orders']);
        Route::get('orders/{id}', [AdminOrderController::class, 'orderDetails']);
        Route::post('update-order-status', [AdminOrderController::class, 'updateOrderStatus']);
        Route::post('update-order-item-status', [AdminOrderController::class, 'updateOrderItemStatus']);
        Route::get('orders/invoice/{id}', [AdminOrderController::class, 'viewOrderInvoice']);
        Route::get('orders/invoice/pdf/{id}', [AdminOrderController::class, 'viewPDFInvoice']);

        // Shipping
        Route::get('shipping-charges', [ShippingController::class, 'shippingCharges']);
        Route::post('update-shipping-status', [ShippingController::class, 'updateShippingStatus']);
        Route::match(['get', 'post'], 'edit-shipping-charges/{id}', [ShippingController::class, 'editShippingCharges']);

        // Subscribers
        Route::get('subscribers', [AdminNewsletterController::class, 'subscribers'])->middleware('adminType:superadmin');
        Route::post('update-subscriber-status', [AdminNewsletterController::class, 'updateSubscriberStatus'])->middleware('adminType:superadmin');
        Route::get('delete-subscriber/{id}', [AdminNewsletterController::class, 'deleteSubscriber'])->middleware('adminType:superadmin');
        Route::get('export-subscribers', [AdminNewsletterController::class, 'exportSubscribers'])->middleware('adminType:superadmin');

        // Ratings
        Route::get('ratings', [AdminRatingController::class, 'ratings'])->middleware('adminType:superadmin');
        Route::post('update-rating-status', [AdminRatingController::class, 'updateRatingStatus'])->middleware('adminType:superadmin');
        Route::get('delete-rating/{id}', [AdminRatingController::class, 'deleteRating'])->middleware('adminType:superadmin');
    });
});

// Front Invoice PDF Download
Route::get('orders/invoice/download/{id}', [AdminOrderController::class, 'viewPDFInvoice']);

Route::prefix('/')->name('front.')->group(function () {
    Route::get('/', [IndexController::class, 'index']);

    // Dynamic category routes
    if (Schema::hasTable('categories')) {
        try {
            $catUrls = \App\Models\Category::where('status', 1)->pluck('url')->toArray();
            foreach ($catUrls as $url) {
                Route::match(['get', 'post'], '/' . $url, [FrontProductsController::class, 'listing']);
            }
        } catch (\Exception $e) {}
    }

    // Vendor
    Route::get('vendor/login-register', [VendorController::class, 'loginRegister']);
    Route::post('vendor/register', [VendorController::class, 'vendorRegister']);
    Route::get('vendor/confirm/{code}', [VendorController::class, 'confirmVendor']);

    // Product
    Route::get('/shop', [FrontProductsController::class, 'listing'])->name('shop');
    Route::get('product/{id}', [FrontProductsController::class, 'detail']);
    Route::post('get-product-price', [FrontProductsController::class, 'getProductPrice']);
    Route::get('products/{vendorid}', [FrontProductsController::class, 'vendorListing']);

    // Cart
    Route::post('cart/add', [FrontProductsController::class, 'cartAdd']);
    Route::get('cart', [FrontProductsController::class, 'cart'])->name('cart');
    Route::post('cart/update', [FrontProductsController::class, 'cartUpdate']);
    // routes/web.php
Route::post('/cart/delete', [FrontProductsController::class, 'cartDelete']);


    // User Auth
    Route::get('user/login-register', [FrontUserController::class, 'loginRegister'])->name('login');
    Route::post('user/register', [FrontUserController::class, 'userRegister']);
    Route::post('user/login', [FrontUserController::class, 'userLogin']);
    Route::get('user/logout', [FrontUserController::class, 'userLogout']);
    Route::match(['get', 'post'], 'user/forgot-password', [FrontUserController::class, 'forgotPassword']);
    Route::get('user/confirm/{code}', [FrontUserController::class, 'confirmAccount']);

    // General
    Route::get('search-products', [FrontProductsController::class, 'listing']);
    Route::post('check-pincode', [FrontProductsController::class, 'checkPincode']);
    Route::match(['get', 'post'], 'contact', [CmsController::class, 'contact']);
    Route::get('about', [CmsController::class, 'about']);

    Route::post('add-subscriber-email', [FrontNewsletterController::class, 'addSubscriber']);
    Route::post('add-rating', [FrontRatingController::class, 'addRating']);

    Route::middleware(['auth'])->group(function () {
        Route::match(['get', 'post'], 'user/account', [FrontUserController::class, 'userAccount']);
        Route::post('user/update-password', [FrontUserController::class, 'userUpdatePassword']);
        Route::post('apply-coupon', [FrontProductsController::class, 'applyCoupon']);
        Route::match(['get', 'post'], 'checkout', [FrontProductsController::class, 'checkout']);
        Route::get('thanks', [FrontProductsController::class, 'thanks']);
        Route::get('user/orders/{id?}', [FrontOrderController::class, 'orders']);

        // Address
        Route::post('get-delivery-address', [AddressController::class, 'getDeliveryAddress']);
        Route::post('save-delivery-address', [AddressController::class, 'saveDeliveryAddress']);
        Route::post('remove-delivery-address', [AddressController::class, 'removeDeliveryAddress']);

        // Paypal
        Route::get('paypal', [PaypalController::class, 'paypal']);
        Route::post('pay', [PaypalController::class, 'pay'])->name('payment');
        Route::get('success', [PaypalController::class, 'success']);
        Route::get('error', [PaypalController::class, 'error']);

        // Iyzico
        Route::get('iyzipay', [IyzipayController::class, 'iyzipay']);
        Route::get('iyzipay/pay', [IyzipayController::class, 'pay']);
    });
});
