<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->decimal('value')->nullable();
            $table->string('type')->nullable();
            $table->date('date')->nullable();
            $table->string('priority_level')->nullable();
            $table->string('max_period')->nullable();
            $table->string('description')->nullable();
            $table->string('comment')->nullable();

            $table->foreignIdFor(\App\Models\User::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(\App\Models\Bank::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(\App\Models\Company::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(\App\Models\Project::class)->nullable()->constrained()->nullOnDelete();

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
        Schema::dropIfExists('items');
    }
};
