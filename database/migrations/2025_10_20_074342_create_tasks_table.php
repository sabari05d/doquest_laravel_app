<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('task_date');
            $table->time('task_time');
            $table->dateTime('reminder_datetime')->nullable();
            $table->integer('reminded')->default(0)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 = pending, 1 = finished');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
