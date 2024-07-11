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
            $table->enum('status',[RequestType::Pending,RequestType::Accepted,RequestType::Rejected,RequestType::Shown]);
//            $table->boolean('approved')->default(false);
            $table->integer('period');
            $table->integer('price');
//            $table->boolean('shown')->default(false);
            $table->string('invoice');
            $table->string('phone_number');
            $table->string('image');
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
