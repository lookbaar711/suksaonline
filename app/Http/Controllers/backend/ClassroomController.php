<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Member;
use App\Models\Test;
use App\Models\QueueEmail;
use App\Models\CoinsLog;
use App\Models\BBBSettings;
use App\Models\StudentRating;
use App\Models\TeacherRating;
use Sentinel;
use App\Models\MemberNotification;
use App\Models\QueueNoti;
use Mail;
use App\Events\SendNotiFrontend;
use Session;

class ClassroomController extends Controller
{
    public function index(){
        $classroom = Classroom::orderBy('classroom_date','desc')->get();

        $url = array();

        //select bbb server
        $bbb_setting = BBBSettings::first();

        libxml_use_internal_errors(true);

        $server_amount = $bbb_setting->server_amount;
        $camera_per_server = $bbb_setting->camera_per_server;
        $hostname = $bbb_setting->host_name;

        foreach ($classroom as $class) {
            $course_id = $class->course_id;
            $course_name = $class->classroom_name;

            if(isset($class->classroom_link)){
                $url[$course_id] = $class->classroom_link;
            }
            else{
                $url[$course_id] = '';
            }
        }

        return view('backend.classroom.classroom-index', compact('classroom','url'));
    }

    public function getModalOpenClassRoom($id)
    {
        $model = 'classroom';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('backend.classroom.open_class_room', ['id' => $classroom->course_id]);
            return view('backend.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {
            $error = trans('categories/message.error.open_class_room', compact('id'));
            return view('backend.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }

    public function openClassRoom($id)
    {
        set_time_limit(3000);

        $classroom = Classroom::where('classroom_status', '!=' ,'2')
                    ->where('course_id', '=', $id)
                    ->first();

        //select bbb server
        $bbb_setting = BBBSettings::first();

        libxml_use_internal_errors(true);

        $server_amount = $bbb_setting->server_amount;
        $last_classroom_server = $bbb_setting->last_classroom_server;
        $hostname = $bbb_setting->host_name;

        //ถ้ามี classroom_server ให้ใช้ server เดิม
        if(isset($classroom->classroom_server)){
            if($classroom->classroom_server == 'channel1.suksalive.com'){
                $server = $bbb_setting->bbb_url_1;
                $shared_secret = $bbb_setting->bbb_shared_secret_1;
            }
            else if($classroom->classroom_server == 'channel2.suksalive.com'){
                $server = $bbb_setting->bbb_url_2;
                $shared_secret = $bbb_setting->bbb_shared_secret_2;
            }
            else if($classroom->classroom_server == 'channel3.suksalive.com'){
                $server = $bbb_setting->bbb_url_3;
                $shared_secret = $bbb_setting->bbb_shared_secret_3;
            }
            else if($classroom->classroom_server == 'channel4.suksalive.com'){
                $server = $bbb_setting->bbb_url_4;
                $shared_secret = $bbb_setting->bbb_shared_secret_4;
            }
            else{ //suksalive.com
                $server = $bbb_setting->bbb_url_5;
                $shared_secret = $bbb_setting->bbb_shared_secret_5;
            }

            $classroom_server = $classroom->classroom_server;
        }
        //ถ้าไม่มี classroom_server ระบบจะเลือก server ให้อัตโนมัติ
        else{
            if($server_amount == 1){
                $server = $bbb_setting->bbb_url_1;
                $shared_secret = $bbb_setting->bbb_shared_secret_1;
            }
            else if($server_amount == 2){
                if($last_classroom_server == "channel1.suksalive.com"){
                    $server = $bbb_setting->bbb_url_2;
                    $shared_secret = $bbb_setting->bbb_shared_secret_2;
                    $case = '2';
                }
                else{
                    $server = $bbb_setting->bbb_url_1;
                    $shared_secret = $bbb_setting->bbb_shared_secret_1;
                    $case = '1';
                }
            }
            else if($server_amount == 3){
                if($last_classroom_server == "channel1.suksalive.com"){
                    $server = $bbb_setting->bbb_url_2;
                    $shared_secret = $bbb_setting->bbb_shared_secret_2;
                    $case = '2';
                }
                else if($last_classroom_server == "channel2.suksalive.com"){
                    $server = $bbb_setting->bbb_url_3;
                    $shared_secret = $bbb_setting->bbb_shared_secret_3;
                    $case = '3';
                }
                else{
                    $server = $bbb_setting->bbb_url_1;
                    $shared_secret = $bbb_setting->bbb_shared_secret_1;
                    $case = '1';
                }
            }
            else if($server_amount == 4){
                if($last_classroom_server == "channel1.suksalive.com"){
                    $server = $bbb_setting->bbb_url_2;
                    $shared_secret = $bbb_setting->bbb_shared_secret_2;
                    $case = '2';
                }
                else if($last_classroom_server == "channel2.suksalive.com"){
                    $server = $bbb_setting->bbb_url_3;
                    $shared_secret = $bbb_setting->bbb_shared_secret_3;
                    $case = '3';
                }
                else if($last_classroom_server == "channel3.suksalive.com"){
                    $server = $bbb_setting->bbb_url_4;
                    $shared_secret = $bbb_setting->bbb_shared_secret_4;
                    $case = '4';
                }
                else{
                    $server = $bbb_setting->bbb_url_1;
                    $shared_secret = $bbb_setting->bbb_shared_secret_1;
                    $case = '1';
                }
            }
            else{
                if($last_classroom_server == "channel1.suksalive.com"){
                    $server = $bbb_setting->bbb_url_2;
                    $shared_secret = $bbb_setting->bbb_shared_secret_2;
                    $case = '2';
                }
                else if($last_classroom_server == "channel2.suksalive.com"){
                    $server = $bbb_setting->bbb_url_3;
                    $shared_secret = $bbb_setting->bbb_shared_secret_3;
                    $case = '3';
                }
                else if($last_classroom_server == "channel3.suksalive.com"){
                    $server = $bbb_setting->bbb_url_4;
                    $shared_secret = $bbb_setting->bbb_shared_secret_4;
                    $case = '4';
                }
                else if($last_classroom_server == "channel4.suksalive.com"){
                    $server = $bbb_setting->bbb_url_5;
                    $shared_secret = $bbb_setting->bbb_shared_secret_5;
                    $case = '5';
                }
                else{
                    $server = $bbb_setting->bbb_url_1;
                    $shared_secret = $bbb_setting->bbb_shared_secret_1;
                    $case = '1';
                }
            }

            //select domain name from server
            $classroom_url = parse_url($server);
            $classroom_server = $classroom_url['host'];

            $bbb_setting->last_classroom_server = $classroom_url['host'];
            $bbb_setting->update();
        }

        $teacher_id = $classroom->classroom_teacher['teacher_id'];
        $teacher_fullname = $classroom->classroom_teacher['teacher_fname'].' '.$classroom->classroom_teacher['teacher_lname'];
        $teacher_email = $classroom->classroom_teacher['teacher_email'];
        $course_id = $classroom->course_id;
        $course_name = $classroom->classroom_name;
        $classroom_date = $classroom->classroom_date;
        $classroom_time_start = $classroom->classroom_time_start;
        $classroom_time_end = $classroom->classroom_time_end;

        //$server = 'https://suksalive.com/bigbluebutton/';
        //$shared_secret = 'cacvGZrcHNxK2RXsYB9TQUH7iPHvNa0GxuIjAlmPUM';

        $allowStartStopRecording = 'true';
        $attendeePW = 'ap';
        $autoStartRecording = 'false';

        //$logoutURL = 'http://127.0.0.1:8000/backend/classroom/logout/'.$id;
        $logoutURL = 'http://'.$hostname.'/';
        $meetingID = 'suksa-'.$classroom->course_id;
        $moderatorPW = 'mp';
        $name = $course_name;
        $record = 'true';

        $digits = 5;
        $last = rand(0,9);
        $random = intval(rand(pow(10, $digits-1), pow(10, $digits)-1) + $last);

        $voiceBridge = $random; //random number ไม่ซ้ำ
        $welcome = '<br>Welcome to <b>Suksa Online</b><br>คอร์สเรียน <font color="red"><b>'.$course_name.'</b></font>';

        //generate checksum
        $checksum = sha1('createallowStartStopRecording='.$allowStartStopRecording.'&attendeePW='.$attendeePW.'&autoStartRecording='.$autoStartRecording.'&logoutURL='.$logoutURL.'&meetingID='.$meetingID.'&moderatorPW='.$moderatorPW.'&name='.urlencode($name).'&record='.$record.'&voiceBridge='.$voiceBridge.'&welcome='.urlencode($welcome).$shared_secret);

        $create_url = $server.'api/create?allowStartStopRecording='.$allowStartStopRecording.'&attendeePW='.$attendeePW.'&autoStartRecording='.$autoStartRecording.'&logoutURL='.$logoutURL.'&meetingID='.$meetingID.'&moderatorPW='.$moderatorPW.'&name='.urlencode($name).'&record='.$record.'&voiceBridge='.$voiceBridge.'&welcome='.urlencode($welcome).'&checksum='.$checksum;


        $classroom->classroom_status = '1';
        $classroom->updated_at = date('Y-m-d H:i:s');

        if($classroom->update()){

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $create_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    // Set Here Your Requesred Headers
                    'Content-Type: application/json',
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if($err) {
                Session::put('bbb_status','CURL Error : '.$err);
                return redirect('backend/classroom');
            }
            else {
                //call create api success

                $fullName = $teacher_fullname;
                $password = 'mp';
                $redirect = 'true';

                //generate checksum
                $checksum = sha1('joinfullName='.urlencode($fullName).'&meetingID='.$meetingID.'&password='.$password.'&redirect='.$redirect.$shared_secret);

                $join_moderator_url = $server.'api/join?fullName='.urlencode($fullName).'&meetingID='.$meetingID.'&password='.$password.'&redirect='.$redirect.'&checksum='.$checksum;

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $join_moderator_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_TIMEOUT => 30000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        // Set Here Your Requesred Headers
                        'Content-Type: application/json',
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                //send email to teacher
                $subject = 'Suksa Online : เปิดคอร์ส '.$course_name.' เรียบร้อยแล้ว';
                $from_name = 'Suksa Online';
                $from_email = 'noreply@suksa.online';
                $to_name = $teacher_fullname;
                $to_email = $teacher_email;
                $description = '';
                $data = array(
                    'name'=>$teacher_fullname,
                    "course" => $course_name,
                    "teacher_fullname" => $teacher_fullname,
                    "url" => $join_moderator_url,
                    "start_date" => $classroom_date,
                    "start_time" => $classroom_time_start,
                    "end_time" => $classroom_time_end
                );

                //insert queue send email to teacher
                $queue_email_teacher = new QueueEmail();
                $queue_email_teacher->email_type = 'send_email_classroom';
                $queue_email_teacher->data = $data;
                $queue_email_teacher->subject = $subject;
                $queue_email_teacher->from_name = $from_name;
                $queue_email_teacher->from_email = $from_email;
                $queue_email_teacher->to_name = $to_name;
                $queue_email_teacher->to_email = $to_email;
                $queue_email_teacher->queue_status = '0';
                $queue_email_teacher->save();

                //insert noti to teacher
                $teacher_noti = new MemberNotification();
                $teacher_noti->course_id = $classroom->course_id;
                $teacher_noti->classroom_date = $classroom_date;
                $teacher_noti->classroom_name = $course_name;
                $teacher_noti->classroom_time_start = $classroom_time_start;
                $teacher_noti->classroom_time_end = $classroom_time_end;
                $teacher_noti->classroom_url = $join_moderator_url;
                $teacher_noti->member_id = $teacher_id;
                $teacher_noti->member_fullname = $teacher_fullname;
                $teacher_noti->teacher_id = $teacher_id;
                $teacher_noti->teacher_fullname = $teacher_fullname;
                $teacher_noti->noti_type = 'open_course_teacher';
                $teacher_noti->noti_status = '0';
                $teacher_noti->save();

                //send noti to teacher
                sendMemberNoti($teacher_id);

                $url[0] = $join_moderator_url;

                $i = 1;

                foreach ($classroom->classroom_student as $key => $value) {
                    $student_id = $value['student_id'];
                    $student_fullname = $value['student_fname'].' '.$value['student_lname'];
                    $student_email = $value['student_email'];

                    $fullName = $student_fullname;
                    $password = 'ap';
                    $redirect = 'true';

                    //generate checksum
                    $checksum = sha1('joinfullName='.urlencode($fullName).'&meetingID='.$meetingID.'&password='.$password.'&redirect='.$redirect.$shared_secret);

                    $join_attendee_url[$value['student_email']] = $server.'api/join?fullName='.urlencode($fullName).'&meetingID='.$meetingID.'&password='.$password.'&redirect='.$redirect.'&checksum='.$checksum;

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $join_attendee_url[$value['student_email']],
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_TIMEOUT => 30000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => array(
                            // Set Here Your Requesred Headers
                            'Content-Type: application/json',
                        ),
                    ));
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);

                    //send email to student
                    $subject = 'Suksa Online : เปิดคอร์ส '.$course_name.' เรียบร้อยแล้ว';
                    $from_name = 'Suksa Online';
                    $from_email = 'noreply@suksa.online';
                    $to_name = $student_fullname;
                    $to_email = $student_email;
                    $description = '';
                    $data = array(
                        'name'=>$student_fullname,
                        "course" => $course_name,
                        "teacher_fullname" => $teacher_fullname,
                        "url" => $join_attendee_url[$value['student_email']],
                        "start_date" => $classroom_date,
                        "start_time" => $classroom_time_start,
                        "end_time" => $classroom_time_end
                    );

                    //insert queue send email to student
                    $queue_email_student = new QueueEmail();
                    $queue_email_student->email_type = 'send_email_classroom';
                    $queue_email_student->data = $data;
                    $queue_email_student->subject = $subject;
                    $queue_email_student->from_name = $from_name;
                    $queue_email_student->from_email = $from_email;
                    $queue_email_student->to_name = $to_name;
                    $queue_email_student->to_email = $to_email;
                    $queue_email_student->queue_status = '0';
                    $queue_email_student->save();

                    $url[$i] = $join_attendee_url[$value['student_email']];

                    //insert noti to student
                    $student_noti = new MemberNotification();
                    $student_noti->course_id = $classroom->course_id;
                    $student_noti->classroom_date = $classroom_date;
                    $student_noti->classroom_name = $course_name;
                    $student_noti->classroom_time_start = $classroom_time_start;
                    $student_noti->classroom_time_end = $classroom_time_end;
                    $student_noti->classroom_url = $join_attendee_url[$value['student_email']];
                    $student_noti->member_id = $student_id;
                    $student_noti->member_fullname = $student_fullname;
                    $student_noti->teacher_id = $teacher_id;
                    $student_noti->teacher_fullname = $teacher_fullname;
                    $student_noti->noti_type = 'open_course_student';
                    $student_noti->noti_status = '0';
                    $student_noti->save();

                    //send noti to student
                    sendMemberNoti($student_id);

                    $i++;
                }

                //update classroom_server
                $classroom->classroom_server = $classroom_server;
                $classroom->save();

                Session::put('bbb_status','เปิดห้องสอนนี้เรียบร้อยแล้ว');
                return redirect('backend/classroom');
            }
        }
        else{
            Session::put('bbb_status','เปิดห้องสอนนี้ไม่สำเร็จ');
            return redirect('backend/classroom');
        }
    }

