<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flash_infos', function (Blueprint $table) {
            $table->id();
            $table->string('message', 500);
            $table->string('link_text')->nullable();
            $table->string('link_url', 500)->nullable();
            $table->string('type')->default('info'); // info | important | urgent
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_infos');
    }
};
