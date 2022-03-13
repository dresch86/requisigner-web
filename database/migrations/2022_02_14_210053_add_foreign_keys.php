<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('group_id')->references('id')->on('groups')->nullOnDelete();
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('groups')->nullOnDelete();
            $table->foreign('manager_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->foreign('requestor')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('signees', function (Blueprint $table) {
            $table->foreign('document_id')->references('id')->on('documents')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::table('templates', function (Blueprint $table) {
            $table->foreign('owner_user')->references('id')->on('users')->nullOnDelete();
            $table->foreign('owner_group')->references('id')->on('groups')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
