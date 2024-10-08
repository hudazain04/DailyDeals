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
        Schema::create('category_requests', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('admin_name')->nullable();
            $table->string('parent_category')->nullable();
            $table->enum('status',[RequestType::Accepted,RequestType::Rejected,RequestType::Pending]);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category__requests');
    }
};
