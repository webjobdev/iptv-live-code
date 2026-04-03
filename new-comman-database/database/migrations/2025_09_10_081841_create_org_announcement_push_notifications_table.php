<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgAnnouncementPushNotificationsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('org_announcement_push_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->string('name');
            $table->string('title');
            $table->string('description');
            $table->unsignedBigInteger('org_subscription_id');
            $table->string('subscriber_status')->comment("0 = inactive, 1 = active");
            $table->json('platform');
            $table->string('resource_type');
            $table->string('publish');
            $table->unsignedBigInteger('created_by');
            $table->string('status')->comment("0 = send-out, 1 = pending, 2 = deleted, 3 = failed")->default(1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('org_announcement_push_notifications');
    }
}
