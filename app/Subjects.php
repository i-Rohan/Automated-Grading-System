<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{
    protected $table = 'subjects';
    protected $fillable = [
        'id', 'subject_name', 'teacher_id', 'discipline', 'stream', 'sem', 'batch','credits'
    ];
}
