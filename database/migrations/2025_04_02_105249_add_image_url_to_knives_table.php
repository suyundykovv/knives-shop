<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('knives', function (Blueprint $table) {
            $table->string('image_url')->nullable();  // Добавляем поле для URL изображения
        });
    }

    public function down()
    {
        Schema::table('knives', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });
    }

};
