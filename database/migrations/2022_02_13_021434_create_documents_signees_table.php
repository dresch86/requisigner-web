<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsSigneesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents_signees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('signee_id');
            $table->unsignedMediumInteger('order')->default(0);
            $table->unsignedTinyInteger('signed')->default(0);
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
        Schema::dropIfExists('documents_signees');
    }
}
