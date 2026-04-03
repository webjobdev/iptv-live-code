<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationChannelListingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_channel_listing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organization_details')->onDelete('cascade');
            $table->foreignId('monitization_plan_id')->nullable()->constrained('org_monetization_planss')->onDelete('cascade');
            $table->string('channel_listing')->nullable();
            $table->json('sequence_assigned_channels')->nullable();
            // $table->bigInteger('form')->nullable();
            // $table->bigInteger('to')->nullable();
            // $table->string('description')->nullable();
            $table->json('group_channel_list')->nullable();
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
        Schema::dropIfExists('organization_channel_listing');
    }
}
