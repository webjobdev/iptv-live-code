<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationGeneralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_general', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->boolean('live')->nullable()->default(0)->comment("0 For Inactive, 1 For Active");
            $table->boolean('epg')->nullable()->default(0)->comment("0 For Inactive, 1 For Active");
            $table->boolean('catchup')->nullable()->default(0)->comment("0 For Inactive, 1 For Active");
            $table->boolean('movie')->nullable()->default(0)->comment("0 For Inactive, 1 For Active");
            $table->boolean('sereis')->nullable()->default(0)->comment("0 For Inactive, 1 For Active");
            $table->boolean('event')->nullable()->default(0)->comment("0 For Inactive, 1 For Active");
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
        Schema::dropIfExists('organization_general_tabel');
    }
}
