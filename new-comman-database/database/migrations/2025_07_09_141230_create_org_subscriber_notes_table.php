<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgSubscriberNotesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('org_subscriber_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscriber_id')
                ->nullable()
                ->constrained('org_subscribers')
                ->onDelete('cascade');
            $table->string('note_type')->nullable();
            $table->string('sub_note_type')->nullable();
            $table->string('subject')->nullable();
            $table->string('description')->nullable()->maxLength(999);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('org_subscriber_notes');
    }
}
