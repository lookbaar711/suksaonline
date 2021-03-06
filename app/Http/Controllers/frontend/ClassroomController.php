<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BBBSettings;
use App\Models\Classroom;
use App\Models\Member;
use App\Models\Course;
use App\Models\CourseQuestions;
use App\Models\CourseTest;
use App\Models\Subject;
use App\Models\Aptitude;
use App\Models\CoinsLog;
use App\Models\RequestSubjects;
use Auth;
use App\Models\MemberNotification;
use Mail;
use App\Events\SendNotiFrontend;
use Session;

class ClassroomController extends Controller
{

    public function store(Course $courses, $request_id){
        $check_limit = Classroom::where('course_id', '=', $courses->id)->first();

        if($check_limit){
            if(count($check_limit->classroom_student) == $courses->course_student_limit){
                return redirect()->back()->with('alerterror', 'คอร์สเรียนปิดการสมัคร');
            }
        }

        $check_course = Classroom::where('classroom_student.student_id', '=', Auth::guard('members')->user()->id)->where('course_id', '=', $courses->id)->first();

        if($check_course){
            return redirect()->back()->with('alerterror', 'คุณได้สมัครเรียนไปแล้ว');
        }

        //คอร์ส public
        if($courses->course_type=='1'){
            $student = Member::where('_id', '=', Auth::guard('members')->user()->id)->where('member_status', '1')->first();

            //เช็ค coins ว่าเพียงพอไหม
            if(str_replace(",","",$student->member_coins) < str_replace(",","",$courses->course_price)){
                return redirect()->back()->with('classroom', 'fail');
            }
            //หัก coins นักเรียน
            $coins = str_replace(",","",$student->member_coins) -
                str_replace(",","",$courses->course_price);
            $member_coins = $coins;
            $student->member_coins = number_format($coins);
            $student->save();

            $data_log = ([
                'member_id' => Auth::guard('members')->user()->_id,
                'member_fname' => Auth::guard('members')->user()->member_fname,
                'member_lname' => Auth::guard('members')->user()->member_lname,
                'event' => 'pay_coins',
                'ref_id' => $courses->id,
                'ref_name' => $courses->course_name,

                'coin_date' => date('Y-m-d'),
                'coin_time' => date('H:i:s'),
                'coin_number' => number_format($courses->course_price),
                'coin_status' => '1',
            ]);
            CoinsLog::create($data_log);
        }

        $classroom_datetime = array();

        //เช็คว่ามีคนสมัครเรียนแล้วหรือยัง
        $classroom = Classroom::where('course_id', $courses->id)->first();
        $check_opening = '0';

        //ถ้ามีคนสมัครแล้ว
        if($classroom){
            //ดึงข้อมูลคอร์สนั้นมาอัพเดต
            $classroom_update = Classroom::where('course_id', $courses->id)
                                ->orderby('classroom_date','asc')
                                ->get();

            foreach ($classroom_update as $key => $value) {
                $student_detail = $value->classroom_student;
                $student_detail[] = ([
                    'student_id' => Auth::guard('members')->user()->id,
                    'student_fname' => Auth::guard('members')->user()->member_fname,
                    'student_lname' => Auth::guard('members')->user()->member_lname,
                    'student_email' => Auth::guard('members')->user()->member_email,
                    'student_date_regis' => date('Y-m-d'),
                    'student_tell' => Auth::guard('members')->user()->member_tell,
                    'student_checkin' => '0',
                ]);
                $value->classroom_student = $student_detail;
                //dd($value);
                $value->save();

                $datetime = array(
                    'classroom_date' => $value->classroom_date,
                    'classroom_time_start' => $value->classroom_time_start,
                    'classroom_time_end' => $value->classroom_time_end,
                );

                array_push($classroom_datetime,$datetime);

                if($value->classroom_status == '1'){
                    $check_opening = '1';
                }
            }
        }
        //ถ้ายังไม่เคยมีคนสมัคร
        else{
            foreach ($courses->course_date as $key => $value) {
                //สร้างห้องเรียน
                if($key=="0"){
                    $student_detail[] = ([
                        'student_id' => Auth::guard('members')->user()->id,
                        'student_fname' => Auth::guard('members')->user()->member_fname,
                        'student_lname' => Auth::guard('members')->user()->member_lname,
                        'student_email' => Auth::guard('members')->user()->member_email,
                        'student_date_regis' => date('Y-m-d'),
                        'student_tell' => Auth::guard('members')->user()->member_tell,
                        'student_checkin' => '0',
                    ]);
                }
                $teacher_detail = ([
                    'teacher_id' => $courses->member_id,
                    'teacher_fname' => $courses->member_fname,
                    'teacher_lname' => $courses->member_lname,
                    'teacher_email' => $courses->member_email,
                    'teacher_checkin' => '0',
                ]);
                $data = ([
                    'course_id' => $courses->id,
                    'classroom_name' => $courses->course_name,
                    'classroom_date' => $value['date'],
                    'classroom_time_start' => $value['time_start'],
                    'classroom_time_end' => $value['time_end'],
                    'classroom_teacher' => $teacher_detail,
                    'classroom_student' => $student_detail,
                    'classroom_status' => '0',
                    'classroom_category' => $courses->course_category,
                ]);

                $datetime = array(
                    'classroom_date' => $value['date'],
                    'classroom_time_start' => $value['time_start'],
                    'classroom_time_end' => $value['time_end'],
                );

                array_push($classroom_datetime,$datetime);

                Classroom::create($data);
            }
        }

        $request = RequestSubjects::where('_id', $request_id)->first();
        if($request){
            $request->request_status = "1";
            $request->request_course_id = $courses->id;
            $request->teacher_id = $courses->member_id;
            $request->save();
        }


        $count_datetime = count($classroom_datetime)-1;

        $courses_free = Course::where('course_type', '0')->get();
        $courses_notfree = Course::where('course_type', '1')->get();

        $course_id = $courses->id;
        $course_name = $courses->course_name;
        $course_date = $courses->course_date[0]['date'];
        $course_time_start = $courses->course_time_start;
        $course_time_end = $courses->course_time_end;

        $student_fullname = Auth::guard('members')->user()->member_fname.' '.Auth::guard('members')->user()->member_lname;
        $student_id = Auth::guard('members')->user()->id;
        $teacher_fullname = $courses->member_fname.' '.$courses->member_lname;
        $teacher_id = $courses->member_id;

        //sent email to teacher
        $subject = 'Suksa Online : สมัครเรียนสำเร็จ';
        $from_name = 'Suksa Online';
        $from_email = 'noreply@suksa.online';
        $to_name = $teacher_fullname;
        $to_email = $courses->member_email;
        $description = '';
        $data = array(
            'student_fullname'=>$student_fullname,
            'teacher_fullname' => $teacher_fullname,
            'course_name' => $course_name,
            'course_date' => changeDate($course_date, 'full_date', 'th'),
            'course_time_start' => substr($course_time_start,0,5),
            'course_time_end' => substr($course_time_end,0,5),
            'description' => $description
        );

        Mail::send('frontend.send_email_register_course_teacher', $data, function($message) use ($subject, $from_name, $from_email, $to_name, $to_email) {
            $message->from($from_email, $from_name);
            $message->to($to_email, $to_name);
            $message->subject($subject);
        });

        if($courses->course_category=="Private"){
            $noti_type_teacher = "register_course_private_teacher";
            $noti_type_student = "register_course_private_student";
        }
        else{
            $noti_type_teacher = "register_course_teacher";
            $noti_type_student = "register_course_student";
        }

        // //insert noti to teacher
        $teacher_noti = new MemberNotification();
        $teacher_noti->course_id = $course_id;
        $teacher_noti->classroom_name = $course_name;
        $teacher_noti->classroom_datetime = $classroom_datetime;
        $teacher_noti->classroom_start_date = $classroom_datetime[0]['classroom_date'];
        $teacher_noti->classroom_end_date = $classroom_datetime[$count_datetime]['classroom_date'];
        $teacher_noti->member_id = $teacher_id;
        $teacher_noti->member_fullname = $teacher_fullname;
        $teacher_noti->student_id = $student_id;
        $teacher_noti->student_fullname = $student_fullname;
        $teacher_noti->noti_type = $noti_type_teacher;
        $teacher_noti->noti_status = '0';
        $teacher_noti->save();

        //send noti
        sendMemberNoti($teacher_id);

        //send email to student
        $subject = 'Suksa Online : สมัครเรียนสำเร็จ';
        $from_name = 'Suksa Online';
        $from_email = 'noreply@suksa.online';
        $to_name = $student_fullname;
        $to_email = Auth::guard('members')->user()->member_email;
        $description = '';
        $data = array(
            'student_fullname'=>$student_fullname,
            'teacher_fullname' => $teacher_fullname,
            'course_name' => $course_name,
            'course_date' => changeDate($course_date, 'full_date', 'th'),
            'course_time_start' => substr($course_time_start,0,5),
            'course_time_end' => substr($course_time_end,0,5),
            'description' => $description
        );

        Mail::send('frontend.send_email_register_course_student', $data, function($message) use ($subject, $from_name, $from_email, $to_name, $to_email) {
            $message->from($from_email, $from_name);
            $message->to($to_email, $to_name);
            $message->subject($subject);
        });


        //insert noti to student
        $student_noti = new MemberNotification();
        $student_noti->course_id = $course_id;
        $student_noti->classroom_name = $course_name;
        $student_noti->classroom_datetime = $classroom_datetime;
        $student_noti->classroom_start_date = $classroom_datetime[0]['classroom_date'];
        $student_noti->classroom_end_date = $classroom_datetime[$count_datetime]['classroom_date'];
        $student_noti->member_id = $student_id;
        $student_noti->member_fullname = $student_fullname;
        $student_noti->teacher_id = $teacher_id;
        $student_noti->teacher_fullname = $teacher_fullname;
        $student_noti->noti_type = $noti_type_student;
        $student_noti->noti_status = '0';
        $student_noti->save();

        //send noti
        sendMemberNoti($student_id);







        // $current_date = date('Y-m-d');
        // $current_time = date('H:i:s');

        //มีห้องเรียนกำลังเปิดอยู่
        if($check_opening == '1'){
            //ถ้าวันเวลาปัจจุบัน ตรงกับวันเวลาที่กำลังเปิดสอนอยู่
            //ให้ส่ง email & noti เข้าห้องเรียนให้นักเรียนคนที่สมัคร
            $student_id = $student_id;
            $student_fullname = $student_fullname;
            $student_email = Auth::guard('members')->user()->member_email;

            //select bbb server
            $bbb_setting = BBBSettings::first();

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

            $fullName = $student_fullname;
            $meetingID = 'suksa-'.$course_id;
            $password = 'ap';
            $redirect = 'true';

            //generate checksum
            $checksum = sha1('joinfullName='.urlencode($fullName).'&meetingID='.$meetingID.'&password='.$password.'&redirect='.$redirect.$shared_secret);

            $join_attendee_url[$student_email] = $server.'api/join?fullName='.urlencode($fullName).'&meetingID='.$meetingID.'&password='.$password.'&redirect='.$redirect.'&checksum='.$checksum;

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $join_attendee_url[$student_email],
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
                "url" => $join_attendee_url[$student_email],
                "start_date" => $course_date,
                "start_time" => $course_time_start,
                "end_time" => $course_time_end
            );

            //insert queue send email to student
            // $queue_email_student = new QueueEmail();
            // $queue_email_student->email_type = 'send_email_classroom';
            // $queue_email_student->data = $data;
            // $queue_email_student->subject = $subject;
            // $queue_email_student->from_name = $from_name;
            // $queue_email_student->from_email = $from_email;
            // $queue_email_student->to_name = $to_name;
            // $queue_email_student->to_email = $to_email;
            // $queue_email_student->queue_status = '0';
            // $queue_email_student->save();

            Mail::send('frontend.send_email_classroom', $data, function($message) use ($subject, $from_name, $from_email, $to_name, $to_email) {
                $message->from($from_email, $from_name);
                $message->to($to_email, $to_name);
                $message->subject($subject);
            });

            //insert noti to student
            $student_noti = new MemberNotification();
            $student_noti->course_id = $course_id;
            $student_noti->classroom_date = $course_date;
            $student_noti->classroom_name = $course_name;
            $student_noti->classroom_time_start = $course_time_start;
            $student_noti->classroom_time_end = $course_time_end;
            $student_noti->classroom_url = $join_attendee_url[$student_email];
            $student_noti->member_id = $student_id;
            $student_noti->member_fullname = $student_fullname;
            $student_noti->teacher_id = $teacher_id;
            $student_noti->teacher_fullname = $teacher_fullname;
            $student_noti->noti_type = 'open_course_student';
            $student_noti->noti_status = '0';
            $student_noti->save();

            //send noti to student
            sendMemberNoti($student_id);
        }

