<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assessments extends Model
{
    protected $table = 'weightage';
    protected $fillable = [
        'id', 'subject_id', 'assessment_name', 'weightage', 'max_marks'
    ];
}
