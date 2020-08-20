<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_authors', function (Blueprint $table) {
            $table->engine    = 'InnoDB';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->unsignedBigInteger('id', true);
            $table->unsignedBigInteger('book_id')->index();
            $table->unsignedBigInteger('author_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booK_authors');
    }
}
