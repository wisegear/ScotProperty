<?php

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
        // This is the guide title and whether it will be added to the navigation
        Schema::create('article_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->boolean('navigation')->default(false);
            $table->tinyInteger('order')->default(1);
            $table->timestamps();
        });

        // This is the article that will be paired with a guide
        Schema::create('article', function (Blueprint $table) {
            $table->id('id');
            $table->text('original_image')->nullable();
            $table->text('small_image')->nullable();
            $table->text('medium_image')->nullable();
            $table->text('large_image')->nullable();
            $table->date('date')->nullable();
            $table->string('title', 150);
            $table->string('slug', 200);
            $table->text('summary');
            $table->boolean('published')->default(true);
            $table->text('text');
            $table->json('images')->nullable();
            $table->tinyInteger('order')->default(1);
            $table->Biginteger('user_id')->unsigned();
            $table->Biginteger('articles_id')->unsigned();     
            $table->timestamps(); 

        // Create foreign Keys

        $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->ondelete('cascade');

        $table->foreign('articles_id')
            ->references('id')
            ->on('article_categories')
            ->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_categories');
        Schema::dropIfExists('articles');
    }
};
