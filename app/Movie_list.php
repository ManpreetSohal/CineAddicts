<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie_list extends Model
{
    protected $fillable = ['title', 'username', 'list_type_id'];
}
