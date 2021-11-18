<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use App\Models\Subject;
use App\Models\AssignmentStudent;
use App\Models\AssignmentQuestions;
use App\Models\AssigmentStudentMake;

class Assignment extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'assignment';

    protected $fillable = [
        'subject_id',
        'aptitude_id',
        'assignment_name',
        'assignment_detail',
        'assignment_date_start',
        'assignment_time_start',
        'assignment_date_end',
        'assignment_time_end',
        'assignment_status',
        'status_noti',
        'assignment_teacher',
        'teacher_id',
        'student_classroom',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',

    ];

    public function getSubject()
    {
        return $this->belongsTo(Subject::class,'subject_id');
    }

    public function getStudens()
    {
        return $this->hasMany(AssignmentStudent::class,'assignment_id','_id');
    }
    public function getQuestions()
    {
        return $this->hasMany(AssignmentQuestions::class,'assignment_id','_id');
    }
    public function getStudentMake()
    {
        return $this->hasMany(AssigmentStudentMake::class,'assignment_id','_id')->whereNotNull('score');
    }


}
