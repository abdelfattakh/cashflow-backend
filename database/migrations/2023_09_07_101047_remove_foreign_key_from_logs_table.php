<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logs', function (Blueprint $table) {

            // First, drop the foreign keys associated with 'item_id' and 'user_id'
            $table->dropForeign(['item_id']);
            $table->dropForeign(['user_id']);

            // Then, drop the columns 'item_id' and 'user_id'
            $table->dropColumn('item_id');
            $table->dropColumn('user_id');
            // What did they do
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logs', function (Blueprint $table) {
            //
        });
    }
};
