<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Foreign Key with Cascade
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');

            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('admin_id');

            $table->string('admin_type');
            $table->string('product_name');
            $table->string('product_code')->unique();
            $table->string('product_color');

            $table->float('product_price', 8, 2);
            $table->float('product_discount', 8, 2)->default(0);
            $table->integer('product_weight')->nullable();

            $table->string('product_image')->nullable();
            $table->string('product_video')->nullable();
            $table->string('group_code')->nullable(); // for product colors

            $table->text('description')->nullable();

            // SEO fields
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->nullable();

            // New stock fields
            $table->integer('stock')->default(0);
            $table->enum('stock_status', ['In Stock', 'Out of Stock'])->default('In Stock');

            $table->enum('is_featured', ['No', 'Yes'])->default('No');
            $table->tinyInteger('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
