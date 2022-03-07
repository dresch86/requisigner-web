<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaceholdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('placeholders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('version_id');
            $table->string('pdf_name');
            $table->string('friendly_name');
            $table->unsignedMediumInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::table('placeholders', function (Blueprint $table) {
            $table->foreign('version_id')->references('id')->on('versions')->cascadeOnDelete();
        });

        Schema::table('signees', function (Blueprint $table) {
            $table->foreign('placeholder_id')->references('id')->on('placeholders')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('placeholders');
    }
}
