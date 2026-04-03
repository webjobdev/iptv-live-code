<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnerProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_programs', function (Blueprint $table) {
            $table->id();
            $table->string('program_name');
            $table->string('partner_provider');
            $table->string('partner_code');
            $table->string('partner_app_logo');
            $table->string('api_key');
            $table->string('partner_api_link');
            $table->string('description');
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('partner_programs');
    }
}
