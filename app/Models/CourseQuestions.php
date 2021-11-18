<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class CourseQuestions extends Eloquent
{
    protected $connection = 'mongodb';
	protected $collection = 'course_questions';
    // protected $dates = [
    //     'classroom_date',
    // ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'course_id',
        'created_at',
        'questions',
        'updated_at',
    ];
}
