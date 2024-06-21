<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewReleasesTable extends Migration
{
    public function up()
    {
        Schema::create('new_releases', function (Blueprint $table) {
            $table->id();
            $table->string('spotify_id')->unique();
            $table->string('title');
            $table->unsignedBigInteger('artist_id');
            $table->string('genre')->nullable();
            $table->string('label')->nullable();
            $table->date('release_date')->nullable();
            $table->timestamps();

            $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('new_releases');
    }
}
