<?php
namespace App\Models;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

use App\Models\Member;
use App\Models\Aptitude;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\CourseTest;
use App\Models\CourseQuestions;
use App\Models\School;

class Course extends Eloquent
{
	protected $connection = 'mongodb';
	protected $collection = 'course';
    // protected $dates = [
    //     'course_date'
    // ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'member_fname',
        'member_lname',
        'member_email',
        'course_name',
        'course_detail',
        'course_group',
        'course_subject',
        'course_date',
        'course_time_start',
        'course_time_end',
        'course_type',
        'course_price',
        'course_img',
        'course_file',
        'course_student_limit',
        'course_status',
        'course_category',
        'course_student',
        'course_date_start',
        'course_time_start',
        'course_time_end',
        'last_course_date_time',
        'school_id',
    ];

    public function getMember()
    {
        return $this->belongsTo(Member::class,'member_id','_id');
    }

    public function getaAtitude()
    {
        return $this->belongsTo(Aptitude::class,'course_group','_id')->select('aptitude_name_th','aptitude_name_en');
    }

    public function getSubject()
    {
        return $this->belongsTo(Subject::class,'course_subject','_id')->select('subject_name_th','subject_name_en');
    }

    public function getClassroom()
    {
        return $this->belongsTo(Classroom::class,'_id','course_id');
    }

    public function getCourseTest()
    {
        return $this->hasMany(CourseTest::class,'course_id','_id');
    }

    public function getSchool()
    {
        return $this->belongsTo(School::class,'school_id','_id');
    }

    public function getcoursequestions()
    {
        return $this->belongsTo(CourseQuestions::class,'_id','course_id');
    }
}
