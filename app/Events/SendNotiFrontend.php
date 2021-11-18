<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\MemberNotification;
use App\Models\Aptitude;
use App\Models\Subject;

class SendNotiFrontend implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $member_id;
    public $member_fullname;
    public $classroom_name;
    public $classroom_date;
    public $classroom_datetime;
    public $classroom_time_start;
    public $classroom_time_end;
    public $classroom_url;
    public $teacher_fullname;
    public $noti_type;
    public $noti_status;
    public $created_at;

    public $count_member_noti;
    public $count_badge_member_noti;

    public $coins;
    public $sum_coins;
    public $topup_date;
    public $topup_time;
    public $withdraw_date;
    public $withdraw_time;
    public $get_date;
    public $get_time;

    public $student_fullname;

    public $course_name;
    public $course_datetime;
    public $course_start_date;
    public $course_id;
    public $course_price;
    public $noti_course_type;

    /////---- request -----------------------------------------
    public $request_id;
    public $request_name;
    public $request_date;
    public $request_time;
    public $request_member_id;
    public $request_full_name;
    public $request_member_email;

    public $student_id;
    public $request_topic;
    public $request_group_th;
    public $request_group_en;
    public $request_subject_th;
    public $request_subject_en;
    public $request_datetime;

    public $coins_description;
    public $refund_description;

    public $noti_id;

    public $assignment_id;
    public $assignment_name;
    public $assignment_date_start;
    public $assignment_date_end;
    public $assignment_url;

    /////---- request -----------------------------------------
    /**
     * Create a new event instance.
     *
     * @return void
     */

    public function __construct($member_id)
    {
        $member_noti = MemberNotification::where('member_id', $member_id)
                    ->where('noti_status', '0')
                    ->orderby('created_at','desc')
                    ->first();

        $count_badge_member_noti = MemberNotification::where('member_id', $member_id)
                    ->where('noti_status', '0')
                    ->orderby('created_at','desc')
                    ->count();

        $count_member_noti = MemberNotification::where('member_id', $member_id)
                    ->orderby('created_at','desc')
                    ->count();

        if(isset($member_noti)){

            if($member_noti->noti_type == 'open_course_teacher'){
                $this->noti_id = $member_noti->_id;
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->course_id = $member_noti->course_id;
                $this->classroom_name = $member_noti->classroom_name;
                //$this->classroom_date = changeDate($member_noti->classroom_date, 'full_date', $lang);
                $this->classroom_date = date('d/m/Y', strtotime($member_noti->classroom_date));
                $this->classroom_time_start = substr($member_noti->classroom_time_start,0,5);
                $this->classroom_time_end = substr($member_noti->classroom_time_end,0,5);
                $this->classroom_url = $member_noti->classroom_url;
                $this->teacher_fullname = $member_noti->teacher_fullname;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'open_course_student'){
                $this->noti_id = $member_noti->_id;
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->course_id = $member_noti->course_id;
                $this->classroom_name = $member_noti->classroom_name;
                //$this->classroom_date = changeDate($member_noti->classroom_date, 'full_date', $lang);
                $this->classroom_date = date('d/m/Y', strtotime($member_noti->classroom_date));
                $this->classroom_time_start = substr($member_noti->classroom_time_start,0,5);
                $this->classroom_time_end = substr($member_noti->classroom_time_end,0,5);
                $this->classroom_url = $member_noti->classroom_url;
                $this->teacher_fullname = $member_noti->teacher_fullname;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'approve_topup_coins'){
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->coins = $member_noti->coins;
                $this->sum_coins = $member_noti->sum_coins;
                $this->topup_date = $member_noti->topup_date;
                $this->topup_time = $member_noti->topup_time;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'not_approve_topup_coins'){
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->coins = $member_noti->coins;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->coins_description = $member_noti->coins_description;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'approve_withdraw_coins'){
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->coins = $member_noti->coins;
                $this->sum_coins = $member_noti->sum_coins;
                $this->withdraw_date = $member_noti->topup_date;
                $this->withdraw_time = $member_noti->topup_time;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'not_approve_withdraw_coins'){
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->coins = $member_noti->coins;
                $this->sum_coins = $member_noti->sum_coins;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->coins_description = $member_noti->coins_description;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'register_course_teacher'){
                if($member_noti->classroom_start_date != $member_noti->classroom_end_date){
                    $date_time = date('d/m/Y', strtotime($member_noti->classroom_start_date)).' - '.date('d/m/Y', strtotime($member_noti->classroom_end_date));
                }
                else{
                    $date_time = date('d/m/Y', strtotime($member_noti->classroom_start_date));
                }
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->course_id = $member_noti->course_id;
                $this->classroom_name = $member_noti->classroom_name;
                $this->classroom_datetime = $date_time;
                $this->classroom_time_start = substr($member_noti->classroom_time_start,0,5);
                $this->classroom_time_end = substr($member_noti->classroom_time_end,0,5);
                $this->student_fullname = $member_noti->student_fullname;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'register_course_private_teacher'){
                if($member_noti->classroom_start_date != $member_noti->classroom_end_date){
                    $date_time = date('d/m/Y', strtotime($member_noti->classroom_start_date)).' - '.date('d/m/Y', strtotime($member_noti->classroom_end_date));
                }
                else{
                    $date_time = date('d/m/Y', strtotime($member_noti->classroom_start_date));
                }
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->course_id = $member_noti->course_id;
                $this->classroom_name = $member_noti->classroom_name;
                //$this->classroom_date = changeDate($member_noti->classroom_date, 'full_date', $lang);
                $this->classroom_date = $date_time;
                $this->classroom_time_start = substr($member_noti->classroom_time_start,0,5);
                $this->classroom_time_end = substr($member_noti->classroom_time_end,0,5);
                $this->student_fullname = $member_noti->student_fullname;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'register_course_student'){
                if($member_noti->classroom_start_date != $member_noti->classroom_end_date){
                    $date_time = date('d/m/Y', strtotime($member_noti->classroom_start_date)).' - '.date('d/m/Y', strtotime($member_noti->classroom_end_date));
                }
                else{
                    $date_time = date('d/m/Y', strtotime($member_noti->classroom_start_date));
                }
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->course_id = $member_noti->course_id;
                $this->classroom_name = $member_noti->classroom_name;
                $this->classroom_datetime = $date_time;
                $this->classroom_time_start = substr($member_noti->classroom_time_start,0,5);
                $this->classroom_time_end = substr($member_noti->classroom_time_end,0,5);
                $this->teacher_fullname = $member_noti->teacher_fullname;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'register_course_private_student'){
                if($member_noti->classroom_start_date != $member_noti->classroom_end_date){
                    $date_time = date('d/m/Y', strtotime($member_noti->classroom_start_date)).' - '.date('d/m/Y', strtotime($member_noti->classroom_end_date));
                }
                else{
                    $date_time = date('d/m/Y', strtotime($member_noti->classroom_start_date));
                }
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->course_id = $member_noti->course_id;
                $this->classroom_name = $member_noti->classroom_name;
                //$this->classroom_date = changeDate($member_noti->classroom_date, 'full_date', $lang);
                $this->classroom_date = $date_time;
                $this->classroom_time_start = substr($member_noti->classroom_time_start,0,5);
                $this->classroom_time_end = substr($member_noti->classroom_time_end,0,5);
                $this->teacher_fullname = $member_noti->teacher_fullname;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'invite_course_student'){
                if($member_noti->classroom_start_date != $member_noti->classroom_end_date){
                    $date_time = date('d/m/Y', strtotime($member_noti->course_start_date)).' - '.date('d/m/Y', strtotime($member_noti->course_end_date));
                }
                else{
                    $date_time = date('d/m/Y', strtotime($member_noti->course_start_date));
                }
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->course_id = $member_noti->course_id;
                $this->course_name = $member_noti->course_name;
                $this->course_datetime = $date_time;
                $this->teacher_fullname = $member_noti->teacher_fullname;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'invite_course_teacher'){
                if($member_noti->classroom_start_date != $member_noti->classroom_end_date){
                    $date_time = date('d/m/Y', strtotime($member_noti->course_start_date)).' - '.date('d/m/Y', strtotime($member_noti->course_end_date));
                }
                else{
                    $date_time = date('d/m/Y', strtotime($member_noti->course_start_date));
                }
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->course_id = $member_noti->course_id;
                $this->course_name = $member_noti->course_name;
                $this->course_datetime = $date_time;
                $this->teacher_fullname = $member_noti->teacher_fullname;
                $this->noti_course_type = $member_noti->noti_course_type;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'invite_course_student_school'){
                if($member_noti->classroom_start_date != $member_noti->classroom_end_date){
                    $date_time = date('d/m/Y', strtotime($member_noti->course_start_date)).' - '.date('d/m/Y', strtotime($member_noti->course_end_date));
                }
                else{
                    $date_time = date('d/m/Y', strtotime($member_noti->course_start_date));
                }
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->course_id = $member_noti->course_id;
                $this->course_name = $member_noti->course_name;
                $this->course_datetime = $date_time;
                $this->teacher_fullname = $member_noti->teacher_fullname;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'cancel_course_teacher'){
                $date_time = date('d/m/Y', strtotime($member_noti->classroom_date));

                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->course_id = $member_noti->course_id;
                $this->classroom_name = $member_noti->classroom_name;
                $this->classroom_date = $date_time;
                $this->classroom_time_start = substr($member_noti->classroom_time_start,0,5);
                $this->classroom_time_end = substr($member_noti->classroom_time_end,0,5);
                $this->classroom_url = $member_noti->classroom_url;
                $this->teacher_fullname = $member_noti->teacher_fullname;
                $this->sum_coins = $member_noti->sum_coins;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'cancel_course_teacher_not'){
                if($member_noti->classroom_start_date != $member_noti->classroom_end_date){
                    $date_time = date('d/m/Y', strtotime($member_noti->classroom_time_start)).' - '.date('d/m/Y', strtotime($member_noti->classroom_time_end));
                }
                else{
                    $date_time = date('d/m/Y', strtotime($member_noti->classroom_time_start));
                }
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->course_id = $member_noti->course_id;
                $this->classroom_name = $member_noti->classroom_name;
                $this->classroom_date = $date_time;;
                $this->classroom_time_start = substr($member_noti->classroom_time_start,0,5);
                $this->classroom_time_end = substr($member_noti->classroom_time_end,0,5);
                $this->classroom_url = $member_noti->classroom_url;
                $this->teacher_fullname = $member_noti->teacher_fullname;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'cancel_course_student'){
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->course_id = $member_noti->course_id;
                $this->classroom_name = $member_noti->classroom_name;
                //$this->classroom_date = changeDate($member_noti->classroom_date, 'full_date', $lang);
                $this->classroom_date = date('d/m/Y', strtotime($member_noti->classroom_date));
                $this->classroom_time_start = substr($member_noti->classroom_time_start,0,5);
                $this->classroom_time_end = substr($member_noti->classroom_time_end,0,5);
                $this->classroom_url = $member_noti->classroom_url;
                $this->teacher_fullname = $member_noti->teacher_fullname;
                $this->sum_coins = $member_noti->sum_coins;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
                $this->course_price = number_format($member_noti->course_price);
            }
            else if($member_noti->noti_type == 'student_request'){
                $this->request_id = $member_noti->request_id;
                $this->request_name = $member_noti->request_name;
                $this->request_date = date('d/m/Y H:i', strtotime($member_noti->request_date));
                $this->request_time = $member_noti->request_time;
                $this->request_member_id = $member_noti->request_member_id;
                $this->request_full_name = $member_noti->request_full_name;
                $this->request_member_email = $member_noti->request_member_email;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'request_to_teacher'){
                $this->member_id = $member_id;
                $this->student_id = $member_noti->student_id;
                $this->student_fullname = $member_noti->student_fullname;
                $this->request_topic = $member_noti->request_topic;
                $this->request_group_th = $member_noti->request_group_name_th;
                $this->request_group_en = $member_noti->request_group_name_en;
                $this->request_subject_th = $member_noti->request_subject_name_th;
                $this->request_subject_en = $member_noti->request_subject_name_en;
                $this->request_datetime = date('d/m/Y H:i', strtotime($member_noti->request_date." ".$member_noti->request_time));
                $this->request_date = date('d/m/Y', strtotime($member_noti->request_date));
                $this->request_time = $member_noti->request_time;
                $this->noti_type = $member_noti->noti_type;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'get_coins_course'){
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->coins = $member_noti->coins;
                $this->course_name = $member_noti->course_name;
                $this->sum_coins = $member_noti->sum_coins;
                $this->get_date = $member_noti->get_date;
                $this->get_time = $member_noti->get_time;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'sent_student_rating'){
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->course_id = $member_noti->course_id;
                $this->classroom_name = $member_noti->classroom_name;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'sent_teacher_rating'){
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->teacher_fullname = $member_noti->teacher_fullname;
                $this->course_id = $member_noti->course_id;
                $this->classroom_name = $member_noti->classroom_name;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'approve_refund_teacher'){
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->student_fullname = $member_noti->student_fullname;
                $this->course_id = $member_noti->course_id;
                $this->course_name = $member_noti->course_name;
                $this->coins = $member_noti->coins;
                $this->refund_description = $member_noti->refund_description;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'approve_refund_student'){
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->course_id = $member_noti->course_id;
                $this->course_name = $member_noti->course_name;
                $this->coins = $member_noti->coins;
                $this->sum_coins = $member_noti->sum_coins;
                $this->refund_description = $member_noti->refund_description;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'not_approve_refund_teacher'){
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->student_fullname = $member_noti->student_fullname;
                $this->course_id = $member_noti->course_id;
                $this->course_name = $member_noti->course_name;
                $this->coins = $member_noti->coins;
                $this->sum_coins = $member_noti->sum_coins;
                $this->refund_description = $member_noti->refund_description;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'not_approve_refund_student'){
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->course_id = $member_noti->course_id;
                $this->course_name = $member_noti->course_name;
                $this->coins = $member_noti->coins;
                $this->refund_description = $member_noti->refund_description;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'post_test_course'){
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->course_id = $member_noti->course_id;
                $this->classroom_name = $member_noti->classroom_name;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }
            else if($member_noti->noti_type == 'cancel_course_school'){
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->course_id = $member_noti->course_id;
                $this->classroom_name = $member_noti->course_name;
                $this->teacher_fullname = $member_noti->teacher_fullname;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }else if($member_noti->noti_type == 'checkend_homework'){
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->assignment_id = $member_noti->assignment_id;
                $this->assignment_name = $member_noti->assignment_name;
                // $this->assignment_url = $member_noti->assignment_url;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }else if($member_noti->noti_type == 'homework_studen')
            {
                $this->member_id = $member_id;
                $this->member_fullname = $member_noti->member_fullname;
                $this->assignment_id = $member_noti->assignment_id;
                $this->assignment_name = $member_noti->assignment_name;
                $this->assignment_date_start = date('d/m/Y H:i', strtotime($member_noti->assignment_date_start.' '.$member_noti->assignment_time_start));;
                $this->assignment_date_end = date('d/m/Y H:i', strtotime($member_noti->assignment_date_end.' '.$member_noti->assignment_time_end));;
                $this->teacher_fullname = $member_noti->teacher_fullname;
                $this->noti_type = $member_noti->noti_type;
                $this->noti_status = $member_noti->noti_status;
                $this->created_at = date('d/m/Y H:i', strtotime($member_noti->created_at));
            }

            $this->count_member_noti = $count_member_noti;
            $this->count_badge_member_noti = $count_badge_member_noti;

        }else{

        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //$my_channel = 'localhost-frontend-channel';
        $my_channel = 'dev-frontend-channel';
        // $my_channel = 'production-frontend-channel';

        return new Channel($my_channel);
    }

    public function broadcastAs()
    {
        //$my_event = 'localhost-frontend-event';
        // $my_event = 'production-frontend-event';
        $my_event = 'dev-frontend-event';

        return $my_event;
    }
}
