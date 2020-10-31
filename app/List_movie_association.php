<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class List_movie_association extends Model
{
    protected $fillable = ['list_id', 'movie_id'];
    
}
