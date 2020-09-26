<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('page_element', function (Blueprint $table) {
            $table->string('rules_store',100)->nullable();
            $table->string('rules_update',100)->nullable();
        });

        Schema::table('page_list_element', function (Blueprint $table) {
            $table->string('rules_store',100)->nullable();
            $table->string('rules_update',100)->nullable();
        });

        Schema::table('page_list_preset', function (Blueprint $table) {
            $table->boolean('is_table')->default(false);
        });

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
            $table->dropColumn('is_table');
        });

    }
}
