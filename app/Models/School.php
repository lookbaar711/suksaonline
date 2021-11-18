<?php


namespace App\Models;


use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;


class School extends Eloquent
{
	protected $connection = 'mongodb';
    protected $collection = 'school';

    use SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_name_th',
        'school_name_en',
        'school_email',
        'school_tell',
        'school_teacher',
        'school_student',
        'school_status',
        'school_image',
    ];
}
