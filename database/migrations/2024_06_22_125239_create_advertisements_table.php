<?php

use App\Types\RequestType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status',[RequestType::Pending,RequestType::Accepted,RequestType::Rejected,RequestType::Reactivate]);
            $table->integer('period');
            $table->integer('price')->nullable();
            $table->boolean('shown')->default(false);
            $table->string('phone_number');
            $table->string('image');
            $table->datetime('accepted_at')->nullable();
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
