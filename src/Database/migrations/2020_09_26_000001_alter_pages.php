<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

class AlterPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::beginTransaction();
        Schema::table('page_element', function (Blueprint $table) {
            $table->string('rules_store',100)->nullable();
            $table->string('rules_update',100)->nullable();
        });

        Schema::table('page_list_element', function (Blueprint $table) {
            $table->string('rules_store',100)->nullable();
            $table->string('rules_update',100)->nullable();
        });

        Schema::table('page_list_preset', function (Blueprint $table) {
            $table->string('format',100)->nullable(); //null, number, decimal, percent, link
            $table->string('rules_store',100)->nullable();
            $table->string('rules_update',100)->nullable();
            $table->boolean('is_table')->default(false);
        });
        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('page_element', function (Blueprint $table) {
            $table->dropColumn('rules_store');
            $table->dropColumn('rules_update');
        });

        Schema::table('page_list_element', function (Blueprint $table) {
            $table->dropColumn('rules_store');
            $table->dropColumn('rules_update');
        });

        Schema::table('page_list_preset', function (Blueprint $table) {
            $table->dropColumn('rules_store');
            $table->dropColumn('rules_update');
            $table->dropColumn('is_table');
        });

    }
}
