<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('version_id')->nullable();
            $table->unsignedBigInteger('requestor')->nullable();
            $table->string('title');
            $table->json('metatags');
            $table->dateTimeTz('complete_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Save space by using binary type for checksum
        DB::statement('ALTER TABLE `documents` ADD `checksum` binary(20) AFTER `requestor`');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
