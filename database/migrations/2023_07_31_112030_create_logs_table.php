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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            // Which table are we tracking
            // Which record from the table are we referencing
            $table->foreignIdFor(\App\Models\Item::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            // Who made the action
            $table->foreignIdFor(\App\Models\User::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            // What did they do
            $table->string('body');
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
        Schema::dropIfExists('logs');
    }
};
