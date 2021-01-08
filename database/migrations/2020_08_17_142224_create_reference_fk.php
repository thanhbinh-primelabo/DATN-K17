<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferenceFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function ($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('news', function ($table) {
            $table->foreign('user_id_create')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('comments', function ($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('orders', function ($table) {
            // $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customer_id')->references('user_id')->on('members')->onDelete('cascade');
        });

        Schema::table('products', function ($table) {
            $table->foreign('type_product_id')->references('id')->on('product_types')->onDelete('cascade');
        });

        Schema::table('order_details', function ($table) {
            $table->foreign('bill_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('districts', function ($table) {
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
        });

        Schema::table('villages', function ($table) {
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reference_fk');
    }
}
