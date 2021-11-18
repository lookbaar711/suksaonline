<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Member;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Coin;
use App\Models\Withdraw;
use App\Models\MemberNotification;
use App\Models\RequestSubjects;
use App\Models\Aptitude;
use App\Models\Subject;
use App\Models\CoinsLog;
use App\Models\BankMaster;
use App\Models\MemberBank;
use App\Models\TeacherRating;
use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Session;
use Mail;
use App;
use Lang;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    public function index(){

    }

    public function create(){
      if(Auth::guard('members')->user()){
        return redirect('/');
      }
      else{
        update_last_action();
        return view('frontend.users.user-create');
      }
    }

    public function store(Request $request){
      if(Auth::guard('members')->user()){
        return redirect('/');
      }
      else{

        // $fname = trim($request['member_fname']);
        // $lname = trim($request['member_lname']);
        // $check_member_name = Member::where('member_fname',$fname)
        // ->where('member_lname',$lname)->first();
        // if($check_member_name){
        //     return redirect()->back()->with('create_member_error', 'fales');
        // }

        $last_member = Member::orderBy('mid','desc')->where('member_status', '1')->first();
        $mid = ((isset($last_member->mid)&&($last_member->mid!=null)))?$last_member->mid+1:1;

        $data = ([
            'member_email' => $request['member_email'],
            'member_password' => Hash::make($request['member_password']),
            'member_fname' => $request['member_fname'],
            'member_lname' => $request['member_lname'],
            'member_fullname' => $request['member_fname']." ".$request['member_lname'],
            'member_tell' => $request['member_tell'],
            'member_status' => '1',
            'member_coins' => '0',
            'member_type' => 'student',
            'member_teacher' => '0',
            'online_status' => '0',
            'mid' => intval($mid),
            'member_lang' => 'th',
        ]);
        Member::create($data);

        $member = Member::orderBy('created_at','desc')->where('member_status', '1')->first();
        $member->member_id = $member->_id;
        $member->save();

        //send email
        $subject = 'Suksa Online : สมัครสมาชิกสำเร็จ';
        $from_name = 'Suksa Online';
        $from_email = 'noreply@suksa.online';
        $to_name = $request['member_fname'].' '.$request['member_lname'];
        $to_email = $request['member_email'];
        $description = '';
        $data = array('name'=>$to_name, "username" => $request['member_email'], "password" => $request['member_password'], "description" => $description);

        Mail::send('frontend.send_email_register_student', $data, function($message) use ($subject, $from_name, $from_email, $to_name, $to_email) {
            $message->from($from_email, $from_name);
            $message->to($to_email, $to_name);
            $message->subject($subject);
        });

        return redirect('/')->with('alert', 'user');
      }
    }

    public function profile(){
        //ข้อมูลนักเรียน
        $members = Member::where('_id', '=', Auth::guard('members')->user()->id)->where('member_status', '1')->first();
        //ข้อมูลธนาคาร master ระบบ
        $bank_master = BankMaster::orderBy('bank_name_th','asc')->get();
        //ข้อมูลธนาคารของ user
        $member_bank = MemberBank::where('member_id', Auth::guard('members')->user()->_id)
                      ->orderby('created_at','asc')
                      ->get();
        // dd();
        //ข้อมูลโรงเรียน
        $school = School::where('school_status', '1')->where('school_student.student_email', Auth::guard('members')->user()->member_email)->get();
        $school_all = School::where('school_status', '1')->get();
        // dd($school);
        update_last_action();

        return view('frontend.users.user-profile', compact('members','bank_master','member_bank','school','school_all'));
    }

    public function user_profile_cousres(Request $request){

      //ประวัติการสมัคร
      $classroom = Classroom::where('classroom_student.student_id', '=', Auth::guard('members')->user()->id)
      ->select('classroom_student', 'created_at', 'course_id', 'classroom_status')
      ->orderBy('created_at','desc')
      ->groupBy('course_id')
      ->get();

      $courses = [];
      //dd($classroom);
      // dd($classroom[8],$classroom[9]);
      foreach ($classroom as $key => $value) {

          $course = Course::where('_id', $value->course_id)->first();

          if (!empty($course)) {
            $aptitude = Aptitude::where('_id', $course->course_group)->first();
            $course->course_group = $aptitude->aptitude_name_th;

            $subject = Subject::where('_id', $course->course_subject)->first();
            $course->course_subject = $subject->subject_name_th;
          }


          $teacher_rating = TeacherRating::where('course_id', '=', $value->course_id)
                        ->where('member_id', '=', Auth::guard('members')->user()->_id)
                        ->first();
          if (!empty($course)) {
            $course->student = count($value->classroom_student);
            $course->classroom_status = $value->classroom_status;
            $course->rating_status = (isset($teacher_rating->rating_status))?$teacher_rating->rating_status:'9';

          }

          $link_open = MemberNotification::where('course_id', '=', $value->course_id)
                      ->where('member_id', '=', Auth::guard('members')->user()->id)
                      ->where('classroom_date', '=', date('Y-m-d'))
                      ->where('noti_type', '=', 'open_course_student')
                      ->first();

          if($link_open){

              $course->link_open = $link_open->_id;
              $course->status_show = "true";
          }
          $courses[] = $course;
      }

      $currentPage = LengthAwarePaginator::resolveCurrentPage();

      //Create a new Laravel collection from the array data
      $collection = Collect($courses);

      //Define how many items we want to be visible in each page
      $per_page = 3;

      //Slice the collection to get the items to display in current page
      $currentPageResults = $collection->slice(($currentPage-1) * $per_page, $per_page)->all();

      //Create our paginator and add it to the data array
      $data['courses'] = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);

      //Set base url for pagination links to follow e.g custom/url?page=6
      $data['courses']->setPath($request->url());

      if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
          App::setLocale('en');
      }
      else {
          App::setLocale('th');
      }

      $course_edit = Lang::get('frontend/members/title.edit_button');
      $free_course = Lang::get('frontend/members/title.free_course');
      $course_document = Lang::get('frontend/members/title.course_document');
      $download_button = Lang::get('frontend/members/title.download_button');
      $close_register = Lang::get('frontend/members/title.close_register');
      $openning_register = Lang::get('frontend/members/title.openning_register');
      $waiting_register = Lang::get('frontend/members/title.waiting_register');
      $course_detail = Lang::get('frontend/members/title.course_detail');
      $student_number = Lang::get('frontend/members/title.student_number');



      $data = array(
        'cousres' => $data,
        'free_course' => $free_course,
        'course_document' => $course_document,
        'download_button' => $download_button,
        'close_register' => $close_register,
        'openning_register' => $openning_register,
        'waiting_register' => $waiting_register,
        'course_detail' => $course_detail,
        'student_number' => $student_number,
        'course_edit' => $course_edit,
      );
      return json_encode($data);
    }

    public function user_profile_coins(Request $request){
      $coins = CoinsLog::where('member_id', '=', Auth::guard('members')->user()->id)->orderBy('created_at','desc')->paginate(3);

      if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
          App::setLocale('en');
      }
      else {
          App::setLocale('th');
      }

      $topup = Lang::get('frontend/members/title.topup');
      $withdraw = Lang::get('frontend/members/title.withdraw');

      $pay = Lang::get('frontend/members/title.pay');
      $get = Lang::get('frontend/members/title.get');
      $return = Lang::get('frontend/members/title.return');
      $deduct = Lang::get('frontend/members/title.deduct');
      $refund = Lang::get('frontend/coins/title.refund');

      $waiting_approve = Lang::get('frontend/members/title.waiting_approve');
      $approved = Lang::get('frontend/members/title.approved');
      $not_approved = Lang::get('frontend/members/title.not_approved');
      $success = Lang::get('frontend/members/title.success');
      $course_detail = Lang::get('frontend/members/title.course_detail');


      $data = array(
          // 'data_coins' => $coins,
          'coins' => $coins,
          'topup' => $topup,
          'withdraw' => $withdraw,

          'pay' => $pay,
          'get' => $get,
          'return' => $return,
          'deduct' => $deduct,
          'refund' => $refund,

          'waiting_approve' => $waiting_approve,
          'approved' => $approved,
          'not_approved' => $not_approved,
          'success' => $success,
          'course_detail' => $course_detail,
      );
      return json_encode($data);
    }

    public function user_profile_alerts(Request $request){

      if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
          $lang = 'en';
      }else{
          $lang = 'th';
      }

      $member_id = Auth::guard('members')->user()->member_id;
      $member_noti = MemberNotification::where('member_id', $member_id)
                      ->orderby('created_at','desc')
                      ->paginate(3);

      foreach ($member_noti as $noti) {
          if($noti->noti_type=="open_course_student" || $noti->noti_type=="open_course_teacher"){
              $price = Course::where('_id', '=', $noti->course_id)
                  ->select('course_price')
                  ->first();
              $noti->price = $price->course_price;
          }

          if(isset($noti->classroom_time_start)){
              $noti->classroom_date = $noti->classroom_date;
          }

          if(isset($noti->classroom_time_start) || isset($noti->classroom_time_end)){
              $noti->classroom_time_start = substr($noti->classroom_time_start,0,5);
              $noti->classroom_time_end = substr($noti->classroom_time_end,0,5);
          }
      }


      if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
          App::setLocale('en');
          $lang = "en";
      }
      else{
        App::setLocale('th');
          $lang = "th";
      }

      $data = array(
        'alerts' => $member_noti,
        'lang' => $lang,

      );

      return json_encode($data);
    }

    public function user_profile_request(Request $request){

      $request = RequestSubjects::where('request_member_id', Auth::guard('members')->user()->id)
          ->orderBy('created_at','desc')
          ->paginate(3);

      foreach ($request as $key => $value) {
        // $member = Member::where('_id', Auth::guard('members')->user()->id)->first();
        // $request[$key]->member_rate_start = $member->member_rate_start;
        // $request[$key]->member_rate_end = $member->member_rate_end;

        $aptitude = Aptitude::where('_id', $value->request_group_id)->first();
        $subject = Subject::where('_id', $value->request_subject_id)->first();

        if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
            $request[$key]->course_group = $aptitude->aptitude_name_en;
            $request[$key]->course_subject = $subject->subject_name_en;
        }
        else{
            $request[$key]->course_group = $aptitude->aptitude_name_th;
            $request[$key]->course_subject = $subject->subject_name_th;
        }
      }


      if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
        App::setLocale('en');
      }else {
        App::setLocale('th');
      }

      $data = array(
        'request' => $request,
      );
      return json_encode($data);
    }

    public function update(Request $request){
        $input =  $request['data'];
        $fname = trim($input['member_fname']);
        $lname = trim($input['member_lname']);
        $check_member_name = Member::where('member_id', '!=', $input['id'])->where('member_fname',$fname)
        ->where('member_lname',$lname)->first();
        if($check_member_name){
            if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
                App::setLocale('en');
                $text = [
                    'error' => "name and lastname Someone is already using it",
                ];
            }
            else{
                App::setLocale('th');
                $text = [
                    'error' => "ชื่อ-นามสกุลนี้มีคนใช้แล้ว",
                ];
            }

            return $text;
        }

        //เบอร์โทร10ตัว
        if(strlen($request->data['member_tell']) < 10)
        {
            if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
                App::setLocale('en');
                $text = [
                    'error' => "Phone number must contain ten characters.",
                ];
            }
            else{
                App::setLocale('th');
                $text = [
                    'error' => "เบอร์โทรศัพท์ ต้องมี 10 ตัว",
                ];
            };
            return $text;
        }

      $check_school = "";
      $text_school = "";
      if ($input['member_school']) { // มีโรงเรียนมาหรือเปล่า
        $data_school = Member::where('member_id', $input['id'])->where('member_status', '1')->first();

        if ($data_school->member_school) { /// มีโรงเรียนมา
          $school_teacher = School::where('school_status', '1')->where('school_student.student_email', $data_school->member_email)->first();
          if ($school_teacher) {
             $check_school = "มีรายชื่ออยู่ในโรงเรียน";
             $text_school = $input['member_school'];
          }else {
            $check_school = "ไม่มีรายชื่ออยู่ในโรงเรียน";
          }
        }else { // เลือกโรงเรียนมาแล้ว แต่ไม่เคยมีข้อมูลโรงเรียนในโปรไฟล์ตัวเอง
          $school_teacher = School::where('school_status', '1')->where('school_student.student_email', $data_school->member_email)->first();
          if ($school_teacher) {
             $check_school = "มีรายชื่ออยู่ในโรงเรียน (ไม่เคยมีข้อมูลโรงเรียนในโปรไฟล์ตัวเอง)";
             $text_school = $input['member_school'];
          }else {
            $check_school = "ไม่มีรายชื่ออยู่ในโรงเรียน (ไม่เคยมีข้อมูลโรงเรียนในโปรไฟล์ตัวเอง)";
          }
        }

      }else {
        $check_school = "ยังไม่ได้เลือกโรงเรียน";
        $text_school = "";
      }

      // dd($text_school,$check_school);

      if ($check_school == "ไม่มีรายชื่ออยู่ในโรงเรียน" || $check_school == "ไม่มีรายชื่ออยู่ในโรงเรียน (ไม่เคยมีข้อมูลโรงเรียนในโปรไฟล์ตัวเอง)") {
        if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
            App::setLocale('en');
            $text = [
                'error' => "No names in the school",
            ];
        }
        else{
            App::setLocale('th');
            $text = [
                'error' => "ไม่มีรายชื่ออยู่ในโรงเรียน",
            ];
        }

        return $text;

      }


      $user = Member::where('member_id', '=', $input['id'])->where('member_status', '1')->first();
      $user->member_fname = $input['member_fname'];
      $user->member_lname = $input['member_lname'];
      $user->member_tell = $input['member_tell'];
      $user->member_school = $text_school;
      $user->save();
      // dd(!empty($input['member_bank']));
      MemberBank::where('member_id',Auth::guard('members')->user()->id)->delete();
      $bank_master = BankMaster::get();
      if (!empty($input['member_bank'])) {
        foreach ($input['member_bank'] as $key => $val) {
         foreach ($bank_master as $key => $value) {
             if ($val[0] == $value->_id) {
               $data = array(
                 'member_id' => Auth::guard('members')->user()->_id,
                 'bank_id' => $value->_id,
                 'bank_name_en' => $value->bank_name_en,
                 'bank_name_th' => $value->bank_name_th,
                 'bank_account_number' => $val[1],
               );
               MemberBank::create($data);
             }
         }
       }
      }

      // if ($check_school != "ยังไม่ได้เลือกโรงเรียน") {
        // $school_update = School::where('_id', $input['member_school'])->where('school_student.student_email',$user->member_email)->update(['member_school' => $input['member_school']]);
        // $up_data=[];
        // $detal_date_School = School::where('_id', $input['member_school'])->get();
        // foreach ($detal_date_School[0]->school_student as $key => $value) {
        //   array_push($up_data,$value);
        // }
        // $user2 = Member::where('member_id', '=', $input['id'])->first();
        // $repeat_check = School::where('school_student.student_id', $input['id'])->first();
        // dd($detal_date_School,$user->member_email);
        // if ($check_school != "เลือกโรงเรียนถูก" && $repeat_check['school_student'] == null) {
        //   $data_school = ([
        //       'student_fname' => $input['member_fname'],
        //       'student_lname' => $input['member_lname'],
        //       'student_email' => $user2['member_email'],
        //       'student_tell' => $input['member_tell'],
        //   ]);
        //   array_push($up_data,$data_school);
        // }

        // dd($up_data);

        // $school_update = School::where('_id', $input['member_school'])->where('school_student.student_email',$user->member_email)->update(['member_school' => $input['member_school']]);
        // $school_update->school_student = $up_data;
        // $school_update->save();
      // }

      if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
          App::setLocale('en');
          $text = [
              'success' => "success",
          ];
      }
      else{
          App::setLocale('th');
          $text = [
              'success' => "บันทึกสำเร็จ",
          ];
      }

      return $text;
    }

    public function imgprofile(){
        return redirect('users/profile/')->with('imgprofile', 'success');
    }

    public function course(){
        $classroom = Classroom::all();
        dd($classroom);
    }

    public function updatemember()
    {
      // dd("111111");
      $member = Member::where('member_status', '=', '1')->get();

      foreach ($member as $key => $value) {
        if ($value->member_school) { //เช็คว่ามีโรงเรียนหรือเปล่า
            $school = School::where('school_status', '1')->where('_id', '=',$value->member_school)->first();
            // print_r('<pre>');
            // print_r($school['_id']);
            // print_r('<pre>');
            if ($school['_id'] == "") {
              $Course_update = Member::where('_id', $value->member_id)->update(['member_school' => ""]);
              // $Course_update = Member::where('member_school', $school->_id)->first();
              //   print_r('<pre>');
              //   print_r($Course_update->member_school);
              //   print_r('<pre>');
            }
        }
      }
    }
}
