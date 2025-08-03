<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixFavoritesForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropForeign('favorites_micropost_id_foreign');

            $table->foreign('micropost_id')
                ->references('id')
                ->on('microposts')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropForeign(['micropost_id']);

            $table->foreign('micropost_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
}
