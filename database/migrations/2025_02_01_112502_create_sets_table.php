<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // The set code from LorcanaJSON
            $table->string('name');
            $table->string('type'); // expansion, quest, etc.
            $table->date('release_date')->nullable();
            $table->date('prerelease_date')->nullable();
            $table->boolean('has_all_cards')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sets');
    }
};
