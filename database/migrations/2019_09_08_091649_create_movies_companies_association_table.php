<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesCompaniesAssociationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies_companies_association', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('movie_id');                                      
            $table->foreign('movie_id')->references('id')->on('movies');
            $table->unsignedBigInteger('company_id');                               
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('company_roles');
            $table->unique(['movie_id', 'company_id', 'role_id'], 'movie_id_company_id_role_id_unique');
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
        Schema::dropIfExists('movies_companies_association');
    }
}
