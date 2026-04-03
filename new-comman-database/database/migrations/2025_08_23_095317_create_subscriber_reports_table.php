<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriberReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriber_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_name')->nullable();
            $table->string('report_type')->nullable();
            $table->foreignId('organization')->nullable()->constrained('organization_details')->onDelete('cascade');
            $table->string('report_fields')->nullable();
            $table->string('report_filter')->nullable();
            $table->boolean('generate')->default(0)->comment("0 For Not Generated, 1 For Generated");
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
        Schema::dropIfExists('subscriber_reports');
    }
}
