<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('spotify_id')->unique();
            $table->string('name');
            $table->string('artist');
            $table->date('release_date');
            $table->integer('total_tracks');
            $table->string('image_url');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('albums');
    }

};
