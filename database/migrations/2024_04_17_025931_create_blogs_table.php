<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('blogs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')
        ->references('id')
        ->on('users')
        ->onDelete('cascade');
      $table->foreignId('category_id')
        ->references('id')
        ->on('categories')
        ->onDelete('cascade');
      $table->string('title');
      $table->text('body');
      $table->binary("image");
      $table->timestamps();
    });
    DB::statement(
      "ALTER TABLE blogs MODIFY image LONGBLOB;"
    );
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('blogs');
  }
};
