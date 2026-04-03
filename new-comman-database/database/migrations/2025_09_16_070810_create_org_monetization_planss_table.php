<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgMonetizationPlanssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('org_monetization_planss', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->string('subscription_name');
            $table->string('subscription_identifier')->unique();
            $table->string('platforms')->nullable();
            $table->boolean('use_org_settings')->default(0)->comment('0 = false, 1 = true');
            $table->string('subscription_length')->nullable();
            $table->string('subs_length_time_type')->nullable()->comment('day, month');
            $table->string('subscription_type');
            $table->string('advertising')->comment("0 = false, 1 = true")->default(0);
            $table->string('currency');
            $table->string('subscription_price');
            $table->float('total_price')->nullable();
            $table->json('additional_device_price')->nullable();
            $table->string('autopay')->comment("0 = false, 1 = true")->default(0);
            $table->string('subscription_devices');
            $table->json('conditional_subscriptions')->nullable();
            $table->json('conditional_content_addons')->nullable();
            $table->json('conditional_accessories')->nullable();
            $table->unsignedBigInteger('org_monetzn_content_set_id')->nullable();
            $table->json('org_monetzn_accessories_id')->nullable();
            $table->json('org_monetzn_partner_product_id')->nullable();
            $table->json('org_monetzn_extra_partner_product_id')->nullable();
            $table->string('created_by');
            $table->boolean('auto_scrolling')->nullable()->default(0)->comment('0 For Not Auto Scrolling, 1 For Auto Scrolling');
            $table->bigInteger('second')->nullable();
            $table->json('banners')->nullable();
            $table->boolean('banner_carousel_is_active')->nullable()->default(0)->comment('0 For Not Active, 1 For Active');
            $table->string('status')->comment('1 => enable, 0 => disable, enable or disable status to show in shopping cart');
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
        Schema::dropIfExists('org_monetization_planss');
    }
}
