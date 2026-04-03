<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgAnnouncementRemindersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('org_announcement_reminders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->string('subject');
            $table->string('message');
            $table->string('day_before');
            $table->string('reminder_to');
            $table->unsignedBigInteger('created_by');
            $table->string('status')->comment("0 = false, 1 = true")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('org_announcement_reminders');
    }
}
