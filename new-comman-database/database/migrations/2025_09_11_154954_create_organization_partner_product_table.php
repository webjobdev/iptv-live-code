<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationPartnerProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_partner_product', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organization_id')->nullable();
            $table->string('partner_program')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_image')->nullable();
            $table->string('product_description')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->bigInteger('by_user')->nullable();
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
        Schema::dropIfExists('organization_partner_product');
    }
}