    public function logout($id)
    {
        $classroom = Classroom::where('classroom_status', '!=' ,'2')
                    ->where('course_id', '=', $id)
                    ->first();

        $classroom->classroom_status = '2';
        $hostname = get_hostname();

        //select bbb server
        $bbb_setting = BBBSettings::first();

        if ($classroom->update()) {
            //call api end
            if($classroom->classroom_server == 'channel1.suksalive.com'){
                $server = $bbb_setting->bbb_url_1;
                $shared_secret = $bbb_setting->bbb_shared_secret_1;
            }
            else if($classroom->classroom_server == 'channel2.suksalive.com'){
                $server = $bbb_setting->bbb_url_2;
                $shared_secret = $bbb_setting->bbb_shared_secret_2;
            }
            else if($classroom->classroom_server == 'channel3.suksalive.com'){
                $server = $bbb_setting->bbb_url_3;
                $shared_secret = $bbb_setting->bbb_shared_secret_3;
            }
            else if($classroom->classroom_server == 'channel4.suksalive.com'){
                $server = $bbb_setting->bbb_url_4;
                $shared_secret = $bbb_setting->bbb_shared_secret_4;
            }
            else{ //suksalive.com
                $server = $bbb_setting->bbb_url_5;
                $shared_secret = $bbb_setting->bbb_shared_secret_5;
            }

            // $server = 'https://suksalive.com/bigbluebutton/';
            // $shared_secret = 'cacvGZrcHNxK2RXsYB9TQUH7iPHvNa0GxuIjAlmPUM';

            $meetingID = 'suksa-'.$id;
            $password = 'mp';

            //generate checksum
            $checksum = sha1('endmeetingID='.$meetingID.'&password='.$password.$shared_secret);

            $end_url = $server.'api/end?meetingID='.$meetingID.'&password='.$password.'&checksum='.$checksum;

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $end_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    // Set Here Your Requesred Headers
                    'Content-Type: application/json',
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if($err) {
                return Redirect::route('backend/classroom')->withInput()->with('error', 'CURL Error : '.$err);
            }
            else {
                return redirect('http://'.$hostname.'/');
            }


        } else {
            return Redirect::route('backend/classroom')->withInput()->with('error', 'Log out ไม่สำเร็จ');
        }

    }


