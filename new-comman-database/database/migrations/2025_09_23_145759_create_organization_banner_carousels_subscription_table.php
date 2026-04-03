<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationBannerCarouselsSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_banner_carousels_subscription', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organization_details')->onDelete('cascade');
            $table->string('poster_image')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->string('resource_type')->nullable();
            $table->string('select_platform')->nullable();
            $table->string('name')->nullable();
            $table->string('content_type')->nullable();
            $table->string('target_link')->nullable();
            $table->boolean('is_active')->nullable()->default(0)->comment("0 For InActive, 1 For Active");
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
        Schema::dropIfExists('organization_banner_carousels_subscription');
    }
}
