<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgAppcustRowOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_appcust_row_order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organization_details')->onDelete('cascade');
            $table->bigInteger('row_order')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->json('assigne_row')->nullable();
            $table->string('poster_type')->nullable();
            $table->string('poster_size')->nullable();
            $table->string('horizontal_image')->nullable();
            $table->string('vertical_image')->nullable();
            $table->string('gradient')->nullable();
            $table->string('no_set')->nullable()->default('not_set');
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
        Schema::dropIfExists('org_appcust_row_order');
    }
}
