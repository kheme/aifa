<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->engine    = 'InnoDB';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->unsignedBigInteger('id', true);
            $table->string('name')->unique();
            $table->string('isbn')->unique();
            $table->string('country');
            $table->integer('number_of_pages');
            $table->string('publisher');
            $table->date('release_date');
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
        Schema::dropIfExists('books');
    }
}
