<?php
namespace App\Models;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class CourseTest extends Eloquent
{
	protected $connection = 'mongodb';
	protected $collection = 'course_test';
    // protected $dates = [
    //     'course_date'
    // ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id',
        'member_id',
        'pretest_questions',
        'posttest_questions',
        'pretest_score',
        'posttest_score',
    ];
}