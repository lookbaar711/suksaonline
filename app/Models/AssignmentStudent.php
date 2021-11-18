<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use App\Models\Assignment;
use App\Models\AssignmentQuestions;
use App\Models\AssigmentStudentMake;
use App\Models\Member;

class AssignmentStudent extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'assignment_student';

    protected $fillable = [
        'student_id',
        'assignment_id',
        'student_email',
        'student_fname',
        'student_lname',
        'student_class',
        'student_classroom',
        'student_room',
        'student_tel',
        'send_assignment_status',
        'send_assignment_date',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',

    ];

    public function getAssignment()
    {
        return $this->belongsTo(Assignment::class,'assignment_id');
    }

    public function getStudent()
    {
        return $this->belongsTo(Member::class,'student_id');
    }

    public function getQuestionsType()
    {
        return $this->hasMany(AssignmentQuestions::class,'assignment_id','assignment_id')->orderBy('questions_type','asc');
    }

    public function getStudentMake()
    {

    }



}
