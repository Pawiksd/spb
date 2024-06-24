<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueIndexToNewReleasesSpotifyId extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('new_releases')) {
            Schema::table('new_releases', function (Blueprint $table) {
                $table->unique('spotify_id');
            });
        }
    }

    public function down()
    {
        Schema::table('new_releases', function (Blueprint $table) {
            $table->dropUnique(['spotify_id']);
        });
    }
}
