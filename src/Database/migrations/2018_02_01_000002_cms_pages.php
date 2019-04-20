<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CMSPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable()->unsigned();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('page')->onDelete('cascade');
        });

        Schema::create('page_group', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('page_element', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id')->unsigned();
            $table->integer('group_id')->unsigned()->nullable();
            $table->string('locale',2)->default('en');
            $table->integer('sort_no')->default(0);
            $table->string('name',190);
            $table->string('type',20)->default('text');
            $table->longText('content')->nullable();
            $table->string('rules',100)->nullable();
            $table->boolean('editor')->default(0);
            $table->boolean('dropify')->default(0);
            $table->string('label',190)->nullable();
            $table->string('placeholder',190)->nullable();
            $table->string('note',190)->nullable();
            $table->timestamps();

            $table->foreign('page_id')->references('id')->on('page')->onDelete('cascade');
            $table->foreign('locale')->references('iso')->on('language')->onDelete('cascade');

            $table->unique(['locale', 'name', 'page_id']);
        });

        Schema::create('page_list', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id')->unsigned();
            $table->string('name');
            $table->string('slug');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('page_id')->references('id')->on('page')->onDelete('cascade');
            $table->unique(['slug', 'page_id']);
        });

        Schema::create('page_list_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_list_id')->unsigned();
            $table->integer('sort_no');
            $table->timestamps();

            $table->foreign('page_list_id')->references('id')->on('page_list')->onDelete('cascade');
        });

        Schema::create('page_list_element', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_list_detail_id')->unsigned();
            $table->string('locale',2)->default('en');
            $table->integer('sort_no')->default(0);
            $table->string('name',190);
            $table->string('type',20)->default('text');
            $table->longText('content')->nullable();
            $table->string('rules',100)->nullable();
            $table->boolean('editor')->default(0);
            $table->boolean('dropify')->default(0);
            $table->string('label',190)->nullable();
            $table->string('placeholder',190)->nullable();
            $table->string('note',190)->nullable();
            $table->timestamps();

            $table->foreign('page_list_detail_id')->references('id')->on('page_list_detail')->onDelete('cascade');
            $table->unique(['locale','name', 'page_list_detail_id']);
        });


        Schema::create('page_list_preset', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_list_id')->unsigned();
            $table->string('name',190);
            $table->string('type',20)->default('text');
            $table->string('rules',100)->nullable();
            $table->boolean('editor')->default(0);
            $table->boolean('dropify')->default(0);
            $table->string('label',190)->nullable();
            $table->string('placeholder',190)->nullable();
            $table->string('note',190)->nullable();
            $table->integer('sort_no')->default(0);

            $table->timestamps();
            $table->foreign('page_list_id')->references('id')->on('page_list')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_list_preset');
        Schema::dropIfExists('page_list_element');
        Schema::dropIfExists('page_list_detail');
        Schema::dropIfExists('page_list');
        Schema::dropIfExists('page_element');
        Schema::dropIfExists('page_group');
        Schema::dropIfExists('page');
    }
}
