<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoreTables extends Migration
{
    public function up()
    {
        Schema::create('user_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('group_name',150);
            $table->integer('group_level')->unique();
            $table->tinyInteger('group_status')->default(1);
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',60);
            $table->string('username',50)->unique();
            $table->string('password',255);
            $table->integer('user_level')->unsigned();
            $table->string('image',255)->default('no_image.jpg');
            $table->tinyInteger('status')->default(1);
            $table->dateTime('last_login')->nullable();
            // foreign key not strictly enforced to original group_level name; add if desired
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',60)->unique();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_name',255);
            $table->string('file_type',100);
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',255)->unique();
            $table->string('contact',50);
            $table->string('email',100)->nullable();
            $table->string('address',255)->nullable();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('quantity',50)->nullable();
            $table->decimal('buy_price',25,2)->nullable();
            $table->decimal('sale_price',25,2);
            $table->unsignedInteger('categorie_id');
            $table->integer('media_id')->default(0);
            $table->dateTime('date');
            $table->integer('supplier_id')->nullable();
            $table->foreign('categorie_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->integer('qty');
            $table->decimal('price',25,2);
            $table->date('date');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
        Schema::dropIfExists('products');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('media');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_groups');
    }
}
