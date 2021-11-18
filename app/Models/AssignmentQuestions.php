<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use App\Models\AssigmentStudentMake;
use App\Models\Assignment;
class AssignmentQuestions extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'assignment_questions';

    protected $fillable = [
        'assignment_id',
        'questions',
        'questions_studen',
        'questions_type',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',

    ];

    public function getStudentMake()
    {
        return $this->belongsTo(AssigmentStudentMake::class,'id','assignment_questions_id');
    }

    public function getStudentMakemember()
    {
        return $this->belongsTo(AssigmentStudentMake::class,'id','assignment_questions_id')->where('assigment_student_id',\Auth::guard('members')->user()->_id);
    }
    public function getAssignment()
    {
        return $this->belongsTo(Assignment::class,'assignment_id');
    }

}
