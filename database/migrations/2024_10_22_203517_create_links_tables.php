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
        // Category Table
        Schema::create('links_categories', function(Blueprint $table)
        {
            $table->id('id');
            $table->string('name');

        });

        //Category Links
        Schema::create('links', function(Blueprint $table)
        {

            $table->id('id');
            $table->string('title');
            $table->string('slug');
            $table->text('image')->nullable();
            $table->text('description');
            $table->string('url');
            $table->bigInteger('category_id')->unsigned();
            $table->boolean('published')->default(true);
            $table->timestamps();

            // Create Foreign Key
            $table->foreign('category_id')
            ->references('id')
            ->on('links_categories')
            ->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links_categories');
        Schema::dropIfExists('links');
    }
};
