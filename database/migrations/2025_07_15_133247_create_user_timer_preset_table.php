<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_timer_presets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name')->default('Default Timer Preset');
            $table->integer('work_duration')->default(25);
            $table->integer('break_duration')->default(5);
            $table->integer('long_break_duration')->default(15);
            $table->integer('long_break_interval')->default(4);
            $table->boolean('notifications')->default(true);
            $table->boolean('auto_play')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_timer_presets');
    }
};
