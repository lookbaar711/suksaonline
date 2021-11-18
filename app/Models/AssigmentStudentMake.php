<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use App\Models\AssignmentQuestions;
class AssigmentStudentMake extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'assigment_student_make';

    protected $fillable = [
        'assignment_id',
        'assignment_questions_id',
        'assigment_student_id',
        'questions_type',
        'questions_make',
        'teacher_check',
        'score',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',

    ];

    public function getAssignmentQuestion()
    {
        return $this->belongsTo(AssignmentQuestions::class,'assignment_questions_id')->select('questions');
    }

}
