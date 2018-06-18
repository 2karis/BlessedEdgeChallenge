<?php

namespace src\Models;

use Illuminate\Database\Eloquent\Model;

class student extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'FirstName',
    	'LastName',
    	'Major'
    ];
}