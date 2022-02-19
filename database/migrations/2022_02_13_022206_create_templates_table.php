<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('filename');
            $table->unsignedBigInteger('head_version')->nullable();
            $table->unsignedBigInteger('owner')->nullable();
            $table->unsignedTinyInteger('group_read')->default(0);
            $table->unsignedTinyInteger('group_edit')->default(0);
            $table->unsignedTinyInteger('child_read')->default(0);
            $table->unsignedTinyInteger('child_edit')->default(0);
            $table->mediumText('description');
            $table->json('metatags');
            $table->softDeletes();
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
        Schema::dropIfExists('templates');
    }
}
