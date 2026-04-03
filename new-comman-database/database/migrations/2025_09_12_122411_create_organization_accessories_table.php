<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationAccessoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_accessories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organization_id')->nullable();
            $table->string('accessories')->nullable();
            $table->string('accessories_type')->nullable();
            $table->string('identifier')->nullable();
            $table->boolean('identifier_auto')->nullable()->comment("0 For In Active, 1 For Active");
            $table->string('description')->nullable();
            $table->string('currency')->nullable();
            $table->float('price')->nullable();
            $table->bigInteger('by_user')->nullable();
            $table->boolean('is_active')->nullable()->comment("0 For In Active, 1 For Active");
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
        Schema::dropIfExists('organization_accessories');
    }
}