        $student_coins = Member::where('_id', '=', Auth::guard('members')->user()->id)->where('member_status', '1')->first();

        if($classroom_datetime[0]['classroom_date'] == $classroom_datetime[$count_datetime]['classroom_date']){
            $date_time = date('d/m/Y', strtotime($classroom_datetime[0]['classroom_date']));
        }
        else{
            if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
                $date_time = date('d/m/Y', strtotime($classroom_datetime[0]['classroom_date'])).' to '.date('d/m/Y', strtotime($classroom_datetime[$count_datetime]['classroom_date']));
            }
            else{
                $date_time = date('d/m/Y', strtotime($classroom_datetime[0]['classroom_date'])).' ถึง '.date('d/m/Y', strtotime($classroom_datetime[$count_datetime]['classroom_date']));
            }
        }

        if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
            $aptitude_detail = Aptitude::where('_id', $courses->course_group)->first();
            $aptitude_name = $aptitude_detail->aptitude_name_en;

            $subject_detail = Subject::where('_id', $courses->course_subject)->first();
            $subject_name = $subject_detail->subject_name_en;
        }else{
            $aptitude_detail = Aptitude::where('_id', $courses->course_group)->first();
            $aptitude_name = $aptitude_detail->aptitude_name_th;

            $subject_detail = Subject::where('_id', $courses->course_subject)->first();
            $subject_name = $subject_detail->subject_name_th;
        }
        if($courses->course_price=="0"){
            if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
                $course_price = "Free";
            }else{
                $course_price = "ฟรี";
            }
        }else{
            $course_price = number_format($courses->course_price)." Coins";
        }

        return redirect()->back()->with('flash_message', [$courses->course_name,$aptitude_name.", ".$subject_name,$courses->member_fname." ".$courses->member_lname,$date_time,$course_price,$student_coins->member_coins,$courses->course_category]);
    }

    public function check($noti_id){

        $member_noti = MemberNotification::where('_id', $noti_id)
                        ->first();

        if (Auth::guard('members')->user() == null || Auth::guard('members')->user()->id != $member_noti->member_id )
        {
            if(Auth::guard('members')->user() != null)
            {
                $member_update = Member::where('_id', '=', Auth::guard('members')->user()->_id)->first();
                $member_update->online_status = '0';
                $member_update->last_action_at = date('Y-m-d H:i:s');
                $member_update->save();

                Auth::guard('members')->logout();
            }

            if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
                return redirect()->to('/')->with('please_login', 'Please Login.');
            }
            else{
                return redirect()->to('/')->with('please_login', 'กรุณาเข้าสู่ระบบ');
            }

        }

        //เช็คว่าตอนนี้ มีห้องเรียนที่คอร์สตรงกับเวลาที่เปิดสอนนี้หรือเปล่า
        $classroom = Classroom::where('course_id', '=', $member_noti->course_id)
                    ->where('classroom_date', '=', $member_noti->classroom_date)
                    ->where('classroom_time_start', '=', $member_noti->classroom_time_start)
                    ->where('classroom_time_end', '=', $member_noti->classroom_time_end)
                    ->first();

        $course = Course::where('_id', '=', $member_noti->course_id)
                        ->first();
        // dd($classroom);
        //ถ้ามีห้องเรียน และที่มีสถานะเป็น 0 หรือ 1 จะเปิดห้องและเข้าห้องเรียนได้
        if(isset($classroom->classroom_status) && ($classroom->classroom_status != '2'))
        {
            // dd($member_noti);
            $join_moderator_url = $member_noti->classroom_url;
            // dd($join_moderator_url);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $join_moderator_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_TIMEOUT => 3000,
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

                if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
                    return redirect()->back()->with('classroom_closed', 'The classroom is closed.');
                }
                else{
                    return redirect()->back()->with('classroom_closed', 'ไม่สามารถเข้าห้องเรียนได้ กรุณาลองใหม่อีกครั้ง');
                }

                // echo 'CURL Error : '.$err;
            }
            else {

                //ถ้าเป็นนักเรียนในคอร์ส School
                if(($course->course_category == 'School') && ($member_noti->noti_type == 'open_course_student'))
                {

                    $check_test = CourseTest::where('course_id', '=', $member_noti->course_id)
                                ->where('member_id', '=', Auth::guard('members')->user()->id)
                                ->count();

                    if($check_test == 0){ //ยังไม่ได้ทำ
                        //return redirect()->back()->with('classroom_closed', 'ทำแบบทดสอบก่อนเรียน');

                        $datas = CourseQuestions::where('course_id', '=', $member_noti->course_id)->first();

                        if($datas){
                            return view('frontend.examination.pretest',compact('datas'));
                        }
                        else{
                            return redirect()->back()->with('classroom_closed', 'ไม่พบห้องเรียน');
                        }
                    }
                }

                //ถ้าเป็นคอร์ส School ที่ทำแบบทดสอบแล้ว หรือเป็นคอร์สประเภทอื่นให้เปิดห้องเรียน แล้วส่งอาจารย์หรือนักเรียนเข้าห้องเรียน
                $teacher_id = $classroom->classroom_teacher['teacher_id'];
                $teacher_fullname = $classroom->classroom_teacher['teacher_fname'].' '.$classroom->classroom_teacher['teacher_lname'];
                $teacher_email = $classroom->classroom_teacher['teacher_email'];
                $course_id = $classroom->course_id;
                $course_name = $classroom->classroom_name;
                $classroom_date = $classroom->classroom_date;
                $classroom_time_start = $classroom->classroom_time_start;
                $classroom_time_end = $classroom->classroom_time_end;


                //select bbb server
                $bbb_setting = BBBSettings::first();

                //select domain name from classroom_server
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

                $allowStartStopRecording = 'true';
                $attendeePW = 'ap';
                $autoStartRecording = 'false';

                //$logoutURL = 'http://127.0.0.1:8000/backend/classroom/logout/'.$id;
                if(($_SERVER['HTTP_HOST'] == '127.0.0.1:8000') || ($_SERVER['HTTP_HOST'] == '127.0.0.1') || ($_SERVER['HTTP_HOST'] == 'localhost')){
                    $logoutURL = 'http://'.$_SERVER['HTTP_HOST'].'/';
                }
                else if($_SERVER['HTTP_HOST'] == 'dev.suksa.online'){
                    $logoutURL = 'http://'.$_SERVER['HTTP_HOST'].'/';
                }
                else{ //suksa.online
                    $logoutURL = 'http://'.$_SERVER['HTTP_HOST'].'/';
                }

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
                    //return "cURL Error #:" . $err;
                    return redirect()->back()->with('create_meeting_room_error', 'CURL Error : '.$err);
                }
                else {
                    //update checkin BBB
                    //ถ้าเป็นอาจารย์ให้อัพเดต teacher_checkin = 1
                    if($member_noti->noti_type == 'open_course_teacher'){
                        $classroom->update([
                            'classroom_teacher' => [
                                'teacher_id' => $classroom->classroom_teacher['teacher_id'],
                                'teacher_fname' => $classroom->classroom_teacher['teacher_fname'],
                                'teacher_lname' => $classroom->classroom_teacher['teacher_lname'],
                                'teacher_email' => $classroom->classroom_teacher['teacher_email'],
                                'teacher_checkin' => '1'
                            ],
                        ]);
                    }
                    //ถ้าเป็นนักเรียนให้อัพเดต student_checkin = 1
                    else{
                        $classroom_student = array();

                        foreach ($classroom->classroom_student as $key => $value) {
                            //ถ้าไม่มีค่า student_checkin ให้ set default student_checkin = 0
                            $student_checkin = (isset($value['student_checkin']))?$value['student_checkin'] : '0';

                            //ถ้าเป็นนักเรียนที่กดเข้าห้องเรียน ให้ set student_checkin = 1
                            //ถ้าไม่ใช่นักเรียนที่กดเข้าห้องเรียน ให้ set student_checkin เป็นค่าเดิม หรือ ค่า default
                            $student_detail = array(
                                'student_id' => $value['student_id'],
                                'student_fname' => $value['student_fname'],
                                'student_lname' => $value['student_lname'],
                                'student_email' => $value['student_email'],
                                'student_date_regis' => $value['student_date_regis'],
                                'student_tell' => $value['student_tell'],
                                'student_checkin' => ($member_noti->member_id == $value['student_id'])?'1' : $student_checkin
                            );

                            array_push($classroom_student , $student_detail);
                        }

                        $classroom->classroom_student = $classroom_student;
                        $classroom->save();
                    }

                    //call create api success
                    return redirect($member_noti->classroom_url);

                    //return $response;
                }

            }

        }
        //ถ้าไม่มี ให้แสดงผลกลับไปว่า ห้องเรียนถูกปิดแล้ว
        else{
          if ($classroom->classroom_status == '0') {
            if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
                return redirect()->back()->with('classroom_closed', 'Classroom undiscovered please wait.');
            }
            else{
                return redirect()->back()->with('classroom_closed', 'ห้องเรียนยังไม่ถูกเปิด กรุณารอสักครู่');
            }
          }else {
            if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
                return redirect()->back()->with('classroom_closed', 'The classroom is closed.');
            }
            else{
                return redirect()->back()->with('classroom_closed', 'ห้องเรียนถูกปิดแล้ว');
            }
          }


        }
    }

    public function show(Course $course){
        update_last_action();
        return view('backend.subjects.subject-edit',compact('subject'));
    }
}
