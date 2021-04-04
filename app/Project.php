<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable=[
        'slug','user_id','image','thumbnail'
    ];
    
}