    public function openClassRoomCronJob()
    {
        set_time_limit(50000);
        $today = date('Y-m-d');
        $before_open_class_min = 10; //minute
        $strtotime_before_open_class = $before_open_class_min * 60;
        $current_time = date('H:i:s');
        $set_open_time = date('H:i', strtotime($current_time)+$strtotime_before_open_class);
        $set_open_time_down5 = date('H:i', strtotime($set_open_time)-300);

        $classrooms = Classroom::where('classroom_status', '0')
                    ->where('classroom_date', 'like', '%'.$today.'%')
                    ->whereBetween('classroom_time_start', [$set_open_time_down5.':00', $set_open_time.':00'])
                    // ->offset(0)
                    // ->limit(5)
                    ->offset(0)
                    ->limit(9)
                    ->get();

        foreach ($classrooms as $classroom) {

            $url = array();

            //select bbb server
            $bbb_setting = BBBSettings::first();

            libxml_use_internal_errors(true);

            $server_amount = $bbb_setting->server_amount;
            $last_classroom_server = $bbb_setting->last_classroom_server;
            $hostname = $bbb_setting->host_name;

            //ถ้ามี classroom_server ให้ใช้ server เดิม
            if(isset($classroom->classroom_server)){
                if($classroom->classroom_server == 'channel1.suksalive.com'){
                    $server = $bbb_setting->bbb_url_1;
                    $shared_secret = $bbb_setting->bbb_shared_secret_1;
                }
                else if($classroom->classroom_server == 'channel2.suksalive.com'){
                    $server = $bbb_setting->bbb_url_2;
                    $shared_secret = $bbb_setting->bbb_shared_secret_2;
                }
                else if($classroom->classroom_server == 'channel3.suksalive.com'){
                    $server = $bbb_setting->bbb_url_3;
                    $shared_secret = $bbb_setting->bbb_shared_secret_3;
                }
                else if($classroom->classroom_server == 'channel4.suksalive.com'){
                    $server = $bbb_setting->bbb_url_4;
                    $shared_secret = $bbb_setting->bbb_shared_secret_4;
                }
                else{ //suksalive.com
                    $server = $bbb_setting->bbb_url_5;
                    $shared_secret = $bbb_setting->bbb_shared_secret_5;
                }

                $classroom_server = $classroom->classroom_server;
            }
            //ถ้าไม่มี classroom_server ให้ระบบเลือก server ให้อัตโนมัติ
            else{
                if($server_amount == 1){
                    $server = $bbb_setting->bbb_url_1;
                    $shared_secret = $bbb_setting->bbb_shared_secret_1;
                }
                else if($server_amount == 2){
                    if($last_classroom_server == "channel1.suksalive.com"){
                        $server = $bbb_setting->bbb_url_2;
                        $shared_secret = $bbb_setting->bbb_shared_secret_2;
                        $case = '2';
                    }
                    else{
                        $server = $bbb_setting->bbb_url_1;
                        $shared_secret = $bbb_setting->bbb_shared_secret_1;
                        $case = '1';
                    }
                }
                else if($server_amount == 3){
                    if($last_classroom_server == "channel1.suksalive.com"){
                        $server = $bbb_setting->bbb_url_2;
                        $shared_secret = $bbb_setting->bbb_shared_secret_2;
                        $case = '2';
                    }
                    else if($last_classroom_server == "channel2.suksalive.com"){
                        $server = $bbb_setting->bbb_url_3;
                        $shared_secret = $bbb_setting->bbb_shared_secret_3;
                        $case = '3';
                    }
                    else{
                        $server = $bbb_setting->bbb_url_1;
                        $shared_secret = $bbb_setting->bbb_shared_secret_1;
                        $case = '1';
                    }
                }
                else if($server_amount == 4){
                    if($last_classroom_server == "channel1.suksalive.com"){
                        $server = $bbb_setting->bbb_url_2;
                        $shared_secret = $bbb_setting->bbb_shared_secret_2;
                        $case = '2';
                    }
                    else if($last_classroom_server == "channel2.suksalive.com"){
                        $server = $bbb_setting->bbb_url_3;
                        $shared_secret = $bbb_setting->bbb_shared_secret_3;
                        $case = '3';
                    }
                    else if($last_classroom_server == "channel3.suksalive.com"){
                        $server = $bbb_setting->bbb_url_4;
                        $shared_secret = $bbb_setting->bbb_shared_secret_4;
                        $case = '4';
                    }
                    else{
                        $server = $bbb_setting->bbb_url_1;
                        $shared_secret = $bbb_setting->bbb_shared_secret_1;
                        $case = '1';
                    }
                }
                else{
                    if($last_classroom_server == "channel1.suksalive.com"){
                        $server = $bbb_setting->bbb_url_2;
                        $shared_secret = $bbb_setting->bbb_shared_secret_2;
                        $case = '2';
                    }
                    else if($last_classroom_server == "channel2.suksalive.com"){
                        $server = $bbb_setting->bbb_url_3;
                        $shared_secret = $bbb_setting->bbb_shared_secret_3;
                        $case = '3';
                    }
                    else if($last_classroom_server == "channel3.suksalive.com"){
                        $server = $bbb_setting->bbb_url_4;
                        $shared_secret = $bbb_setting->bbb_shared_secret_4;
                        $case = '4';
                    }
                    else if($last_classroom_server == "channel4.suksalive.com"){
                        $server = $bbb_setting->bbb_url_5;
                        $shared_secret = $bbb_setting->bbb_shared_secret_5;
                        $case = '5';
                    }
                    else{
                        $server = $bbb_setting->bbb_url_1;
                        $shared_secret = $bbb_setting->bbb_shared_secret_1;
                        $case = '1';
                    }
                }

                //select domain name from server
                $classroom_url = parse_url($server);
                $classroom_server = $classroom_url['host'];

                $bbb_setting->last_classroom_server = $classroom_url['host'];
                $bbb_setting->update();
            }

            // Test::insert([
            //     'time' => $current_time,
            //     'now_quota_1' => $now_quota_1,
            //     'count_user' => $count_user,
            //     'case' => $case
            // ]);


            $teacher_id = $classroom->classroom_teacher['teacher_id'];
            $teacher_fullname = $classroom->classroom_teacher['teacher_fname'].' '.$classroom->classroom_teacher['teacher_lname'];
            $teacher_email = $classroom->classroom_teacher['teacher_email'];
            $course_id = $classroom->course_id;
            $course_name = $classroom->classroom_name;
            $classroom_date = $classroom->classroom_date;
            $classroom_time_start = $classroom->classroom_time_start;
            $classroom_time_end = $classroom->classroom_time_end;

            //$server = 'https://suksalive.com/bigbluebutton/';
            //$shared_secret = 'cacvGZrcHNxK2RXsYB9TQUH7iPHvNa0GxuIjAlmPUM';

            $allowStartStopRecording = 'true';
            $attendeePW = 'ap';
            $autoStartRecording = 'false';

            //$logoutURL = 'http://127.0.0.1:8000/backend/classroom/logout/'.$id;
            $logoutURL = 'http://'.$hostname.'/';
            $meetingID = 'suksa-'.$classroom->course_id;
            $moderatorPW = 'mp';
            $name = $course_name;
            $record = 'true';

            $digits = 5;
            $last = rand(0,9);
            $random = intval(rand(pow(10, $digits-1), pow(10, $digits)-1) + $last);

            $voiceBridge = $random; //random number ไม่ซ้ำ
            $welcome = '<br>Welcome to <b>Suksa Online</b><br>คอร์สเรียน <font color="red"><b>'.$course_name.'</b></font>';

            //generate checksum
            $checksum = sha1('createallowStartStopRecording='.$allowStartStopRecording.'&attendeePW='.$attendeePW.'&autoStartRecording='.$autoStartRecording.'&logoutURL='.$logoutURL.'&meetingID='.$meetingID.'&moderatorPW='.$moderatorPW.'&name='.urlencode($name).'&record='.$record.'&voiceBridge='.$voiceBridge.'&welcome='.urlencode($welcome).$shared_secret);

            $create_url = $server.'api/create?allowStartStopRecording='.$allowStartStopRecording.'&attendeePW='.$attendeePW.'&autoStartRecording='.$autoStartRecording.'&logoutURL='.$logoutURL.'&meetingID='.$meetingID.'&moderatorPW='.$moderatorPW.'&name='.urlencode($name).'&record='.$record.'&voiceBridge='.$voiceBridge.'&welcome='.urlencode($welcome).'&checksum='.$checksum;




            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $create_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_TIMEOUT => 50000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    // Set Here Your Requesred Headers
                    'Content-Type: application/json',
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);


            if($err) {
                echo 'CURL Error : '.$err;
            }
            else {

                //update classroom_status
                $classroom->classroom_status = '9';
                 //update classroom_server
                 $classroom->classroom_server = $classroom_server;
                 $classroom->update();
                 $classroom->save();

            }

        }

    }




    public function openClassRoomRealTimeCronJob()
    {
        set_time_limit(3000);

        $today = date('Y-m-d');
        $set_open_time = date('H:i');
        $set_open_time_up = date('H:i', strtotime('+1 minutes'));

        $classrooms = Classroom::where('classroom_status', '0')
                    ->where('classroom_date', 'like', '%'.$today.'%')
                    ->whereBetween('classroom_time_start', [$set_open_time.':00', $set_open_time_up.':00'])
                    ->offset(0)
                    ->limit(9)
                    ->get();

        $url = array();

        //select bbb server
        $bbb_setting = BBBSettings::first();

        libxml_use_internal_errors(true);

        $server_amount = $bbb_setting->server_amount;
        $last_classroom_server = $bbb_setting->last_classroom_server;
        $hostname = $bbb_setting->host_name;

        foreach ($classrooms as $classroom) {
            //ถ้ามี classroom_server ให้ใช้ server เดิม
            if(isset($classroom->classroom_server)){
                if($classroom->classroom_server == 'channel1.suksalive.com'){
                    $server = $bbb_setting->bbb_url_1;
                    $shared_secret = $bbb_setting->bbb_shared_secret_1;
                }
                else if($classroom->classroom_server == 'channel2.suksalive.com'){
                    $server = $bbb_setting->bbb_url_2;
                    $shared_secret = $bbb_setting->bbb_shared_secret_2;
                }
                else if($classroom->classroom_server == 'channel3.suksalive.com'){
                    $server = $bbb_setting->bbb_url_3;
                    $shared_secret = $bbb_setting->bbb_shared_secret_3;
                }
                else if($classroom->classroom_server == 'channel4.suksalive.com'){
                    $server = $bbb_setting->bbb_url_4;
                    $shared_secret = $bbb_setting->bbb_shared_secret_4;
                }
                else{ //suksalive.com
                    $server = $bbb_setting->bbb_url_5;
                    $shared_secret = $bbb_setting->bbb_shared_secret_5;
                }

                $classroom_server = $classroom->classroom_server;
            }
            //ถ้าไม่มี classroom_server ให้ระบบเลือก server ให้อัตโนมัติ
            else{
                if($server_amount == 1){
                    $server = $bbb_setting->bbb_url_1;
                    $shared_secret = $bbb_setting->bbb_shared_secret_1;
                }
                else if($server_amount == 2){
                    if($last_classroom_server == "channel1.suksalive.com"){
                        $server = $bbb_setting->bbb_url_2;
                        $shared_secret = $bbb_setting->bbb_shared_secret_2;
                        $case = '2';
                    }
                    else{
                        $server = $bbb_setting->bbb_url_1;
                        $shared_secret = $bbb_setting->bbb_shared_secret_1;
                        $case = '1';
                    }
                }
                else if($server_amount == 3){
                    if($last_classroom_server == "channel1.suksalive.com"){
                        $server = $bbb_setting->bbb_url_2;
                        $shared_secret = $bbb_setting->bbb_shared_secret_2;
                        $case = '2';
                    }
                    else if($last_classroom_server == "channel2.suksalive.com"){
                        $server = $bbb_setting->bbb_url_3;
                        $shared_secret = $bbb_setting->bbb_shared_secret_3;
                        $case = '3';
                    }
                    else{
                        $server = $bbb_setting->bbb_url_1;
                        $shared_secret = $bbb_setting->bbb_shared_secret_1;
                        $case = '1';
                    }
                }
                else if($server_amount == 4){
                    if($last_classroom_server == "channel1.suksalive.com"){
                        $server = $bbb_setting->bbb_url_2;
                        $shared_secret = $bbb_setting->bbb_shared_secret_2;
                        $case = '2';
                    }
                    else if($last_classroom_server == "channel2.suksalive.com"){
                        $server = $bbb_setting->bbb_url_3;
                        $shared_secret = $bbb_setting->bbb_shared_secret_3;
                        $case = '3';
                    }
                    else if($last_classroom_server == "channel3.suksalive.com"){
                        $server = $bbb_setting->bbb_url_4;
                        $shared_secret = $bbb_setting->bbb_shared_secret_4;
                        $case = '4';
                    }
                    else{
                        $server = $bbb_setting->bbb_url_1;
                        $shared_secret = $bbb_setting->bbb_shared_secret_1;
                        $case = '1';
                    }
                }
                else{
                    if($last_classroom_server == "channel1.suksalive.com"){
                        $server = $bbb_setting->bbb_url_2;
                        $shared_secret = $bbb_setting->bbb_shared_secret_2;
                        $case = '2';
                    }
                    else if($last_classroom_server == "channel2.suksalive.com"){
                        $server = $bbb_setting->bbb_url_3;
                        $shared_secret = $bbb_setting->bbb_shared_secret_3;
                        $case = '3';
                    }
                    else if($last_classroom_server == "channel3.suksalive.com"){
                        $server = $bbb_setting->bbb_url_4;
                        $shared_secret = $bbb_setting->bbb_shared_secret_4;
                        $case = '4';
                    }
                    else if($last_classroom_server == "channel4.suksalive.com"){
                        $server = $bbb_setting->bbb_url_5;
                        $shared_secret = $bbb_setting->bbb_shared_secret_5;
                        $case = '5';
                    }
                    else{
                        $server = $bbb_setting->bbb_url_1;
                        $shared_secret = $bbb_setting->bbb_shared_secret_1;
                        $case = '1';
                    }
                }

                //select domain name from server
                $classroom_url = parse_url($server);
                $classroom_server = $classroom_url['host'];

                $bbb_setting->last_classroom_server = $classroom_url['host'];
                $bbb_setting->update();
            }


            // Test::insert([
            //     'time' => $current_time,
            //     'now_quota_1' => $now_quota_1,
            //     'count_user' => $count_user,
            //     'case' => $case
            // ]);


            $teacher_id = $classroom->classroom_teacher['teacher_id'];
            $teacher_fullname = $classroom->classroom_teacher['teacher_fname'].' '.$classroom->classroom_teacher['teacher_lname'];
            $teacher_email = $classroom->classroom_teacher['teacher_email'];
            $course_id = $classroom->course_id;
            $course_name = $classroom->classroom_name;
            $classroom_date = $classroom->classroom_date;
            $classroom_time_start = $classroom->classroom_time_start;
            $classroom_time_end = $classroom->classroom_time_end;

            //$server = 'https://suksalive.com/bigbluebutton/';
            //$shared_secret = 'cacvGZrcHNxK2RXsYB9TQUH7iPHvNa0GxuIjAlmPUM';

            $allowStartStopRecording = 'true';
            $attendeePW = 'ap';
            $autoStartRecording = 'false';

            //$logoutURL = 'http://127.0.0.1:8000/backend/classroom/logout/'.$id;
            $logoutURL = 'http://'.$hostname.'/';
            $meetingID = 'suksa-'.$classroom->course_id;
            $moderatorPW = 'mp';
            $name = $course_name;
            $record = 'true';

            $digits = 5;
            $last = rand(0,9);
            $random = intval(rand(pow(10, $digits-1), pow(10, $digits)-1) + $last);

            $voiceBridge = $random; //random number ไม่ซ้ำ
            $welcome = '<br>Welcome to <b>Suksa Online</b><br>คอร์สเรียน <font color="red"><b>'.$course_name.'</b></font>';

            //generate checksum
            $checksum = sha1('createallowStartStopRecording='.$allowStartStopRecording.'&attendeePW='.$attendeePW.'&autoStartRecording='.$autoStartRecording.'&logoutURL='.$logoutURL.'&meetingID='.$meetingID.'&moderatorPW='.$moderatorPW.'&name='.urlencode($name).'&record='.$record.'&voiceBridge='.$voiceBridge.'&welcome='.urlencode($welcome).$shared_secret);

            $create_url = $server.'api/create?allowStartStopRecording='.$allowStartStopRecording.'&attendeePW='.$attendeePW.'&autoStartRecording='.$autoStartRecording.'&logoutURL='.$logoutURL.'&meetingID='.$meetingID.'&moderatorPW='.$moderatorPW.'&name='.urlencode($name).'&record='.$record.'&voiceBridge='.$voiceBridge.'&welcome='.urlencode($welcome).'&checksum='.$checksum;

            //update classroom_status
            $classroom->classroom_status = '1';
            $classroom->update();


            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $create_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    // Set Here Your Requesred Headers
                    'Content-Type: application/json',
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);


            if($err) {
                echo 'CURL Error : '.$err;
            }
            else {
                //call create api success

                //check teacher data before insert
                // $count_teacher_noti = MemberNotification::where('teacher_id', $teacher_id)
                //     ->where('course_id', $course_id)
                //     ->where('classroom_date', $today)
                //     ->where('noti_type', 'open_course_teacher')
                //     ->count();

                //if($count_teacher_noti == 0){
                    $fullName = $teacher_fullname;
                    $password = 'mp';
                    $redirect = 'true';

                    //generate checksum
                    $checksum = sha1('joinfullName='.urlencode($fullName).'&meetingID='.$meetingID.'&password='.$password.'&redirect='.$redirect.$shared_secret);

                    $join_moderator_url = $server.'api/join?fullName='.urlencode($fullName).'&meetingID='.$meetingID.'&password='.$password.'&redirect='.$redirect.'&checksum='.$checksum;

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $join_moderator_url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_TIMEOUT => 30000,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => array(
                            // Set Here Your Requesred Headers
                            'Content-Type: application/json',
                        ),
                    ));
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);

                    //insert noti to teacher
                    // $teacher_noti = new MemberNotification();
                    $teacher_noti['course_id'] = $course_id;
                    $teacher_noti['classroom_date'] = $classroom_date;
                    $teacher_noti['classroom_name'] = $course_name;
                    $teacher_noti['classroom_time_start'] = $classroom_time_start;
                    $teacher_noti['classroom_time_end'] = $classroom_time_end;
                    $teacher_noti['classroom_url'] = $join_moderator_url;
                    $teacher_noti['member_id'] = $teacher_id;
                    $teacher_noti['member_fullname'] = $teacher_fullname;
                    $teacher_noti['teacher_id'] = $teacher_id;
                    $teacher_noti['teacher_fullname'] = $teacher_fullname;
                    $teacher_noti['noti_type'] = 'open_course_teacher';
                    $teacher_noti['noti_status'] = '0';
                    $noti_id_teacher = MemberNotification::create($teacher_noti);
                    // $teacher_noti->save();

                    //send noti to teacher
                    sendMemberNoti($teacher_id);


                    //send email to teacher
                    $url_inclass_teacher = $hostname.'/classroom/check/'.$noti_id_teacher->_id;
                    $subject = 'Suksa Online : เปิดคอร์ส '.$course_name.' เรียบร้อยแล้ว';
                    $from_name = 'Suksa Online';
                    $from_email = 'noreply@suksa.online';
                    $to_name = $teacher_fullname;
                    $to_email = $teacher_email;
                    $description = '';
                    $data = array(
                        'name'=>$teacher_fullname,
                        "course" => $course_name,
                        "teacher_fullname" => $teacher_fullname,
                        "url" => $url_inclass_teacher,
                        "start_date" => $classroom_date,
                        "start_time" => $classroom_time_start,
                        "end_time" => $classroom_time_end
                        // "start_date" => changeDate($classroom_date, 'full_date', 'th'),
                        // "start_time" => substr($classroom_time_start,0,5),
                        // "end_time" => substr($classroom_time_end,0,5)
                    );

                    //insert queue send email to teacher
                    $queue_email_teacher = new QueueEmail();
                    $queue_email_teacher->email_type = 'send_email_classroom';
                    $queue_email_teacher->data = $data;
                    $queue_email_teacher->subject = $subject;
                    $queue_email_teacher->from_name = $from_name;
                    $queue_email_teacher->from_email = $from_email;
                    $queue_email_teacher->to_name = $to_name;
                    $queue_email_teacher->to_email = $to_email;
                    $queue_email_teacher->queue_status = '0';
                    $queue_email_teacher->save();

                    // Mail::send('frontend.send_email_classroom', $data, function($message) use ($subject, $from_name, $from_email, $to_name, $to_email) {
                    //     $message->from($from_email, $from_name);
                    //     $message->to($to_email, $to_name);
                    //     $message->subject($subject);
                    // });

                    $url[$classroom->course_id][0] = $join_moderator_url;




                //}

                $i = 1;

                foreach ($classroom->classroom_student as $key => $value) {
                    $student_id = $value['student_id'];
                    $student_fullname = $value['student_fname'].' '.$value['student_lname'];
                    $student_email = $value['student_email'];

                    //check student data before insert
                    // $count_student_noti = MemberNotification::where('member_id', $student_id)
                    //     ->where('course_id', $course_id)
                    //     ->where('classroom_date', $today)
                    //     ->where('noti_type', 'open_course_student')
                    //     ->count();

                    //if($count_student_noti == 0){
                        $fullName = $student_fullname;
                        $password = 'ap';
                        $redirect = 'true';

                        //generate checksum
                        $checksum = sha1('joinfullName='.urlencode($fullName).'&meetingID='.$meetingID.'&password='.$password.'&redirect='.$redirect.$shared_secret);

                        $join_attendee_url[$value['student_email']] = $server.'api/join?fullName='.urlencode($fullName).'&meetingID='.$meetingID.'&password='.$password.'&redirect='.$redirect.'&checksum='.$checksum;

                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $join_attendee_url[$value['student_email']],
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_TIMEOUT => 30000,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "GET",
                            CURLOPT_HTTPHEADER => array(
                                // Set Here Your Requesred Headers
                                'Content-Type: application/json',
                            ),
                        ));
                        $response = curl_exec($curl);
                        $err = curl_error($curl);
                        curl_close($curl);

                        //insert noti to student
                        // $student_noti = new MemberNotification();
                        $student_noti['course_id'] = $classroom->course_id;
                        $student_noti['classroom_date'] = $classroom_date;
                        $student_noti['classroom_name'] = $course_name;
                        $student_noti['classroom_time_start'] = $classroom_time_start;
                        $student_noti['classroom_time_end'] = $classroom_time_end;
                        $student_noti['classroom_url'] = $join_attendee_url[$value['student_email']];
                        $student_noti['member_id'] = $student_id;
                        $student_noti['member_fullname'] = $student_fullname;
                        $student_noti['teacher_id'] = $teacher_id;
                        $student_noti['teacher_fullname'] = $teacher_fullname;
                        $student_noti['noti_type'] = 'open_course_student';
                        $student_noti['noti_status'] = '0';

                        $noti_id_student = MemberNotification::create($student_noti);

                        // $student_noti->save();

                        //send noti to student
                        sendMemberNoti($student_id);


                        //send email to student
                        $url_inclass = $hostname.'/classroom/check/'.$noti_id_student->_id;
                        $subject = 'Suksa Online : เปิดคอร์ส '.$course_name.' เรียบร้อยแล้ว';
                        $from_name = 'Suksa Online';
                        $from_email = 'noreply@suksa.online';
                        $to_name = $student_fullname;
                        $to_email = $student_email;
                        $description = '';
                        $data = array(
                            'name'=>$student_fullname,
                            "course" => $course_name,
                            "teacher_fullname" => $teacher_fullname,
                            "url" => $url_inclass,
                            "start_date" => $classroom_date,
                            "start_time" => $classroom_time_start,
                            "end_time" => $classroom_time_end
                            // "start_date" => changeDate($classroom_date, 'full_date', 'th'),
                            // "start_time" => substr($classroom_time_start,0,5),
                            // "end_time" => substr($classroom_time_end,0,5)
                        );

                        //insert queue send email to student
                        $queue_email_student = new QueueEmail();
                        $queue_email_student->email_type = 'send_email_classroom';
                        $queue_email_student->data = $data;
                        $queue_email_student->subject = $subject;
                        $queue_email_student->from_name = $from_name;
                        $queue_email_student->from_email = $from_email;
                        $queue_email_student->to_name = $to_name;
                        $queue_email_student->to_email = $to_email;
                        $queue_email_student->queue_status = '0';
                        $queue_email_student->save();

                        // Mail::send('frontend.send_email_classroom', $data, function($message) use ($subject, $from_name, $from_email, $to_name, $to_email) {
                        //     $message->from($from_email, $from_name);
                        //     $message->to($to_email, $to_name);
                        //     $message->subject($subject);
                        // });

                        $url[$classroom->course_id][$i] = $join_attendee_url[$value['student_email']];



                    //}

                    $i++;
                }

                //update classroom_server
                $classroom->classroom_server = $classroom_server;
                $classroom->save();
            }

        }
    }

    public function closeClassRoom()
    {
        $today = date('Y-m-d');
        $after_close_class_min = 10; //minute
        $strtotime_after_close_class = $after_close_class_min * 60;
        $current_time = date('H:i:s');
        $set_close_time = date('H:i', strtotime($current_time)-$strtotime_after_close_class);
        $set_close_time_down5 = date('H:i', strtotime($set_close_time)-300);

        $classrooms = Classroom::where('classroom_status', '1')
                    ->where('classroom_date', 'like', '%'.$today.'%')
                    ->whereBetween('classroom_time_end', [$set_close_time_down5.':00', $set_close_time.':00'])
                    ->get();

        // Test::insert([
        //         'start' => $set_close_time_down5,
        //         'end' => $set_close_time
        //     ]);

        $url = array();

        foreach ($classrooms as $classroom) {
            $classroom_update = Classroom::where('classroom_status', '!=' ,'2')
                        ->where('course_id', '=', $classroom->course_id)
                        ->first();

            $classroom_update->classroom_status = '2';

            //select bbb server
            $bbb_setting = BBBSettings::first();

            if($classroom_update->save()) {
                //call api end
                if($classroom->classroom_server == 'channel1.suksalive.com'){
                    $server = $bbb_setting->bbb_url_1;
                    $shared_secret = $bbb_setting->bbb_shared_secret_1;
                }
                else if($classroom->classroom_server == 'channel2.suksalive.com'){
                    $server = $bbb_setting->bbb_url_2;
                    $shared_secret = $bbb_setting->bbb_shared_secret_2;
                }
                else if($classroom->classroom_server == 'channel3.suksalive.com'){
                    $server = $bbb_setting->bbb_url_3;
                    $shared_secret = $bbb_setting->bbb_shared_secret_3;
                }
                else if($classroom->classroom_server == 'channel4.suksalive.com'){
                    $server = $bbb_setting->bbb_url_4;
                    $shared_secret = $bbb_setting->bbb_shared_secret_4;
                }
                else{ //suksalive.com
                    $server = $bbb_setting->bbb_url_5;
                    $shared_secret = $bbb_setting->bbb_shared_secret_5;
                }

                // $server = 'https://suksalive.com/bigbluebutton/';
                // $shared_secret = 'cacvGZrcHNxK2RXsYB9TQUH7iPHvNa0GxuIjAlmPUM';

                $meetingID = 'suksa-'.$classroom_update->course_id;
                $password = 'mp';

                //generate checksum
                $checksum = sha1('endmeetingID='.$meetingID.'&password='.$password.$shared_secret);

                $end_url = $server.'api/end?meetingID='.$meetingID.'&password='.$password.'&checksum='.$checksum;

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $end_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_TIMEOUT => 30000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        // Set Here Your Requesred Headers
                        'Content-Type: application/json',
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if($err) {
                    //return Redirect::route('backend/classroom')->withInput()->with('error', 'CURL Error : '.$err);
                }
                else {
                    //return redirect('http://suksa.online/');

                    $course = Course::where('_id', '=', $classroom_update->course_id)->first();
                    $course_id = $classroom_update->course_id;
                    $course_name = $course->course_name;
                    $teacher_id = $course->member_id;
                    $teacher_fullname = $course->member_fname.' '.$course->member_lname;

                    $last_time = date('H:i:s', strtotime($course->last_course_date_time));
                    $last_date = $course->course_date[count($course->course_date)-1]['date'];

                    //เช็คแต่ละคอร์สว่าเป็นวันที่เปิดสอนวันสุดท้ายหรือไม่
                    //ถ้าใช่ ให้ส่ง noti หานักเรียนเพื่อทำการประเมิน
                    if(($today == $last_date) && (time() >= strtotime($last_time))){
                        $i = 1;

                        //insert noti to teacher
                        $teacher_noti = new MemberNotification();
                        $teacher_noti->course_id = $course_id;
                        $teacher_noti->classroom_date = $classroom->classroom_date;
                        $teacher_noti->classroom_name = $classroom->classroom_name;
                        $teacher_noti->classroom_time_start = $classroom->classroom_time_start;
                        $teacher_noti->classroom_time_end = $classroom->classroom_time_end;
                        $teacher_noti->member_id = $teacher_id;
                        $teacher_noti->member_fullname = $teacher_fullname;
                        $teacher_noti->noti_type = 'sent_student_rating';
                        $teacher_noti->noti_status = '0';
                        $teacher_noti->save();

                        //send noti to teacher
                        sendMemberNoti($teacher_id);

                        foreach ($classroom->classroom_student as $key => $value) {
                            $student_id = $value['student_id'];
                            $student_fullname = $value['student_fname'].' '.$value['student_lname'];
                            $student_email = $value['student_email'];

                            //check student data before insert
                            $count_student_noti = MemberNotification::where('member_id', $student_id)
                                ->where('course_id', $course_id)
                                ->where('classroom_date', $today)
                                ->where('noti_type', 'sent_teacher_rating')
                                ->count();

                            if($count_student_noti == 0){
                                //insert teacher_rating
                                $teacher_rating = new TeacherRating();
                                $teacher_rating->course_id = $course_id;
                                $teacher_rating->teacher_id = $teacher_id;
                                $teacher_rating->member_id = $student_id;
                                $teacher_rating->rating = array();
                                $teacher_rating->average_rating = '0';
                                $teacher_rating->rating_status = '0';
                                $teacher_rating->save();

                                //insert student_rating
                                $student_rating = new StudentRating();
                                $student_rating->course_id = $course_id;
                                $student_rating->teacher_id = $teacher_id;
                                $student_rating->member_id = $student_id;
                                $student_rating->rating = array();
                                $student_rating->average_rating = '0';
                                $student_rating->recommend = '';
                                $student_rating->rating_status = '0';
                                $student_rating->save();

                                //insert noti to student
                                $student_noti = new MemberNotification();
                                $student_noti->course_id = $course_id;
                                $student_noti->classroom_date = $classroom->classroom_date;
                                $student_noti->classroom_name = $classroom->classroom_name;
                                $student_noti->classroom_time_start = $classroom->classroom_time_start;
                                $student_noti->classroom_time_end = $classroom->classroom_time_end;
                                $student_noti->member_id = $student_id;
                                $student_noti->member_fullname = $student_fullname;
                                $student_noti->teacher_id = $teacher_id;
                                $student_noti->teacher_fullname = $teacher_fullname;
                                $student_noti->noti_type = 'sent_teacher_rating';
                                $student_noti->noti_status = '0';
                                $student_noti->save();

                                //send noti to student
                                sendMemberNoti($student_id);
                            }

                            $i++;
                        }
                    }


                }


            } else {
                //return Redirect::route('backend/classroom')->withInput()->with('error', 'Log out ไม่สำเร็จ');
            }
        }
    }

    public function postTestCourse()
    {
        $today = date('Y-m-d');
        $after_close_class_min = 5; //minute
        $strtotime_after_close_class = $after_close_class_min * 60;
        $current_time = date('H:i:s');
        $set_close_time = date('H:i', strtotime($current_time)-$strtotime_after_close_class);
        $set_close_time_down5 = date('H:i', strtotime($set_close_time)-300);

        $classrooms = Classroom::where('classroom_status', '1')
                    ->where('classroom_date', 'like', '%'.$today.'%')
                    ->where('classroom_category','School')
                    ->whereBetween('classroom_time_end', [$set_close_time_down5.':00', $set_close_time.':00'])
                    ->get();

        foreach ($classrooms as $classroom) {
            $course = Course::where('_id', '=', $classroom->course_id)->first();
            $course_id = $classroom->course_id;
            $course_name = $course->course_name;
            $teacher_id = $course->member_id;
            $teacher_fullname = $course->member_fname.' '.$course->member_lname;

            $last_time = date('H:i:s', strtotime($course->last_course_date_time));
            $last_date = $course->course_date[count($course->course_date)-1]['date'];
            //เช็คแต่ละคอร์สว่าเป็นวันที่เปิดสอนวันสุดท้ายหรือไม่
            //ถ้าใช่ ให้ส่ง noti หานักเรียนเพื่อทำการประเมิน
            if(($today == $last_date)){
                $i = 1;
                foreach ($classroom->classroom_student as $key => $value) {
                    if($value['student_checkin'] == 1){
                        $student_id = $value['student_id'];
                        $student_fullname = $value['student_fname'].' '.$value['student_lname'];
                        $student_email = $value['student_email'];
                        //check student data before insert
                        $count_student_noti = MemberNotification::where('member_id', $student_id)
                            ->where('course_id', $course_id)
                            ->where('classroom_date', $today)
                            ->where('noti_type', 'post_test_course')
                            ->count();
                        // dd($count_student_noti);
                        if($count_student_noti == 0){
                            //insert noti to student
                            $student_noti = new MemberNotification();
                            $student_noti->course_id = $course_id;
                            $student_noti->classroom_date = $classroom->classroom_date;
                            $student_noti->classroom_name = $classroom->classroom_name;
                            $student_noti->classroom_time_start = $classroom->classroom_time_start;
                            $student_noti->classroom_time_end = $classroom->classroom_time_end;
                            $student_noti->member_id = $student_id;
                            $student_noti->member_fullname = $student_fullname;
                            $student_noti->teacher_id = $teacher_id;
                            $student_noti->teacher_fullname = $teacher_fullname;
                            $student_noti->noti_type = 'post_test_course';
                            $student_noti->noti_status = '0';
                            $student_noti->save();

                            //send noti to student
                            sendMemberNoti($student_id);
                        }
                    }
                    $i++;
                }
            }
        }
    }


    public function checkPrivateClassRoomCronJob()
    {
        set_time_limit(3000);

        $today = date('Y-m-d');
        $before_open_class_min = 15; //minute
        $strtotime_before_open_class = $before_open_class_min * 60;
        $current_time = date('H:i:s');
        $set_open_time = date('H:i', strtotime($current_time)+$strtotime_before_open_class);
        $set_open_time_down5 = date('H:i', strtotime($set_open_time)-300);

        $classrooms = Classroom::where('classroom_status', '=' ,'0')
                    ->where('classroom_category', 'Private')
                    ->where('classroom_date', 'like', '%'.$today.'%')
                    ->whereBetween('classroom_time_start', [$set_open_time_down5.':00', $set_open_time.':00'])
                    ->offset(0)
                    ->limit(10)
                    ->get();
        foreach ($classrooms as $classroom) {

            $course = Course::where('_id', '=', $classroom->course_id)->first();
            $count_course_student = count($course->course_student);
            $count_classroom_student = count($classroom->classroom_student);

            $percent_open_course = 50;
            //$count_course_student = 15;
            //$count_classroom_student = 8;

            $count_percent_open_course = ($count_course_student * $percent_open_course) / 100;

            //< 50%
            //คืนเงิน
            if($count_classroom_student < $count_percent_open_course){
            //ถ้าจำนวนนักเรียนใน tb classroom น้อยกว่า 50% ของจำนวนนักเรียนใน tb course

                $data_log = array();

                //คืน coins ให้กับนักเรียนที่สมัครคอร์สนี้มาแล้ว
                foreach ($classroom->classroom_student as $key => $item) {
                    $student = Member::where('_id', '=', $item['student_id'])
                           ->first();
                    $student->member_coins = number_format(str_replace(",","",$student->member_coins)+$course->course_price);
                    $student->save();


                    $data_log = ([
                        'member_id' => $student->_id,
                        'member_fname' => $student->member_fname,
                        'member_lname' => $student->member_lname,
                        'event' => 'return_coins',
                        'ref_id' => $classroom->course_id,
                        'ref_name' => $classroom->classroom_name,

                        'coin_date' => date('Y-m-d'),
                        'coin_time' => date('H:i:s'),
                        'coin_number' => number_format($course->course_price),
                        'coin_status' => '1',
                    ]);
                    CoinsLog::create($data_log);

                }

                $teacher = Member::where('_id', '=', $course->member_id)
                           ->first();

                // $all_coins = $course->course_price * count($classroom->classroom_student);

                //หัก coins อาจารย์ที่สอนคอร์สนั้น
                // $teacher = Member::where('_id', '=', $course->member_id)
                //            ->first();
                // $teacher->member_coins = number_format(str_replace(",","",$teacher->member_coins)-$course->course_price);
                // $teacher->save();

                // $data_log = ([
                //     'member_id' => $teacher->_id,
                //     'member_fname' => $teacher->member_fname,
                //     'member_lname' => $teacher->member_lname,
                //     'event' => 'deduct_coins',
                //     'ref_id' => $classroom->course_id,
                //     'ref_name' => $classroom->classroom_name,

                //     'coin_date' => date('Y-m-d'),
                //     'coin_time' => date('H:i:s'),
                //     //'coin_number' => number_format($all_coins),
                //     'coin_number' => number_format($all_coins),
                //     'coin_status' => '1',
                // ]);
                // CoinsLog::create($data_log);


                //update classroom_status = 3 (cancel)
                $classroom_course = Classroom::where('course_id', '=', $course->id)->first();
                $classroom_course->classroom_status = "3";
                $classroom_course->save();

                $course_price = $course->course_price;
                $classroom_date = $classroom->classroom_date;
                $teacher_id = $classroom->classroom_teacher['teacher_id'];
                $teacher_fullname = $classroom->classroom_teacher['teacher_fname'].' '.$classroom->classroom_teacher['teacher_lname'];
                $teacher_email = $classroom->classroom_teacher['teacher_email'];
                $course_id = $classroom->course_id;
                $course_name = $classroom->classroom_name;
                $classroom_time_start = $classroom->classroom_time_start;
                $classroom_time_end = $classroom->classroom_time_end;

                //update course_status = cancel
                $course->course_status = "cancel";
                $course->save();

                //check teacher data before insert
                $count_teacher_noti = MemberNotification::where('teacher_id', $teacher_id)
                                    ->where('course_id', $course_id)
                                    ->where('classroom_date', $today)
                                    ->where('noti_type', 'cancel_course_teacher')
                                    ->count();

                if($count_teacher_noti == 0){

                    //send email to teacher
                    $subject = 'Suksa Online : เปิดคอร์ส '.$course_name.' ไม่สำเร็จ';
                    $from_name = 'Suksa Online';
                    $from_email = 'noreply@suksa.online';
                    $to_name = $teacher_fullname;
                    $to_email = $teacher_email;
                    $description = '';
                    $data = array(
                        'name'=>$teacher_fullname,
                        "course" => $course_name
                    );

                    //insert queue send email to teacher
                    $queue_email_teacher = new QueueEmail();
                    $queue_email_teacher->email_type = 'send_email_cancel_private_classroom';
                    $queue_email_teacher->data = $data;
                    $queue_email_teacher->subject = $subject;
                    $queue_email_teacher->from_name = $from_name;
                    $queue_email_teacher->from_email = $from_email;
                    $queue_email_teacher->to_name = $to_name;
                    $queue_email_teacher->to_email = $to_email;
                    $queue_email_teacher->queue_status = '0';
                    $queue_email_teacher->save();


                    //insert noti to teacher
                    $teacher_noti = new MemberNotification();
                    $teacher_noti->course_id = $course_id;
                    $teacher_noti->classroom_date = $classroom_date;
                    $teacher_noti->classroom_name = $course_name;
                    $teacher_noti->classroom_time_start = $classroom_time_start;
                    $teacher_noti->classroom_time_end = $classroom_time_end;
                    $teacher_noti->member_id = $teacher_id;
                    $teacher_noti->member_fullname = $teacher_fullname;
                    $teacher_noti->teacher_id = $teacher_id;
                    $teacher_noti->teacher_fullname = $teacher_fullname;
                    $teacher_noti->sum_coins = $teacher->member_coins;
                    $teacher_noti->noti_type = 'cancel_course_teacher';
                    $teacher_noti->noti_status = '0';
                    $teacher_noti->save();

                    //send noti to teacher
                    sendMemberNoti($teacher_id);

                }

                $i = 1;

                foreach ($classroom->classroom_student as $key => $value) {
                    $student_id = $value['student_id'];
                    $student_fullname = $value['student_fname'].' '.$value['student_lname'];
                    $student_email = $value['student_email'];

                    $student = Member::where('_id', '=', $value['student_id'])
                                ->first();

                    //check student data before insert
                    $count_student_noti = MemberNotification::where('member_id', $student_id)
                        ->where('course_id', $course_id)
                        ->where('classroom_date', $today)
                        ->where('noti_type', 'cancel_course_student')
                        ->count();

                    if($count_student_noti == 0){

                        //send email to student
                        $subject = 'Suksa Online : เปิดคอร์ส '.$course_name.' ไม่สำเร็จ';
                        $from_name = 'Suksa Online';
                        $from_email = 'noreply@suksa.online';
                        $to_name = $student_fullname;
                        $to_email = $student_email;
                        $description = '';
                        $data = array(
                            'name'=>$student_fullname,
                            "course" => $course_name
                        );

                        //insert queue send email to student
                        $queue_email_student = new QueueEmail();
                        $queue_email_student->email_type = 'send_email_cancel_private_classroom';
                        $queue_email_student->data = $data;
                        $queue_email_student->subject = $subject;
                        $queue_email_student->from_name = $from_name;
                        $queue_email_student->from_email = $from_email;
                        $queue_email_student->to_name = $to_name;
                        $queue_email_student->to_email = $to_email;
                        $queue_email_student->queue_status = '0';
                        $queue_email_student->save();


                        //insert noti to student
                        $student_noti = new MemberNotification();
                        $student_noti->course_id = $course_id;
                        $student_noti->classroom_date = $classroom_date;
                        $student_noti->classroom_name = $course_name;
                        $student_noti->classroom_time_start = $classroom_time_start;
                        $student_noti->classroom_time_end = $classroom_time_end;
                        $student_noti->member_id = $student_id;
                        $student_noti->member_fullname = $student_fullname;
                        $student_noti->teacher_id = $teacher_id;
                        $student_noti->teacher_fullname = $teacher_fullname;
                        $student_noti->sum_coins = $student->member_coins;
                        $student_noti->noti_type = 'cancel_course_student';
                        $student_noti->noti_status = '0';
                        $student_noti->course_price = $course_price;
                        $student_noti->save();



                        //send noti to student
                        sendMemberNoti($student_id);
                    }

                    $i++;
                }
            }

        }
    }


    public function checkPrivateClassRoomRealTimeCronJob()
    {
        set_time_limit(3000);

        $today = date('Y-m-d');
        $before_open_class_min = 3; //minute
        $strtotime_before_open_class = $before_open_class_min * 60;
        $current_time = date('H:i:s');
        $set_open_time = date('H:i', strtotime($current_time)+$strtotime_before_open_class);
        $set_open_time_down5 = date('H:i', strtotime($set_open_time)-600);

        $classrooms = Classroom::where('classroom_status', '=' ,'0')
                    ->where('classroom_category', 'Private')
                    ->where('classroom_date', 'like', '%'.$today.'%')
                    ->whereBetween('classroom_time_start', [$current_time, $set_open_time.':00'])
                    ->offset(0)
                    ->limit(10)
                    ->get();

        foreach ($classrooms as $classroom) {

            $course = Course::where('_id', '=', $classroom->course_id)->first();
            $count_course_student = count($course->course_student);
            $count_classroom_student = count($classroom->classroom_student);

            // Test::insert([
            //     'course_id' => $classroom->course_id,
            //     'course_name' => $classroom->classroom_name,
            //     'course_student' => $count_course_student,
            //     'classroom_student' => $count_classroom_student
            // ]);

            //if($count_course_student != $count_classroom_student){
            //ถ้าจำนวนนักเรียนใน tb course ไม่เท่ากับ จำนวนใน tb classroom

            $percent_open_course = 50;
            //$count_course_student = 15;
            //$count_classroom_student = 8;

            $count_percent_open_course = ($count_course_student * $percent_open_course) / 100;

            //< 50%
            //คืนเงิน
            if($count_classroom_student < $count_percent_open_course){
            //ถ้าจำนวนนักเรียนใน tb classroom น้อยกว่า 50% ของจำนวนนักเรียนใน tb course

                //คืน coins ให้กับนักเรียนที่สมัครคอร์สนี้มาแล้ว
                foreach ($classroom->classroom_student as $key => $item) {
                    $student = Member::where('_id', '=', $item['student_id'])
                           ->first();
                    $student->member_coins = number_format(str_replace(",","",$student->member_coins)+$course->course_price);
                    $student->save();
                }

                //update classroom_status = 3 (cancel)
                $classroom_course = Classroom::where('course_id', '=', $course->id)->get();
                foreach ($classroom_course as $key => $value) {
                    $value->classroom_status = "3";
                    $value->save();
                }
                $course_price = $course->course_price;
                $classroom_date = $classroom->classroom_date;
                $teacher_id = $classroom->classroom_teacher['teacher_id'];
                $teacher_fullname = $classroom->classroom_teacher['teacher_fname'].' '.$classroom->classroom_teacher['teacher_lname'];
                $teacher_email = $classroom->classroom_teacher['teacher_email'];
                $course_id = $classroom->course_id;
                $course_name = $classroom->classroom_name;
                $classroom_time_start = $classroom->classroom_time_start;
                $classroom_time_end = $classroom->classroom_time_end;

                //update course_status = cancel
                $course->course_status = "cancel";
                $course->save();

                //check teacher data before insert
                $count_teacher_noti = MemberNotification::where('teacher_id', $teacher_id)
                    ->where('course_id', $course_id)
                    ->where('classroom_date', $today)
                    ->where('noti_type', 'cancel_course_teacher')
                    ->count();

                if($count_teacher_noti == 0){
                    //send email to teacher
                    $subject = 'Suksa Online : เปิดคอร์ส '.$course_name.' ไม่สำเร็จ';
                    $from_name = 'Suksa Online';
                    $from_email = 'noreply@suksa.online';
                    $to_name = $teacher_fullname;
                    $to_email = $teacher_email;
                    $description = '';
                    $data = array(
                        'name'=>$teacher_fullname,
                        "course" => $course_name
                    );

                    //insert queue send email to teacher
                    $queue_email_teacher = new QueueEmail();
                    $queue_email_teacher->email_type = 'send_email_cancel_private_classroom';
                    $queue_email_teacher->data = $data;
                    $queue_email_teacher->subject = $subject;
                    $queue_email_teacher->from_name = $from_name;
                    $queue_email_teacher->from_email = $from_email;
                    $queue_email_teacher->to_name = $to_name;
                    $queue_email_teacher->to_email = $to_email;
                    $queue_email_teacher->queue_status = '0';
                    $queue_email_teacher->save();


                    //insert noti to teacher
                    $teacher_noti = new MemberNotification();
                    $teacher_noti->course_id = $course_id;
                    $teacher_noti->classroom_date = $classroom_date;
                    $teacher_noti->classroom_name = $course_name;
                    $teacher_noti->classroom_time_start = $classroom_time_start;
                    $teacher_noti->classroom_time_end = $classroom_time_end;
                    $teacher_noti->member_id = $teacher_id;
                    $teacher_noti->member_fullname = $teacher_fullname;
                    $teacher_noti->teacher_id = $teacher_id;
                    $teacher_noti->teacher_fullname = $teacher_fullname;
                    $teacher_noti->noti_type = 'cancel_course_teacher';
                    $teacher_noti->noti_status = '0';
                    $teacher_noti->save();

                    //send noti to teacher
                    sendMemberNoti($teacher_id);
                }

                $i = 1;

                foreach ($classroom->classroom_student as $key => $value) {
                    $student_id = $value['student_id'];
                    $student_fullname = $value['student_fname'].' '.$value['student_lname'];
                    $student_email = $value['student_email'];

                    //check student data before insert
                    $count_student_noti = MemberNotification::where('member_id', $student_id)
                        ->where('course_id', $course_id)
                        ->where('classroom_date', $today)
                        ->where('noti_type', 'cancel_course_student')
                        ->count();

                    if($count_student_noti == 0){

                        //send email to student
                        $subject = 'Suksa Online : เปิดคอร์ส '.$course_name.' ไม่สำเร็จ';
                        $from_name = 'Suksa Online';
                        $from_email = 'noreply@suksa.online';
                        $to_name = $student_fullname;
                        $to_email = $student_email;
                        $description = '';
                        $data = array(
                            'name'=>$student_fullname,
                            "course" => $course_name
                        );

                        //insert queue send email to student
                        $queue_email_student = new QueueEmail();
                        $queue_email_student->email_type = 'send_email_cancel_private_classroom';
                        $queue_email_student->data = $data;
                        $queue_email_student->subject = $subject;
                        $queue_email_student->from_name = $from_name;
                        $queue_email_student->from_email = $from_email;
                        $queue_email_student->to_name = $to_name;
                        $queue_email_student->to_email = $to_email;
                        $queue_email_student->queue_status = '0';
                        $queue_email_student->save();


                        //insert noti to student
                        $student_noti = new MemberNotification();
                        $student_noti->course_id = $course_id;
                        $student_noti->classroom_date = $classroom_date;
                        $student_noti->classroom_name = $course_name;
                        $student_noti->classroom_time_start = $classroom_time_start;
                        $student_noti->classroom_time_end = $classroom_time_end;
                        $student_noti->member_id = $student_id;
                        $student_noti->member_fullname = $student_fullname;
                        $student_noti->teacher_id = $teacher_id;
                        $student_noti->teacher_fullname = $teacher_fullname;
                        $student_noti->noti_type = 'cancel_course_student';
                        $student_noti->noti_status = '0';
                        $student_noti->course_price = $course_price;
                        $student_noti->save();

                        //send noti to student
                        sendMemberNoti($student_id);
                    }

                    $i++;
                }

            }

        }
    }


    public function checkClassRoom(){
        set_time_limit(3000);

        $today = date('Y-m-d');
        $before_open_class_min = 10; //minute
        $strtotime_before_open_class = $before_open_class_min * 60;
        $current_time = date('H:i:s');
        $set_open_time = date('H:i', strtotime($current_time)+$strtotime_before_open_class);
        $set_open_time_down5 = date('H:i', strtotime($set_open_time)-300);

        $courses = Course::where('course_status', '=' ,'open')
                    ->where('course_category', 'Private')
                    ->where('course_date_start', 'like', '%'.$today.'%')
                    ->whereBetween('course_time_start', [$set_open_time_down5.':00', $set_open_time.':00'])
                    ->offset(0)
                    ->limit(10)
                    ->get();
        foreach ($courses as  $course) {
            if($course){
                $classroom = Classroom::where('course_id', $course->_id)->first();
                if(!$classroom){

                    $teacher_id = $course->member_id;
                    $teacher_fullname = $course->member_fname.' '.$course->member_lname;
                    $teacher_email = $course->member_email;
                    $course_id = $course->id;
                    $course_name = $course->course_name;
                    $course_date = $course->course_date;
                    $course_time_start = $course->course_time_start;
                    $course_time_end = $course->course_time_end;


                    //check teacher data before insert
                    $count_teacher_noti = MemberNotification::where('teacher_id', $teacher_id)
                        ->where('course_id', $course_id)
                        ->where('classroom_date', $today)
                        ->where('noti_type', 'cancel_course_teacher_not')
                        ->count();

                    if($count_teacher_noti == 0){
                        //send email to teacher
                        $subject = 'Suksa Online : เปิดคอร์ส '.$course_name.' ไม่สำเร็จ';
                        $from_name = 'Suksa Online';
                        $from_email = 'noreply@suksa.online';
                        $to_name = $teacher_fullname;
                        $to_email = $teacher_email;
                        $description = '';
                        $data = array(
                            'name'=>$teacher_fullname,
                            "course" => $course_name
                        );

                        //insert queue send email to teacher
                        $queue_email_teacher = new QueueEmail();
                        $queue_email_teacher->email_type = 'send_email_cancel_private_classroom';
                        $queue_email_teacher->data = $data;
                        $queue_email_teacher->subject = $subject;
                        $queue_email_teacher->from_name = $from_name;
                        $queue_email_teacher->from_email = $from_email;
                        $queue_email_teacher->to_name = $to_name;
                        $queue_email_teacher->to_email = $to_email;
                        $queue_email_teacher->queue_status = '0';
                        $queue_email_teacher->save();


                        //insert noti to teacher
                        $teacher_noti = new MemberNotification();
                        $teacher_noti->course_id = $course_id;
                        $teacher_noti->classroom_date = $course_date;
                        $teacher_noti->classroom_name = $course_name;
                        $teacher_noti->classroom_time_start = $course_time_start;
                        $teacher_noti->classroom_time_end = $course_time_end;
                        $teacher_noti->member_id = $teacher_id;
                        $teacher_noti->member_fullname = $teacher_fullname;
                        $teacher_noti->teacher_id = $teacher_id;
                        $teacher_noti->teacher_fullname = $teacher_fullname;
                        $teacher_noti->noti_type = 'cancel_course_teacher_not';
                        $teacher_noti->noti_status = '0';
                        $teacher_noti->save();

                        //send noti to teacher
                        sendMemberNoti($teacher_id);
                    }

                    //update course_status = cancel
                    $course->course_status = "cancel";
                    $course->save();

                }
            }
        }
    }

    public function setCancelCourse(){
        $today = date('Y-m-d');
        $current_time = date('H:i:s');

        // $courses = Course::where('course_status', '=' ,'open')

        $courses = Course::where('course_status', '!=' ,'cancel')
                    ->where('course_category', 'Public')
                    ->where('course_status', '!=' ,'delete')
                    ->where('course_date_start', 'like', '%'.$today.'%')
                    ->where('course_time_start', '<', $current_time)
                    ->get();

        foreach ($courses as $course) {
            //เช็คว่ามีคนสมัครเรียนแล้วหรือยัง
            $count_classroom = Classroom::where('course_id', $course->_id)->count();

            //ถ้ายังไม่เคยมีคนสมัคร
            if($count_classroom == 0){
                //ให้อัพเดตสถานะคอร์สเป็น cancel
                $course = Course::where('_id', '=', $course->_id)->first();
                $course->course_status = 'cancel';
                $course->save();
            }
        }
    }

    public function updateClassRoomLink(){
        $classroom = Classroom::where('classroom_status', '!=' ,'0')
                    ->orderBy('classroom_date','desc')->get();

        $url = array();

        //select bbb server
        $bbb_setting = BBBSettings::first();

        libxml_use_internal_errors(true);

        $server_amount = $bbb_setting->server_amount;
        $camera_per_server = $bbb_setting->camera_per_server;
        $hostname = $bbb_setting->host_name;

        foreach ($classroom as $class) {
            $course_id = $class->course_id;
            $course_name = $class->classroom_name;

            // ถ้าไม่มีค่า classroom_link ให้ gen link ใหม่โดยใช้ server เดิม
            if(isset($class->classroom_server) && !isset($class->classroom_link)){
                if($class->classroom_server == 'channel1.suksalive.com'){
                    $server = $bbb_setting->bbb_url_1;
                    $shared_secret = $bbb_setting->bbb_shared_secret_1;
                }
                else if($class->classroom_server == 'channel2.suksalive.com'){
                    $server = $bbb_setting->bbb_url_2;
                    $shared_secret = $bbb_setting->bbb_shared_secret_2;
                }
                else if($class->classroom_server == 'channel3.suksalive.com'){
                    $server = $bbb_setting->bbb_url_3;
                    $shared_secret = $bbb_setting->bbb_shared_secret_3;
                }
                else if($class->classroom_server == 'channel4.suksalive.com'){
                    $server = $bbb_setting->bbb_url_4;
                    $shared_secret = $bbb_setting->bbb_shared_secret_4;
                }
                else{ //suksalive.com
                    $server = $bbb_setting->bbb_url_5;
                    $shared_secret = $bbb_setting->bbb_shared_secret_5;
                }


                $student_fullname = 'Suksa Admin';
                $student_email = 'suksa_admin@edispro.com';

                $meetingID = 'suksa-'.$course_id;
                $fullName = $student_fullname;
                $password = 'ap';
                $redirect = 'true';

                //generate checksum
                $checksum = sha1('joinfullName='.urlencode($fullName).'&meetingID='.$meetingID.'&password='.$password.'&redirect='.$redirect.$shared_secret);

                $join_attendee_url = $server.'api/join?fullName='.urlencode($fullName).'&meetingID='.$meetingID.'&password='.$password.'&redirect='.$redirect.'&checksum='.$checksum;

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $join_attendee_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_TIMEOUT => 30000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        // Set Here Your Requesred Headers
                        'Content-Type: application/json',
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                $class->classroom_link = $join_attendee_url;
                $class->save();
            }
        }
    }

    public function updateClassRoomLinkMember()
    {
        $today = date('Y-m-d');
        $classrooms = Classroom::where('classroom_status', '9')
                    ->where('classroom_date', 'like', '%'.$today.'%')
                    ->offset(0)
                    ->limit(7)
                    ->get();
        foreach ($classrooms as $classroom) {
            //update classroom_status
            $classroom->classroom_status = '1';
            $classroom->update();
            $classroom->save();
            $bbb_setting = BBBSettings::first();
            if($classroom->classroom_server == 'channel1.suksalive.com')
            {
                $server = $bbb_setting->bbb_url_1;
                $shared_secret = $bbb_setting->bbb_shared_secret_1;
            }else if($classroom->classroom_server == 'channel2.suksalive.com'){
                $server = $bbb_setting->bbb_url_2;
                $shared_secret = $bbb_setting->bbb_shared_secret_2;
            }else if($classroom->classroom_server == 'channel3.suksalive.com'){
                $server = $bbb_setting->bbb_url_3;
                $shared_secret = $bbb_setting->bbb_shared_secret_3;
            }else if($classroom->classroom_server == 'channel4.suksalive.com'){
                $server = $bbb_setting->bbb_url_4;
                $shared_secret = $bbb_setting->bbb_shared_secret_4;
            }else{
                $server = $bbb_setting->bbb_url_5;
                $shared_secret = $bbb_setting->bbb_shared_secret_5;
            }
            $hostname = $bbb_setting->host_name;
            $course_id = $classroom->course_id;
            $teacher_id = $classroom->classroom_teacher['teacher_id'];
            $teacher_fullname = $classroom->classroom_teacher['teacher_fname'].' '.$classroom->classroom_teacher['teacher_lname'];
            $teacher_email = $classroom->classroom_teacher['teacher_email'];
            $course_name = $classroom->classroom_name;
            $classroom_date = $classroom->classroom_date;
            $classroom_time_start = $classroom->classroom_time_start;
            $classroom_time_end = $classroom->classroom_time_end;
            $meetingID = 'suksa-'.$classroom->course_id;
            // noti teacher
                $fullName = $teacher_fullname;
                $password = 'mp';
                $redirect = 'true';
                $checksum = sha1('joinfullName='.urlencode($fullName).'&meetingID='.$meetingID.'&password='.$password.'&redirect='.$redirect.$shared_secret);
                $join_moderator_url = $server.'api/join?fullName='.urlencode($fullName).'&meetingID='.$meetingID.'&password='.$password.'&redirect='.$redirect.'&checksum='.$checksum;
                $check_noti_teacher = MemberNotification::where('noti_type','open_course_teacher')->where('id_user',$teacher_id)->where('course_id',$course_id)->count();
                if($check_noti_teacher == 0)
                {
                        $create_noti['noti_status'] = '0';
                        $create_noti['noti_type'] = 'open_course_teacher';
                        $create_noti['id_user'] = $teacher_id;
                        $create_noti['course_id'] = $course_id;
                        $create_noti['check_update'] = '0';
                        $noti_id_teacher = MemberNotification::create($create_noti);
                        $url_inclass_teacher = $hostname.'/classroom/check/'.$noti_id_teacher->_id;

                        $subject = 'Suksa Online : เปิดคอร์ส '.$course_name.' เรียบร้อยแล้ว';
                        $from_name = 'Suksa Online';
                        $from_email = 'noreply@suksa.online';
                        $to_name = $teacher_fullname;
                        $to_email = $teacher_email;
                        $description = '';
                        $data = array(
                            'name'=>$teacher_fullname,
                            "course" => $course_name,
                            "teacher_fullname" => $teacher_fullname,
                            "url" => $url_inclass_teacher,
                            "start_date" => $classroom_date,
                            "start_time" => $classroom_time_start,
                            "end_time" => $classroom_time_end
                            // "start_date" => changeDate($classroom_date, 'full_date', 'th'),
                            // "start_time" => substr($classroom_time_start,0,5),
                            // "end_time" => substr($classroom_time_end,0,5)
                        );

                        //insert queue send email to teacher
                        $queue_email_teacher = new QueueEmail();
                        $queue_email_teacher->email_type = 'send_email_classroom';
                        $queue_email_teacher->data = $data;
                        $queue_email_teacher->subject = $subject;
                        $queue_email_teacher->from_name = $from_name;
                        $queue_email_teacher->from_email = $from_email;
                        $queue_email_teacher->to_name = $to_name;
                        $queue_email_teacher->to_email = $to_email;
                        $queue_email_teacher->queue_status = '0';
                        $queue_email_teacher->save();

                        //insert noti to teacher
                        $teacher_noti = new QueueNoti();
                        $teacher_noti->course_id = $course_id;
                        $teacher_noti->noti_id = $noti_id_teacher->_id;
                        $teacher_noti->classroom_date = $classroom_date;
                        $teacher_noti->classroom_name = $course_name;
                        $teacher_noti->classroom_time_start = $classroom_time_start;
                        $teacher_noti->classroom_time_end = $classroom_time_end;
                        $teacher_noti->classroom_url = $join_moderator_url;
                        $teacher_noti->member_id = $teacher_id;
                        $teacher_noti->member_fullname = $teacher_fullname;
                        $teacher_noti->teacher_id = $teacher_id;
                        $teacher_noti->teacher_fullname = $teacher_fullname;
                        $teacher_noti->hostname = $hostname;
                        $teacher_noti->classroom_category = $classroom->classroom_category;
                        $teacher_noti->member_email = $teacher_email;
                        $teacher_noti->noti_type = 'open_course_teacher';
                        $teacher_noti->queue_status = '0';
                        $teacher_noti->save();

                }
            // loop student
                $i = 1;
                foreach ($classroom->classroom_student as $key => $value)
                {
                    $student_id = $value['student_id'];
                    $student_fullname = $value['student_fname'].' '.$value['student_lname'];
                    $student_email = $value['student_email'];
                        $fullNameStudent = $student_fullname;
                        $password = 'ap';
                        $redirect = 'true';

                        //generate checksum
                        $checksum = sha1('joinfullName='.urlencode($fullNameStudent).'&meetingID='.$meetingID.'&password='.$password.'&redirect='.$redirect.$shared_secret);

                        $join_attendee_url[$value['student_email']] = $server.'api/join?fullName='.urlencode($fullNameStudent).'&meetingID='.$meetingID.'&password='.$password.'&redirect='.$redirect.'&checksum='.$checksum;
                        $check_noti_student = MemberNotification::where('noti_type','open_course_student')->where('id_user',$student_id)->where('course_id',$classroom->course_id)->count();
                        if($check_noti_student == 0)
                        {
                            $url[$classroom->course_id][$i] = $join_attendee_url[$value['student_email']];
                            $create_noti['noti_status'] = '0';
                            $create_noti['noti_type'] = 'open_course_student';
                            $create_noti['id_user'] = $student_id;
                            $create_noti['course_id'] = $classroom->course_id;
                            $create_noti['check_update'] = '0';
                            $noti_id_student = MemberNotification::create($create_noti);

                            // insert noti to student
                            $student_noti = new QueueNoti();
                            $student_noti->course_id = $classroom->course_id;
                            $student_noti->noti_id = $noti_id_student->_id;
                            $student_noti->classroom_date = $classroom_date;
                            $student_noti->classroom_name = $course_name;
                            $student_noti->classroom_time_start = $classroom_time_start;
                            $student_noti->classroom_time_end = $classroom_time_end;
                            $student_noti->classroom_url = $join_attendee_url[$value['student_email']];
                            $student_noti->member_id = $student_id;
                            $student_noti->member_fullname = $student_fullname;
                            $student_noti->teacher_id = $teacher_id;
                            $student_noti->teacher_fullname = $teacher_fullname;
                            $student_noti->hostname = $hostname;
                            $student_noti->classroom_category = $classroom->classroom_category;
                            $student_noti->noti_type = 'open_course_student';
                            $student_noti->member_email = $student_email;
                            $student_noti->queue_status = '0';
                            $student_noti->save();
                        }
                        //save noti to create QueueEmail;
                        if($noti_id_student->_id)
                        {

                            $url_inclass = $hostname.'/classroom/check/'.$noti_id_student->_id;

                                $subject = 'Suksa Online : เปิดคอร์ส '.$classroom->classroom_name.' เรียบร้อยแล้ว';
                                $from_name = 'Suksa Online';
                                $from_email = 'noreply@suksa.online';
                                $to_name = $classroom->member_fullname;
                                $to_email = $student_email;
                                $description = '';
                                $data = array(
                                    'name'=>$student_fullname,
                                    "course" => $course_name,
                                    "teacher_fullname" => $teacher_fullname,
                                    "url" => $url_inclass,
                                    // "url_bbb" => $join_attendee_url[$value['student_email']],
                                    "start_date" => $classroom_date,
                                    "start_time" => $classroom_time_start,
                                    "end_time" => $classroom_time_end,
                                    // "start_date" => changeDate($classroom_date, 'full_date', 'th'),
                                    // "start_time" => substr($classroom_time_start,0,5),
                                    // "end_time" => substr($classroom_time_end,0,5)
                                );

                                //insert queue send email to student
                                $queue_email_student = new QueueEmail();
                                $queue_email_student->email_type = 'send_email_classroom';
                                $queue_email_student->data = $data;
                                $queue_email_student->subject = $subject;
                                $queue_email_student->from_name = $from_name;
                                $queue_email_student->from_email = $from_email;
                                $queue_email_student->to_name = $to_name;
                                $queue_email_student->to_email = $to_email;
                                $queue_email_student->queue_status = '0';
                                $queue_email_student->save();
                        }

                    $i++;
                }
        }
    }

    public function sendNotiOpenClassRoomCronJob()
    {
        $today = date('Y-m-d');
        $QueueNotis = QueueNoti::where('queue_status','0')->where('classroom_date',$today)
        // ->orderBy('created_at','asc')
        ->limit(500)
        ->get();
        // dd($QueueNotis);
        foreach($QueueNotis as $QueueNoti)
        {
            $QueueNoti->queue_status = '1';
            $QueueNoti->save();

            $noti_id['course_id'] = $QueueNoti->course_id;
            $noti_id['classroom_date'] = $QueueNoti->classroom_date;
            $noti_id['classroom_name'] = $QueueNoti->classroom_name;
            $noti_id['classroom_time_start'] = $QueueNoti->classroom_time_start;
            $noti_id['classroom_time_end'] = $QueueNoti->classroom_time_end;
            $noti_id['classroom_url'] = $QueueNoti->classroom_url;
            $noti_id['member_id'] = $QueueNoti->member_id;
            $noti_id['member_fullname'] = $QueueNoti->member_fullname;
            $noti_id['teacher_id'] = $QueueNoti->teacher_id;
            $noti_id['teacher_fullname'] = $QueueNoti->teacher_fullname;
            $noti_id['noti_type'] = $QueueNoti->noti_type;
            $noti_id['noti_status'] = '0';
            $noti_id['id_user'] = $QueueNoti->member_id;
            $noti_id['check_update'] = '1';
            $check_update_noti = MemberNotification::where('_id',$QueueNoti->noti_id)->where('course_id',$QueueNoti->course_id)->where('id_user',$QueueNoti->member_id)->first();
            if($check_update_noti->check_update == '0'){
                $updateNoti = MemberNotification::where('_id',$QueueNoti->noti_id)->update($noti_id);
                // if($updateNoti == 0)
                // {
                    sendMemberNoti($QueueNoti->member_id);
                // }
            }

        }

    }

    public function CheckInClassFifteenQueue()
    {
        $today = date('Y-m-d');
        $time_now = date('H:i');
        $classrooms = Classroom::where('classroom_status', '1')
                    ->where('classroom_date', 'like', '%'.$today.'%')
                    ->where('classroom_category','School')
                    ->get();

        foreach($classrooms as $classroom)
        {
            $start_minutes_15 = date('H:i',strtotime($classroom->classroom_time_start.'+15 minutes'));
            $start_minutes_20 = date('H:i',strtotime($classroom->classroom_time_start.'+20 minutes'));

            if($time_now >= $start_minutes_15 && $time_now <= $start_minutes_20 && empty($classroom->classroom_checkin))
            {
                $classroom->classroom_checkin = '1';
                $classroom->save();
            }

        }
    }

    public function CheckInClassFifteen()
    {
        $today = date('Y-m-d');
        $time_now = date('H:i');
        $classrooms = Classroom::where('classroom_status', '1')
                    ->where('classroom_checkin', '1')
                    ->where('classroom_date', 'like', '%'.$today.'%')
                    ->where('classroom_category','School')
                    ->get();
        foreach($classrooms as $classroom)
        {
            $bbb_settings = BBBSettings::first();
            if($classroom->classroom_server == 'channel1.suksalive.com')
            {
                $server = $bbb_settings->bbb_url_1;
                $shared_secret = $bbb_settings->bbb_shared_secret_1;
            }else if($classroom->classroom_server == 'channel2.suksalive.com')
            {
                $server = $bbb_settings->bbb_url_2;
                $shared_secret = $bbb_settings->bbb_shared_secret_2;
            }else if($classroom->classroom_server == 'channel3.suksalive.com')
            {
                $server = $bbb_settings->bbb_url_3;
                $shared_secret = $bbb_settings->bbb_shared_secret_3;

            }else if($classroom->classroom_server == 'channel4.suksalive.com')
            {
                $server = $bbb_settings->bbb_url_4;
                $shared_secret = $bbb_settings->bbb_shared_secret_4;

            }else{
                $server = $bbb_settings->bbb_url_5;
                $shared_secret = $bbb_settings->bbb_shared_secret_5;
            }

            $meetingID = 'suksa-'.$classroom->course_id;
            $password = 'ap';
            $checksum = sha1('getMeetingInfomeetingID='.$meetingID.'&password='.$password.$shared_secret);
            $getMeetingInfo = $server.'api/getMeetingInfo?meetingID='.$meetingID.'&password='.$password.'&checksum='.$checksum;
            $urlserver = simplexml_load_file($getMeetingInfo);
            $xml_encode = json_encode($urlserver);
            $meetingInfo = json_decode($xml_encode, true);
            if($classroom->classroom_checkin == '1')
            {


                if($meetingID == $meetingInfo['meetingID'])
                {
                        $classroom->classroom_checkin = '15';
                        $classroom->save();

                        // เนื่องจากค่าที่ได้มาไม่เหมือนกันตลอดถ้ามีคนในห้อง 1 คน จะได้ค่าเป็น array 1 มิติ
                        if(!empty($meetingInfo['attendees']['attendee']['fullName']))
                        {
                            $fullname[] = $meetingInfo['attendees']['attendee']['fullName'];
                        }else
                        {
                            foreach($meetingInfo['attendees']['attendee'] as $key =>  $attendees)
                            {
                                $fullname[] = $attendees['fullName'];
                            }

                        }

                    foreach($classroom->classroom_student as $key2 =>  $classroom_student)
                    {
                        $data_student_incalss[$key2] = ([
                            'student_id' => $classroom_student['student_id'],
                            'student_fname' => $classroom_student['student_fname'],
                            'student_lname' => $classroom_student['student_lname'],
                            'student_email' => $classroom_student['student_email'],
                            'student_date_regis' => $classroom_student['student_date_regis'],
                            'student_tell' => $classroom_student['student_tell'],
                            'student_checkin' => $classroom_student['student_checkin'],
                            'check_student_inclass' =>  (!empty($classroom_student['check_student_inclass'])) ? intval($classroom_student['check_student_inclass']) : 0,
                        ]);
                        foreach($fullname as $key => $student_inclass)
                        {
                            if($student_inclass ==  $classroom_student['student_fname'].' '.$classroom_student['student_lname'])
                            {
                                $data_student_incalss[$key2] = ([
                                    'student_id' => $classroom_student['student_id'],
                                    'student_fname' => $classroom_student['student_fname'],
                                    'student_lname' => $classroom_student['student_lname'],
                                    'student_email' => $classroom_student['student_email'],
                                    'student_date_regis' => $classroom_student['student_date_regis'],
                                    'student_tell' => $classroom_student['student_tell'],
                                    'student_checkin' => $classroom_student['student_checkin'],
                                    'check_student_inclass' =>(!empty($classroom_student['check_student_inclass'])) ? intval($classroom_student['check_student_inclass'])+1 : 1,
                                ]);
                            }
                        }

                    }

                    $classroom->classroom_student = $data_student_incalss;
                    $classroom->save();
                }
            }
        }
    }

    public function CheckInClassThirtyQueue()
    {
        $today = date('Y-m-d');
        $time_now = date('H:i');
        $classrooms = Classroom::where('classroom_status', '1')
                    ->where('classroom_checkin', '15')
                    ->where('classroom_date', 'like', '%'.$today.'%')
                    ->where('classroom_category','School')
                    ->get();

        foreach($classrooms as $classroom)
        {
            $start_minutes_30 = date('H:i',strtotime($classroom->classroom_time_start.'+30 minutes'));
            $start_minutes_35 = date('H:i',strtotime($classroom->classroom_time_start.'+35 minutes'));

            if($time_now >= $start_minutes_30 && $time_now <= $start_minutes_35)
            {
                $classroom->classroom_checkin = '2';
                $classroom->save();
            }

        }
    }

    public function CheckInClassThirty()
    {
        $today = date('Y-m-d');
        $time_now = date('H:i');
        $classrooms = Classroom::where('classroom_status', '1')
                    ->where('classroom_checkin', '2')
                    ->where('classroom_date', 'like', '%'.$today.'%')
                    ->where('classroom_category','School')
                    ->get();
        foreach($classrooms as $classroom)
        {

                $bbb_settings = BBBSettings::first();
                if($classroom->classroom_server == 'channel1.suksalive.com')
                {
                    $server = $bbb_settings->bbb_url_1;
                    $shared_secret = $bbb_settings->bbb_shared_secret_1;
                }else if($classroom->classroom_server == 'channel2.suksalive.com')
                {
                    $server = $bbb_settings->bbb_url_2;
                    $shared_secret = $bbb_settings->bbb_shared_secret_2;
                }else if($classroom->classroom_server == 'channel3.suksalive.com')
                {
                    $server = $bbb_settings->bbb_url_3;
                    $shared_secret = $bbb_settings->bbb_shared_secret_3;
                }else if($classroom->classroom_server == 'channel4.suksalive.com')
                {
                    $server = $bbb_settings->bbb_url_4;
                    $shared_secret = $bbb_settings->bbb_shared_secret_4;
                }else{
                    $server = $bbb_settings->bbb_url_5;
                    $shared_secret = $bbb_settings->bbb_shared_secret_5;
                }

                $meetingID = 'suksa-'.$classroom->course_id;
                $password = 'ap';
                $checksum = sha1('getMeetingInfomeetingID='.$meetingID.'&password='.$password.$shared_secret);
                $getMeetingInfo = $server.'api/getMeetingInfo?meetingID='.$meetingID.'&password='.$password.'&checksum='.$checksum;
                $urlserver = simplexml_load_file($getMeetingInfo);
                $xml_encode = json_encode($urlserver);
                $meetingInfo = json_decode($xml_encode, true);
                if($classroom->classroom_checkin == '2')
                {

                    if($meetingID == $meetingInfo['meetingID'])
                    {
                        $classroom->classroom_checkin = '30';
                        $classroom->save();

                        // เนื่องจากค่าที่ได้มาไม่เหมือนกันตลอดถ้ามีคนในห้อง 1 คน จะได้ค่าเป็น array 1 มิติ
                        if(!empty($meetingInfo['attendees']['attendee']['fullName']))
                        {
                            $fullname[] = $meetingInfo['attendees']['attendee']['fullName'];
                        }else
                        {
                            foreach($meetingInfo['attendees']['attendee'] as $key =>  $attendees)
                            {
                                $fullname[] = $attendees['fullName'];
                            }

                        }

                        foreach($classroom->classroom_student as $key2 =>  $classroom_student)
                        {
                            $data_student_incalss[$key2] = ([
                                'student_id' => $classroom_student['student_id'],
                                'student_fname' => $classroom_student['student_fname'],
                                'student_lname' => $classroom_student['student_lname'],
                                'student_email' => $classroom_student['student_email'],
                                'student_date_regis' => $classroom_student['student_date_regis'],
                                'student_tell' => $classroom_student['student_tell'],
                                'student_checkin' => $classroom_student['student_checkin'],
                                'check_student_inclass' =>  (!empty($classroom_student['check_student_inclass'])) ? intval($classroom_student['check_student_inclass']) : 0,
                            ]);
                            foreach($fullname as $key => $student_inclass)
                            {
                                if($student_inclass ==  $classroom_student['student_fname'].' '.$classroom_student['student_lname'])
                                {
                                    $data_student_incalss[$key2] = ([
                                        'student_id' => $classroom_student['student_id'],
                                        'student_fname' => $classroom_student['student_fname'],
                                        'student_lname' => $classroom_student['student_lname'],
                                        'student_email' => $classroom_student['student_email'],
                                        'student_date_regis' => $classroom_student['student_date_regis'],
                                        'student_tell' => $classroom_student['student_tell'],
                                        'student_checkin' => $classroom_student['student_checkin'],
                                        'check_student_inclass' =>(!empty($classroom_student['check_student_inclass'])) ? intval($classroom_student['check_student_inclass'])+1 : 1,
                                    ]);
                                }
                            }
                        }
                        $classroom->classroom_student = $data_student_incalss;
                        $classroom->save();
                    }
                }

        }
    }

    public function CheckInClassFortyfiveQueue()
    {
        $today = date('Y-m-d');
        $time_now = date('H:i');
        $classrooms = Classroom::where('classroom_status', '1')
                    ->where('classroom_checkin', '30')
                    ->where('classroom_date', 'like', '%'.$today.'%')
                    ->where('classroom_category','School')
                    // ->where('classroom_time_start','<=',$check_start_time)
                    ->get();

        foreach($classrooms as $classroom)
        {
            $start_minutes_45 = date('H:i',strtotime($classroom->classroom_time_start.'+45 minutes'));
            $start_minutes_50 = date('H:i',strtotime($classroom->classroom_time_start.'+50 minutes'));

            if($time_now >= $start_minutes_45 && $time_now <= $start_minutes_50)
            {
                $classroom->classroom_checkin = '3';
                $classroom->save();
            }

        }
    }

    public function CheckInClassFortyfive()
    {
        $today = date('Y-m-d');
        $time_now = date('H:i');
        $classrooms = Classroom::where('classroom_status', '1')
                    ->where('classroom_checkin', '3')
                    ->where('classroom_date', 'like', '%'.$today.'%')
                    ->where('classroom_category','School')
                    ->get();
        foreach($classrooms as $classroom)
        {
            $bbb_settings = BBBSettings::first();
            if($classroom->classroom_server == 'channel1.suksalive.com')
            {
                $server = $bbb_settings->bbb_url_1;
                $shared_secret = $bbb_settings->bbb_shared_secret_1;
            }else if($classroom->classroom_server == 'channel2.suksalive.com')
            {
                $server = $bbb_settings->bbb_url_2;
                $shared_secret = $bbb_settings->bbb_shared_secret_2;
            }else if($classroom->classroom_server == 'channel3.suksalive.com')
            {
                $server = $bbb_settings->bbb_url_3;
                $shared_secret = $bbb_settings->bbb_shared_secret_3;
            }else if($classroom->classroom_server == 'channel4.suksalive.com')
            {
                $server = $bbb_settings->bbb_url_4;
                $shared_secret = $bbb_settings->bbb_shared_secret_4;

            }else{
                $server = $bbb_settings->bbb_url_5;
                $shared_secret = $bbb_settings->bbb_shared_secret_5;
            }

            $meetingID = 'suksa-'.$classroom->course_id;
            $password = 'ap';
            $checksum = sha1('getMeetingInfomeetingID='.$meetingID.'&password='.$password.$shared_secret);
            $getMeetingInfo = $server.'api/getMeetingInfo?meetingID='.$meetingID.'&password='.$password.'&checksum='.$checksum;
            $urlserver = simplexml_load_file($getMeetingInfo);
            $xml_encode = json_encode($urlserver);
            $meetingInfo = json_decode($xml_encode, true);
            if($classroom->classroom_checkin == '3')
            {
                if($meetingID == $meetingInfo['meetingID'])
                {
                    $classroom->classroom_checkin = '45';
                    $classroom->save();

                    // เนื่องจากค่าที่ได้มาไม่เหมือนกันตลอดถ้ามีคนในห้อง 1 คน จะได้ค่าเป็น array 1 มิติ
                    if(!empty($meetingInfo['attendees']['attendee']['fullName']))
                    {
                        $fullname[] = $meetingInfo['attendees']['attendee']['fullName'];
                    }else
                    {
                        foreach($meetingInfo['attendees']['attendee'] as $key =>  $attendees)
                        {
                            $fullname[] = $attendees['fullName'];
                        }

                    }

                    foreach($classroom->classroom_student as $key2 =>  $classroom_student)
                    {
                        $data_student_incalss[$key2] = ([
                            'student_id' => $classroom_student['student_id'],
                            'student_fname' => $classroom_student['student_fname'],
                            'student_lname' => $classroom_student['student_lname'],
                            'student_email' => $classroom_student['student_email'],
                            'student_date_regis' => $classroom_student['student_date_regis'],
                            'student_tell' => $classroom_student['student_tell'],
                            'student_checkin' => $classroom_student['student_checkin'],
                            'check_student_inclass' =>  (!empty($classroom_student['check_student_inclass'])) ? intval($classroom_student['check_student_inclass']) : intval($classroom_student['check_student_inclass']),
                        ]);
                        foreach($fullname as $key => $student_inclass)
                        {
                            if($student_inclass ==  $classroom_student['student_fname'].' '.$classroom_student['student_lname'])
                            {
                                $data_student_incalss[$key2] = ([
                                    'student_id' => $classroom_student['student_id'],
                                    'student_fname' => $classroom_student['student_fname'],
                                    'student_lname' => $classroom_student['student_lname'],
                                    'student_email' => $classroom_student['student_email'],
                                    'student_date_regis' => $classroom_student['student_date_regis'],
                                    'student_tell' => $classroom_student['student_tell'],
                                    'student_checkin' => $classroom_student['student_checkin'],
                                    'check_student_inclass' =>(!empty($classroom_student['check_student_inclass'])) ? intval($classroom_student['check_student_inclass'])+1 : 1,
                                ]);
                            }
                        }
                    }
                    $classroom->classroom_student = $data_student_incalss;
                    $classroom->save();
                }
            }
        }
    }

    public function CheckInClassSixtyQueue()
    {
        $today = date('Y-m-d');
        $time_now = date('H:i');
        $classrooms = Classroom::where('classroom_status', '1')
                    ->where('classroom_checkin', '45')
                    ->where('classroom_date', 'like', '%'.$today.'%')
                    ->where('classroom_category','School')
                    // ->where('classroom_time_start','<=',$check_start_time)
                    ->get();

        foreach($classrooms as $classroom)
        {
            $start_minutes_55 = date('H:i',strtotime($classroom->classroom_time_start.'+55 minutes'));
            $start_minutes_60 = date('H:i',strtotime($classroom->classroom_time_start.'+60 minutes'));

            if($time_now >= $start_minutes_55 && $time_now <= $start_minutes_60)
            {
                $classroom->classroom_checkin = '4';
                $classroom->save();
            }
        }
    }

    public function CheckInClassSixty()
    {
        $today = date('Y-m-d');
        $time_now = date('H:i');
        $classrooms = Classroom::where('classroom_status', '1')
                    ->where('classroom_checkin', '4')
                    ->where('classroom_date', 'like', '%'.$today.'%')
                    ->where('classroom_category','School')
                    ->get();

        foreach($classrooms as $classroom)
        {


            $bbb_settings = BBBSettings::first();
            if($classroom->classroom_server == 'channel1.suksalive.com')
            {
                $server = $bbb_settings->bbb_url_1;
                $shared_secret = $bbb_settings->bbb_shared_secret_1;
                $urlserver = simplexml_load_file('https://channel1.suksalive.com/bigbluebutton/api/getMeetings?checksum=50a2c102fa88dd48d46b26b6f14f03c03969ec14');

            }else if($classroom->classroom_server == 'channel2.suksalive.com')
            {
                $server = $bbb_settings->bbb_url_2;
                $shared_secret = $bbb_settings->bbb_shared_secret_2;
                $urlserver = simplexml_load_file('https://channel2.suksalive.com/bigbluebutton/api/getMeetings?checksum=262f686ef270c14491ffa950abb694457717d9dd');

            }else if($classroom->classroom_server == 'channel3.suksalive.com')
            {
                $server = $bbb_settings->bbb_url_3;
                $shared_secret = $bbb_settings->bbb_shared_secret_3;
                $urlserver = simplexml_load_file('https://channel3.suksalive.com/bigbluebutton/api/getMeetings?checksum=b217a356621b65b1ee2e48f42f5d0855d4d3954f');

            }else if($classroom->classroom_server == 'channel4.suksalive.com')
            {
                $server = $bbb_settings->bbb_url_4;
                $shared_secret = $bbb_settings->bbb_shared_secret_4;
                $urlserver = simplexml_load_file('https://channel4.suksalive.com/bigbluebutton/api/getMeetings?checksum=31a7bf23fcd77bb0dabc98c036f95d1bd5efb561');

            }else{
                $server = $bbb_settings->bbb_url_5;
                $shared_secret = $bbb_settings->bbb_shared_secret_5;
            }
            $meetingID = 'suksa-'.$classroom->course_id;
            $password = 'ap';
            $checksum = sha1('getMeetingInfomeetingID='.$meetingID.'&password='.$password.$shared_secret);
            $getMeetingInfo = $server.'api/getMeetingInfo?meetingID='.$meetingID.'&password='.$password.'&checksum='.$checksum;
            $urlserver = simplexml_load_file($getMeetingInfo);
            $xml_encode = json_encode($urlserver);
            $meetingInfo = json_decode($xml_encode, true);
            if($classroom->classroom_checkin == '4')
            {
                if($meetingID == $meetingInfo['meetingID'])
                {
                    $classroom->classroom_checkin = '60';
                    $classroom->save();

                    // เนื่องจากค่าที่ได้มาไม่เหมือนกันตลอดถ้ามีคนในห้อง 1 คน จะได้ค่าเป็น array 1 มิติ
                    if(!empty($meetingInfo['attendees']['attendee']['fullName']))
                    {
                        $fullname[] = $meetingInfo['attendees']['attendee']['fullName'];
                    }else
                    {
                        foreach($meetingInfo['attendees']['attendee'] as $key =>  $attendees)
                        {
                            $fullname[] = $attendees['fullName'];
                        }

                    }

                    foreach($classroom->classroom_student as $key2 =>  $classroom_student)
                    {
                        $data_student_incalss[$key2] = ([
                            'student_id' => $classroom_student['student_id'],
                            'student_fname' => $classroom_student['student_fname'],
                            'student_lname' => $classroom_student['student_lname'],
                            'student_email' => $classroom_student['student_email'],
                            'student_date_regis' => $classroom_student['student_date_regis'],
                            'student_tell' => $classroom_student['student_tell'],
                            'student_checkin' => $classroom_student['student_checkin'],
                            'check_student_inclass' =>  (!empty($classroom_student['check_student_inclass'])) ? intval($classroom_student['check_student_inclass']) : intval($classroom_student['check_student_inclass']),
                        ]);
                        foreach($fullname as $key => $student_inclass)
                        {
                            if($student_inclass ==  $classroom_student['student_fname'].' '.$classroom_student['student_lname'])
                            {
                                $data_student_incalss[$key2] = ([
                                    'student_id' => $classroom_student['student_id'],
                                    'student_fname' => $classroom_student['student_fname'],
                                    'student_lname' => $classroom_student['student_lname'],
                                    'student_email' => $classroom_student['student_email'],
                                    'student_date_regis' => $classroom_student['student_date_regis'],
                                    'student_tell' => $classroom_student['student_tell'],
                                    'student_checkin' => $classroom_student['student_checkin'],
                                    'check_student_inclass' =>(!empty($classroom_student['check_student_inclass'])) ? intval($classroom_student['check_student_inclass'])+1 : 1,
                                ]);
                            }
                        }
                    }
                    $classroom->classroom_student = $data_student_incalss;
                    $classroom->save();
                }
            }
        }
    }



}
