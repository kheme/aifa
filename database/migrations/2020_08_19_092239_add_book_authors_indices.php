<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookAuthorsIndices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book_authors', function (Blueprint $table) {
            $table->foreign('book_id', 'book_authors_fkc_1')->references('id')->on('books')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('author_id', 'book_authors_fkc_2')->references('id')->on('authors')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_authors', function (Blueprint $table) {
            $table->engine    = 'InnoDB';
            $table->collation = 'utf8mb4_unicode_ci';
            
            $table->dropForeign('book_authors_fkc_1');
            $table->dropForeign('book_authors_fkc_2');
        });
    }
}
