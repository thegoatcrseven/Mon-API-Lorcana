<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('set_id')->constrained()->onDelete('cascade');
            $table->integer('card_id')->unique(); // The unique ID from LorcanaJSON
            $table->string('name');
            $table->string('full_name');
            $table->string('type'); // Character, Action, Item, Location
            $table->string('color'); // Amber, Amethyst, Emerald, Ruby, Sapphire, Steel
            $table->string('rarity');
            $table->integer('cost');
            $table->integer('strength')->nullable();
            $table->integer('willpower')->nullable();
            $table->integer('lore')->nullable();
            $table->boolean('inkwell')->default(false);
            $table->json('abilities')->nullable();
            $table->text('flavor_text')->nullable();
            $table->text('full_text')->nullable();
            $table->string('story');
            $table->string('image_url')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->json('subtypes')->nullable();
            $table->json('artists');
            $table->string('version')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cards');
    }
};
