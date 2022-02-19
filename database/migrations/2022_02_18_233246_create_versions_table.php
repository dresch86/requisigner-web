<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->string('semver')->default('v1.0.0');
            $table->unsignedBigInteger('contributor')->nullable();
            $table->unsignedTinyInteger('is_head')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        // Save space by using binary type for checksum
        DB::statement('ALTER TABLE `versions` ADD `checksum` binary(20) AFTER `semver`');

        Schema::table('versions', function (Blueprint $table) {
            $table->foreign('template_id')->references('id')->on('templates')->cascadeOnDelete();
            $table->foreign('contributor')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('versions');
    }
}
