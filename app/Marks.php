<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marks extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'subject_id', 'batch', 'sem', 'assessment_id', 'student_id', 'marks', 'stream'
    ];
}