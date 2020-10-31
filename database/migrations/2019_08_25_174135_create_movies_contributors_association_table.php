<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesContributorsAssociationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies_contributors_association', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('movie_id');
            $table->foreign('movie_id')->references('id')->on('movies');
            $table->unsignedBigInteger('contributor_id');
            $table->foreign('contributor_id')->references('id')->on('contributors');
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('contributor_roles');
            $table->unique(['movie_id', 'contributor_id', 'role_id'], 'movie_id_contributor_id_role_id_unique');
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
        Schema::dropIfExists('movies_contributors_association');
    }
}
