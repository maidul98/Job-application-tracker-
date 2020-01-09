<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = ['user_id', 'position', 'company', 'location', 'description'];

     /**
     * A user has job application 
     */
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function stages(){
        return $this->hasMany('App\Stages');
    }
}
