<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use app\JobApplication;

class Stages extends Model
{
    protected  $fillable = ['job_application_id', 'stage_name', 'passed'];

    public function message(){
        return $this->belongsTo(JobApplication::class);
    }
}
