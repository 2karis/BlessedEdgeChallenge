<?php

namespace src\Models;

use Illuminate\Database\Eloquent\Model;

class grade extends Model
{	
	protected $table = "grade";
    
    protected $fillable = [
    	'course',
    	'grade'
    ];

    public function hi(){
    	echo "hi";
    }
}