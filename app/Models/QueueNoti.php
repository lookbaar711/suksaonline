<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
//use Tymon\JWTAuth\Contracts\JWTSubject;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContracts;
use Illuminate\Notifications\Notifiable;

class QueueNoti extends Eloquent implements  AuthenticatableContracts
{
    use Notifiable;
    use Authenticatable;
	protected $connection = 'mongodb';
	protected $collection = 'queue_noti';
    protected $guard = 'users';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_id',
        'course_name',
        'classroom_date',
        'classroom_name',
        'classroom_time_start',
        'classroom_time_end',
        'member_id',
        'member_fullname',
        'teacher_id',
        'teacher_fullname',
        'student_id',
        'student_fullname',
        'sum_coins',
        'noti_type',
        'classroom_url',
        'coins_description',
        'course_price',
        'coins',
        'topup_date',
        'topup_time',
        'request_topic',
        'request_group_name_th',
        'request_group_name_en',
        'request_subject_name_th',
        'request_subject_name_en',
        'request_date',
        'request_time',
        'get_date',
        'get_time',
        'refund_description',
        'queue_status',
        'hostname',
        'classroom_category',
        'member_email',
        'noti_id',
        'assignment_id',
        'assignment_name',
        'assignment_date_start',
        'assignment_date_end',
    ];



}
