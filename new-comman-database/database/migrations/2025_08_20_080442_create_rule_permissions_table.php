<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRulePermissionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('rule_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rule_id');
            $table->string('permission_module_name');
            $table->string('view')->nullable()->comment("0 = false, 1 = true");
            $table->string('create')->nullable()->comment("0 = false, 1 = true");
            $table->string('edit')->nullable()->comment("0 = false, 1 = true");
            $table->string('delete')->nullable()->comment("0 = false, 1 = true");
            $table->string('hide')->nullable()->comment("0 = false, 1 = true");
            $table->string('cash_payment')->nullable()->comment("0 = false, 1 = true");
            $table->string('refund_payment')->nullable()->comment("0 = false, 1 = true");
            $table->string('length_adjustment')->nullable()->comment("0 = false, 1 = true");
            $table->string('security_search')->nullable()->comment("0 = false, 1 = true");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('rule_permissions');
    }
}
