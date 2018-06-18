<?php

namespace src\models;

use Illuminate\Database\Eloquent\Model;

class grade extends Model
{
    
    protected $fillable = [
    	'course',
    	'grade'
    ];
}