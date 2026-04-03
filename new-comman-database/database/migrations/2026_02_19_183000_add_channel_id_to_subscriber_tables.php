<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChannelIdToSubscriberTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriber_my_lists', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriber_my_lists', 'channel_id')) {
                $table->unsignedBigInteger('channel_id')->nullable()->after('series_id');
            }
        });

        Schema::table('subscriber_like', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriber_like', 'channel_id')) {
                $table->unsignedBigInteger('channel_id')->nullable()->after('series_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriber_my_lists', function (Blueprint $table) {
            if (Schema::hasColumn('subscriber_my_lists', 'channel_id')) {
                $table->dropColumn('channel_id');
            }
        });

        Schema::table('subscriber_like', function (Blueprint $table) {
            if (Schema::hasColumn('subscriber_like', 'channel_id')) {
                $table->dropColumn('channel_id');
            }
        });
    }
}
