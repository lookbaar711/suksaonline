<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\Aptitude;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Member;
use App\Models\Subject;
use App\Models\Other_subject;
use App\Models\Course;
use App\Models\Coin;
use App\Models\MemberNotification;
use App\Models\Withdraw;
use App\Models\Classroom;
use App\Models\RequestSubjects;
use App\Models\MapTeacherSubject;
use App\Models\CoinsLog;
use App\Models\Event;
use App\Models\BankMaster;
use App\Models\MemberBank;
use App\Models\School;
use Illuminate\Support\Str;
use Session;
use Auth;
use Mail;
use App\Models\AdminNotification;
use App;
use Lang;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function all() {
        $members = Member::where('member_status', '1')
            ->whereNotNull('member_Bday')
            ->orderby('created_at','desc')
            ->paginate(8);

             // ->get();

        $total_row = $members->count();
        if($total_row > 0) {
            foreach($members as $member) {

                // Check Image
                if($member->member_img!="") {
                    $img = asset ('storage/memberProfile/'.$member->member_img);
                } else {
                    $img = asset ('suksa/frontend/template/images/icons/blank_image.jpg');
                }

                $data_apptitude = '';
                $subject        = '';

                // Check subject for teacher
                foreach (array_keys($member->member_aptitude) as $key) {

                    if (count($member->member_aptitude[$key]) > 0) {

                        foreach ($member->member_aptitude[$key] as $items) {
                            $data_apptitude++;
                            if ($data_apptitude > 3) {
                                continue;
                            // } else {
                            //     $subject_detail = Subject::where('_id', $items)->first();
                            //     if(session('lang') == 'th'){
                            //         $subject .= $subject_detail->subject_name_th.' '.'';
                            //     }else{
                            //         $subject .= $subject_detail->subject_name_en.' '.'';
                            //     }

                            }
                        }
                    }
                }
            }
        }

        $view['members'] = $members;

        update_last_action();

        return view('frontend.members.member-all', $view);
    }

    public function get_profile_member($id) {
        $members = Member::where('member_status', '1')
            ->where('member_id',$id)
            ->whereNotNull('member_Bday')
            ->orderby('created_at','desc')
            ->get();

        $bank_master = BankMaster::orderBy('bank_name_th','asc')->get();

        //ข้อมูลธนาคารของ user
        $member_bank = MemberBank::where('member_id', $id)
                      ->orderby('created_at','asc')
                      ->get();

        $members->member_bank = $member_bank;
        // dd($members[0]->member_email);
        //ข้อมูลโรงเรียน
        $school = School::where('school_status', '1')->where('school_teacher.teacher_email', $members[0]->member_email)->get();
        // $members[0]->member_school = $school;
        // dd($school,$members[0]->member_school);
        // dd($school);
        $events = Event::orderBy('created_at','asc')->get();

        // $aptitude = array();
        $i = 0;
        $aptitude = Aptitude::orderBy('created_at','asc')->get();
        foreach ($aptitude as $key => $value) {
            $subject = [];
            foreach ($value['aptitude_subject'] as $ke => $val) {

                $check_subject = Subject::where('_id', $val)->first();

                if(isset($check_subject)){
                    if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
                        App::setLocale('en');
                        $subject[] = Subject::select('subject_name_en')->where('_id', $val)->first();
                    }
                    else{
                        App::setLocale('th');
                        $subject[] = Subject::select('subject_name_th')->where('_id', $val)->first();
                    }
                }
            }

            //get other subject by member
            if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
                $subject_other = Subject::select('subject_name_en')
                                ->where('member_id', Auth::guard('members')->user()->id)
                                ->where('aptitude_id', $value['_id'])
                                ->where('is_master', '0')
                                ->get();

                foreach ($subject_other as $key_other => $value_other) {
                    $subject[] = $value_other;
                }
            }
            else{
                $subject_other = Subject::select('subject_name_th')
                                ->where('member_id', Auth::guard('members')->user()->id)
                                ->where('aptitude_id', $value['_id'])
                                ->where('is_master', '0')
                                ->get();

                foreach ($subject_other as $key_other => $value_other) {
                    $subject[] = $value_other;
                }
            }

            $value['aptitude_subject'] = $subject;
        }

        if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
            App::setLocale('en');
            $lang = 'en';
        }
        else{
            App::setLocale('th');
            $lang = 'th';
        }

        // $y = 0;
        // foreach ($aptitude as $key => $value) {
        //   $subject_list = [];
        //   foreach ($value->subject as $ke => $subject) {
        //         $Subject = Subject::select('_id AS id','subject_name_th','subject_name_en')->where('_id', $subject)->first();
        //         $subject_list[] = $Subject;
        //     }
        //     $aptitude[$y++]['subject'] = $subject_list;
        // }


        $data = array(
            'members' => $members,
            'member_bank' => $member_bank,
            'events' => $events,
            'aptitude' => $aptitude,
            'bank_master' => $bank_master,
            'lang' => $lang,
            'school' => $school,
        );

        return json_encode($data);

    }

    public function fetch_data_menber(Request $request) {
        if($request->page) {
            $members = Member::where('member_status', '1')
                ->whereNotNull('member_Bday')
                ->orderby('created_at','desc')
                ->paginate(8);

            //$members['request_page'] = $request->paginate;

            update_last_action();

            return view('frontend.members.paginate_data', compact('members'))->render();
        }
    }

    public function search (Request $request) {
        if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
            App::setLocale('en');
        }
        else{
            App::setLocale('th');
        }

        $output = '';

        $query = $request->keyword;
        if($query != '') {
            $members = Member::where('member_status', '1')->whereNotNull('member_Bday')
                ->orWhere('member_fname', 'like', '%'.$query.'%')
                ->orWhere('member_lname', 'like', '%'.$query.'%')
                ->orWhere('member_rate_start', 'like', '%'.$query.'%')
                ->orWhere('member_rate_end', 'like', '%'.$query.'%')
                ->orderby('created_at','desc')
                ->get();

        } else {

            $members = Member::where('member_status', '1')
                ->whereNotNull('member_Bday')
                ->orderby('created_at','desc')
                ->paginate(8);
                // ->get();

            $view['members'] = $members;

        }

        $total_row = $members->count();
        if($total_row > 0) {
            foreach($members as $member) {

                // Check Image
                if($member->member_img!="") {
                    $img = asset ('storage/memberProfile/'.$member->member_img);
                } else {
                    $img = asset ('suksa/frontend/template/images/icons/blank_image.jpg');
                }

                $data_apptitude = '';
                $subject        = '';
                $subject_all = [];
                // Check subject for teacher
                foreach (array_keys($member->member_aptitude) as $key) {

                    if (count($member->member_aptitude[$key]) > 0) {
                        foreach ($member->member_aptitude[$key] as $i => $items) {

                            if(in_array($items, $subject_all)){

                            }else{
                                $data_apptitude++;
                                if ($data_apptitude > 3) {
                                    continue;
                                } else {
                                    $subject .= $items.' ';
                                }
                            }
                            $subject_all[] = $items;
                        }
                    }
                }

                $output .= '

                <div class="col-md-3 col-sm-6">
                    <div class="product-grid">

                        <div class="product-image">
                          <a href="'.url('members/detail/'.$member->id).'">
                            <img class="pic-1" src="'.$img.'" onerror="this.onerror=null;this.src='."'".'http://localhost:8000/suksa/frontend/template/images/icons/blank_image.jpg'."'".';" style="background-size: cover; height:300px; object-fit: cover">
                          </a>
                          <ul class="social">
                              <li>
                                <a href="'.url('members/detail/'.$member->id).'" data-tip="'.trans('frontend/members/title.detail').'"><i class="fa fa-search"></i></a>
                              </li>
                          </ul>
                        </div>

                        <div class="product-content">
                            <div class="title">
                                <a style="font-size: 14px;" href="'.url('members/detail/'.$member->id).'">'.$member->member_rate_start.' - '.$member->member_rate_end.' '.trans('frontend/members/title.coins_per_hour').'</a>
                            </div>
                            <div class="title">
                                <a href="'.url('members/detail/'.$member->id).'">'.$member->member_fname.' '.$member->member_lname.'</a>
                            </div>
                             <div class="title">
                                <a href="'.url('members/detail/'.$member->id).'">
                                '.$subject.'
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                ';
            }
        } else {
            $output = '
            <div class="product-content">
                <div class="title">
                    <label style="text-align: center;">ไม่พบข้อมูลผู้สอน</label>
                </div>
            </div

           ';
        }

        echo $output;
    }

    public function create(){
        if(Auth::guard('members')->user()){
            return redirect('/');
        }
        else{
            $subject_list = array();
            $aptitude = Aptitude::orderBy('created_at','asc')->get();

            foreach($aptitude as $i => $item) {
                foreach ($item->aptitude_subject as $key => $subject_id) {
                    $Subject = Subject::where('_id', $subject_id)->first();

                    if(isset($Subject)){
                        $subject_list[] = $Subject;
                    }
                }
                $aptitude[$i]->subject_detail = $subject_list;
                $subject_list = [];
            }
            //dd($aptitude);
            $month = array("มกราคม ","กุมภาพันธ์ ","มีนาคม ","เมษายน ","พฤษภาคม ","มิถุนายน ","กรกฎาคม ","สิงหาคม ","กันยายน ","ตุลาคม ","พฤศจิกายน ","ธันวาคม ");

            $events = Event::where('event_status', '1')
                            ->where('event_start_date', '<=', date('Y-m-d'))
                            ->where('event_end_date', '>=', date('Y-m-d'))
                            ->orderBy('created_at','asc')
                            ->get();

            update_last_action();

            return view('frontend.members.member-create', compact('aptitude','month','events'));
        }
    }

    public function store(Request $request) {
        if(Auth::guard('members')->user()){
            return redirect('/');
        }
        else{
            $check_member = Member::where('_id', '=', $request->member_update)->where('member_status', '=', '4')->first();
            if($check_member){
                return view('backend.404');
            }

            // $fname = trim($request['member_fname']);
            // $lname = trim($request['member_lname']);
            // $check_member_name = Member::where('member_fname',$fname)
            // ->where('member_lname',$lname)->first();
            // if($check_member_name){
            //     return redirect()->back()->with('create_user_error', 'fales');
            // }

            $last_member = Member::orderBy('mid','desc')->first();
            $mid = ((isset($last_member->mid)&&($last_member->mid!=null)))?$last_member->mid+1:1;

            $education = array();
            $member_Exp = array();
            $member_cong = array();
            $subject_prathom1 = array();
            $subject_prathom2 = array();
            $subject_matthayom1 = array();
            $subject_matthayom2 = array();
            $subject_highere = array();
            $subject_language = array();
            $subject_inter = array();
            $subject_admission = array();
            $fileName = array(); $fileName2 = array(); $fileName3 = array(); $fileName4 = array();

            if($request['member_education1']){
                $education['มัธยมศึกษา'] = $request['member_institution1'];
            }
            if($request['member_education2']){
                $education['ปริญญาตรี'] = [$request['member_major2'],$request['member_institution2']];
            }
            if($request['member_education3']){
                $education['ปริญญาโท'] = [$request['member_major3'],$request['member_institution3']];
            }
            if($request['member_education4']){
                $education['ปริญญาเอก'] = [$request['member_major4'],$request['member_institution4']];
            }

            foreach ($request['member_HJPl'] as $i => $value) {
                if($i!='0'){
                    if($value){
                        $member_Exp[] = [$request['member_HJPl'][$i],$request['member_HJPo'][$i],$request['member_HJExp'][$i]];
                    }
                }
            }

            foreach ($request['member_cong'] as $i => $value) {
                if($i!='0'){
                    if($value){
                        $member_cong[] = $value;
                    }
                }
            }

            if($request['txtfile1']){
                foreach ($request['txtfile1'] as $value) {
                    $fileName1[] = $value;
                }
                $fileName['บัตรประชาชน'] = $fileName1;
            }if($request['txtfile2']){
                foreach ($request['txtfile2'] as $value) {
                    $fileName2[] = $value;
                }
                $fileName['สำเนาศึกษา'] = $fileName2;
            }if($request['txtfile3']){
                foreach ($request['txtfile3'] as $value) {
                    $fileName3[] = $value;
                }
                $fileName['ใบผลการเรียน'] = $fileName3;
            }if($request['txtfile4']){
                foreach ($request['txtfile4'] as $value) {
                    $fileName4[] = $value;
                }
                $fileName['วุฒิบัตรอื่นๆ'] = $fileName4;
            }
            //อัพรูปประจำตัว
            if($request['member_img']){
                $member_img =  Str::random(7).time().".jpg";
                $url = public_path("/storage/memberProfile/".$member_img);
                $file = compress_image($request['member_img'], $url, 80);
            }elseif($request['update_img']){
                $member_img = $request['update_img'];
            }else{
                $member_img = "";
            }

            $birthday = explode("/",$request->member_Bday);
            $date = $birthday[2].'-'.$birthday[1].'-'.$birthday[0];

            $data = ([
                'member_email' => $request['member_email'],
                'member_password' => Hash::make($request['member_password']),
                'member_real_password' => $request['member_password'],
                'member_sername' => $request['member_sername'],
                'member_fname' => $request['member_fname'],
                'member_lname' => $request['member_lname'],
                'member_fullname' => $request['member_fname']." ".$request['member_lname'],
                'member_nickname' => $request['member_nickname'],
                'member_Bday' => $date,
                'member_tell' => $request['member_tell'],
                'member_idLine' => $request['member_idLine'],
                'member_address' => $request['member_address'],
                'member_idCard' => $request['member_idCard'],
                'member_rate_start' => intval(preg_replace("/[,]/", "", $request['member_rate_start'])),
                'member_rate_end' => intval(preg_replace("/[,]/", "", $request['member_rate_end'])),
                'member_education' => $education,
                'member_exp' => $member_Exp,
                'member_cong' => $member_cong,
                'member_img' => $member_img,
                'member_file' => $fileName,
                'member_strlenPass' => strlen($request['member_password']),
                'member_status' => '0',
                'member_coins' => '0',
                'member_type' => 'teacher',
                'member_role' => 'teacher',
                'member_teacher' => '1',
                'online_status' => '0',
                'event_id' => (isset($request['member_event']))?$request['member_event']:null,
                //'promotion_code' => (isset($request['promotion_code']))?$request['promotion_code']:null,
                'mid' => intval($mid),
                'member_lang' => 'th'
            ]);
            Member::create($data);

            //get last insert data
            $member = Member::orderBy('mid','desc')->first();
            $members_id = $member->_id;
            $members_name = $member->member_fullname;

            //insert other subject
            $group = [];
            $count_group = Aptitude::count();
            $aptitude_list = Aptitude::get();
            $idex_group = "0";
            for($i = 0; $i<$count_group; $i++){

                $group[$aptitude_list[$i]->_id] = array();

                if(isset($request['subject_'.$i]) || $request['chk_other'.$i]){
                    //ใส่ aptitude_id master เป็น key
                    $group[$aptitude_list[$i]->_id] = get_subject($request['subject_'.$i] , $request['other'.$i] , $request['chk_other'.$i] , $aptitude_list[$i]->_id, $member->_id);

                    //ใส่ aptitude_id ที่เลือกเป็น key
                    //$group[$request->group[$idex_group]] = get_subject($request['subject_'.$i] , $request['other'.$i] , $request['chk_other'.$i] , $aptitude_list[$i]->_id, $member->_id);
                    $idex_group = $idex_group+1;
                }
            }

            $member->member_id = $member->_id;
            $member->member_aptitude = $group;
            $member->save();

            if($request->member_update!=""){
                $member_update = Member::where('_id', '=', $request->member_update)->where('member_status', '1')->first();
                $member_update->member_status = '4';
                $member_update->save();
            }

            if($members_id!="") {
                foreach ($group as $key => $aptitude) {
                    foreach ($aptitude as $aptitude_id => $subject) {
                        $map_teacher_data = ([
                            'members_id' => $members_id,
                            'members_name' => $members_name,
                            'aptitude_id' => $key,
                            'subject_id' => $subject
                        ]);

                        MapTeacherSubject::create($map_teacher_data);
                    }
                }
            }

            //insert noti to admin
            $admin_noti = new AdminNotification();
            $admin_noti->member_id = $member->member_id;
            $admin_noti->member_fullname = $member->member_fname.' '.$member->member_lname;
            $admin_noti->noti_to = 'all';
            $admin_noti->noti_type = 'register_teacher';
            $admin_noti->noti_status = '0';
            $admin_noti->save();

            //send noti
            sendAdminNoti($admin_noti->noti_to);

            return redirect('/')->with('alert', 'member');
        }
    }

    public function checkEmail(Request $request){
        $members = Member::select('member_email','member_status')
        ->where('member_status', '!=', '2')
        ->where('member_status', '!=', '3')
        ->Where('member_status', '!=', '4')
        ->get();
        return $members;
    }

    public function checkFullName(Request $request){
        $check_member_name = Member::where('member_fname', '=', $request->fname)
                            ->where('member_lname', '=', $request->lname)
                            ->where('member_status', '!=', '2')
                            ->where('member_status', '!=', '3')
                            ->Where('member_status', '!=', '4')
                            ->count();

        if($check_member_name > 0){
            $status = 'false';
            return $status;
        }else{
            $status = 'true';
            return $status;
        }


        //วิธีที่ 1
         //$request->get('fname');
          //วิธีที่ 2
        // $inputs = $request->all();
        // $fname = trim($inputs->fname);
        // $lname = trim($inputs->lname);
         //วิธีที่ 3
        // $inputs = $request->all();
        // $fname = trim($inputs['fname']);
        // $lname = trim($inputs['lname']);
        // $check_member_name = Member::where('member_fname',$fname)
        // ->where('member_lname',$lname)->first();
        // if($check_member_name){
        //     $status = 'true';
        //     return response()->json(['status'=>$status], 200);
        // }else{
        //     $status = 'false';
        //     return response()->json(['status'=>$status], 200);
        // }
    }

    public function get_email_student(Request $request){
        $members = Member::select('member_email','member_status')
        ->where('member_status', '=', '1')
        ->Where('_id', '<>', Auth::guard('members')->user()->id)
        ->get();
        return $members;
    }

    public function upFile(Request $request){
        foreach($request->userpic as $i => $item){
            $fielName =  Str::random(7).time().".jpg";

            $url = public_path("/storage/fileUpload/".$fielName);
            $filename = compress_image($item, $url, 50);

            $save[] = $fielName;
        }
        return $save;
    }

    public function profile_cousres(Request $request){
        $cousres = Course::where('member_id', Auth::guard('members')->user()->id)->where('course_status',"!=","delete")
            ->orderBy('created_at','desc')
            ->paginate(3);

        foreach ($cousres as $key => $value) {
            $count_date = count($value->course_date);
            $classroom = Classroom::where('course_id', $value->_id)->first();

            $classroom_status = 2;

            if($classroom){
                $cousres[$key]->student = count($classroom->classroom_student);
                $cousres[$key]->student_name = $classroom->classroom_student;

                $check_classroom = Classroom::where('course_id', $value->_id)
                                    ->where('classroom_date', date('Y-m-d'))
                                    ->first();

                $classroom_status = (isset($check_classroom->classroom_status))?$check_classroom->classroom_status:2;
                // dd($check_classroom);
                // $status_check = 0;
                // foreach ($check_classroom->classroom_student as $key_student => $value_student) {
                  // print_r('<pre>');
                //   print_r($value_student['student_checkin']);
                  // print_r('<pre>');
                //   if ($value_student['student_checkin'] == 1) {
                //     $status_check += 1;
                //   }
                // }
                if($check_classroom && $value->course_category == "School"){
                    $cousres[$key]['student_status'] = 100;
                }else{
                    $cousres[$key]['student_status'] = $value->course_student_limit == 0 ? 0:((count($classroom->classroom_student) / $value->course_student_limit) * 100);
                }
            }
            else{
              // $cousres[$key]['student_status'] = ((count($classroom->classroom_student) / $value->course_student_limit) * 100);
                $cousres[$key]['student_status'] = 0;
            }

            $cousres[$key]->classroom_status = $classroom_status;

            $aptitude = Aptitude::where('_id', $value->course_group)->first();
            $subject = Subject::where('_id', $value->course_subject)->first();

            if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
                $cousres[$key]->course_group = $aptitude->aptitude_name_en;
                $cousres[$key]->course_subject = $subject->subject_name_en;
            }
            else{
                $cousres[$key]->course_group = $aptitude->aptitude_name_th;
                $cousres[$key]->course_subject = $subject->subject_name_th;
            }


            $link_open = MemberNotification::where('course_id', '=', $value->_id)
                        ->where('member_id', '=', Auth::guard('members')->user()->id)
                        ->where('classroom_date', '=', date('Y-m-d'))
                        ->where('noti_type', '=', 'open_course_teacher')
                        ->first();

            if($link_open){
                $cousres[$key]->link_open = $link_open->_id;
            }
            else{
                $cousres[$key]->link_open = '';
            }
        }
        // dd($cousres);

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
            'cousres' => $cousres,
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

    public function profile_coins(Request $request){
        // $data_coins =[];
        // $count = 0;
        $coins = CoinsLog::where('member_id', '=', Auth::guard('members')->user()->id)
                ->orderBy('created_at','desc')->paginate(3);

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

    public function profile_alerts(Request $request){

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
                  ->where('course_status',"!=","delete")
                  ->first();
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

    public function profile_request(Request $request){
      // $request_data = [];
      $request_Subjects = RequestSubjects::where('request_teachers', Auth::guard('members')->user()->id)
          ->orderBy('created_at','desc')->paginate(3);
          // dd($request_Subjects);
      foreach ($request_Subjects as $key => $value) {
        $member = Member::where('_id', Auth::guard('members')->user()->id)->where('member_status', '1')->first();
        // dd($value->member_rate_start,$value);
        $request_Subjects[$key]->member_rate_start = number_format($member->member_rate_start);
        $request_Subjects[$key]->member_rate_end = number_format($member->member_rate_end);

        $aptitude = Aptitude::where('_id', $value->request_group_id)->first();
        $subject = Subject::where('_id', $value->request_subject_id)->first();

        if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
            $request_Subjects[$key]->course_group = $aptitude->aptitude_name_en;
            $request_Subjects[$key]->course_subject = $subject->subject_name_en;
        }
        else {
            $request_Subjects[$key]->course_group = $aptitude->aptitude_name_th;
            $request_Subjects[$key]->course_subject = $subject->subject_name_th;
        }
        // array_push($request_data,$value);
        // $request_data[] = [$value];
      }
      // dd($request_data);

        if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
          App::setLocale('en');
        }
        else {
          App::setLocale('th');
        }

        $data = array(
          'request' => $request_Subjects,
        );
      return json_encode($data);
    }

    public function open_modal_request($id){

        $request = RequestSubjects::where('_id', $id)
            ->orderBy('created_at','desc')
            ->get();

        foreach ($request as $key => $value) {
          $member = Member::where('_id', Auth::guard('members')->user()->id)->where('member_status', '1')->first();
          $request[$key]->member_rate_start = $member->member_rate_start;
          $request[$key]->member_rate_end = $member->member_rate_end;

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

      return json_encode($request);
    }

    public function profile(){
        $members = Member::where('_id', '=', Auth::guard('members')->user()->id)->where('member_status', '1')->first();

        //ข้อมูลธนาคาร
        $bank_master = BankMaster::orderBy('bank_name_th','asc')->get();
        // $data_bank = [];
        // for ($i=0; $i < count($members->member_bank); $i++) {
        //   foreach ($bank_master as $key_bank => $value_bank) {
        //     if ($value_bank->_id == $members->member_bank[$i][0]) {
        //       $data_bank_arr = array(
        //         'bank_name_th' => $value_bank->bank_name_th,
        //         'bank_name_en' => $value_bank->bank_name_en,
        //         'bank_id' => $value_bank->_id,
        //         'bank_account_number' => $members->member_bank[$i][1],
        //       );
        //       $data_bank[$i] = $data_bank_arr;

        //     }
        //   }
        // }
        //return $data_bank;

        //ข้อมูลธนาคารของ user
        $member_bank = MemberBank::where('member_id', Auth::guard('members')->user()->_id)
                      ->orderby('created_at','asc')
                      ->get();

        $members->member_bank = $member_bank;
        // dd($members->member_school);
        $members->member_school == "";
        if ($members->member_school) {
          //ข้อมูลโรงเรียน
          $school = School::where('school_status', '1')->where('school_teacher.teacher_email', $members->member_email)->get();
          // $school = School::select('school_name_th','school_name_en')->where('school_teacher.teacher_id', Auth::guard('members')->user()->id)->get();
          $members->member_school = $school;
          //return $member_bank;
        }


        $members->detail_aptitude = [];
        if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
            foreach (array_keys($members->member_aptitude) as $key => $aptitude_id) {
                $aptitude = Aptitude::where('_id', $aptitude_id)->first();
                if($aptitude){
                    $detail_subject[$aptitude->aptitude_name_en] = [];
                    foreach ($members->member_aptitude[$aptitude_id] as $key => $subject_id) {
                        $subject = Subject::where('_id', $subject_id)->first();
                        if($subject){
                            $detail_subject[$aptitude->aptitude_name_en][] = $subject->subject_name_en;
                        }
                    }
                }
            }
        }
        else{
            foreach (array_keys($members->member_aptitude) as $key => $aptitude_id) {
                $aptitude = Aptitude::where('_id', $aptitude_id)->first();
                if($aptitude){
                    $detail_subject[$aptitude->aptitude_name_th] = [];
                    foreach ($members->member_aptitude[$aptitude_id] as $key => $subject_id) {
                        $subject = Subject::where('_id', $subject_id)->first();
                        if($subject){
                            $detail_subject[$aptitude->aptitude_name_th][] = $subject->subject_name_th;
                        }
                    }
                }
            }
        }
        if(isset($detail_subject)){
            $members->detail_aptitude = $detail_subject;
        }else{
            $members->detail_aptitude = [];
        }

        $cousres = Course::where('member_id', Auth::guard('members')->user()->id)->where('course_status',"!=","delete")
            ->orderBy('created_at','desc')
            ->get();

        foreach ($cousres as $key => $value) {
            $count_date = count($value->course_date);
            $classroom = Classroom::where('course_id', $value->id)->first();
            if($classroom){
                $cousres[$key]->student = count($classroom->classroom_student);
                $cousres[$key]->student_name = $classroom->classroom_student;
            }

            $link_open = MemberNotification::where('course_id', $value->id)
                        ->where('classroom_date', date('Y-m-d'))
                        ->where('noti_type', 'open_course_teacher')
                        ->first();
            if($link_open){
                if(date('H:i:s') > date("H:i:s",strtotime($link_open->classroom_time_start." -10 minutes")) &&
                date('H:i:s') < $link_open->classroom_time_end){
                    $cousres[$key]->link_open = $link_open->id;
                }elseif(date('H:i:s') > $link_open->classroom_time_end) {
                    $cousres[$key]->status_show = "false";
                }
            }else{
                if(date('Y-m-d H:i:s') > $value->course_date[0]['date']." ".$value->course_date[0]['time_start']){
                    $cousres[$key]->status_show = "false";
                }
            }
            if(date('Y-m-d') > $value->course_date[$count_date-1]['date']){
                $cousres[$key]->status_show = "false";
            }

            if($value->course_status == "close" && date('Y-m-d H:i:s') > $value->course_date[0]['date']." ".$value->course_date[0]['time_start']
            ){
                $cousres[$key]->status_show = "false";
            }

        }

        //dd($cousres);
        //ประวัติ Coins
        $data_coins =[];
        $count = 0;
        $coins = Coin::where('member_id', '=', Auth::guard('members')->user()->id)->get();
        foreach ($coins as $key => $value) {
            $data_coins[$key]['type'] = "เติม";
            $data_coins[$key]['number'] = $value->coin_number;
            $data_coins[$key]['date'] = date('d/m/Y', strtotime($value->coin_date));
            $data_coins[$key]['time'] = date('H:i', strtotime($value->coin_time));
            $data_coins[$key]['bank'] = $value->coin_bank;
            $data_coins[$key]['status'] = $value->coin_status;
            $data_coins[$key]['date_time'] = date('Y-m-d', strtotime($value->coin_date))." ".$value->coin_time;
            $count = $key;
        }
        $withdraw = Withdraw::where('member_id', '=', Auth::guard('members')->user()->id)->get();
        foreach ($withdraw as $key => $value) {
            $data_coins[$count+$key+1]['type'] = "ถอน";
            $data_coins[$count+$key+1]['number'] = $value->withdraw_coin_number;
            $data_coins[$count+$key+1]['date'] = date('d/m/Y', strtotime($value->withdraw_date));
            $data_coins[$count+$key+1]['time'] = date('H:i', strtotime($value->withdraw_time));
            $data_coins[$count+$key+1]['bank'] = $value->withdraw_bank;
            $data_coins[$count+$key+1]['status'] = $value->withdraw_status;
            $data_coins[$count+$key+1]['date_time'] = date('Y-m-d', strtotime($value->withdraw_date))." ".$value->withdraw_time;
        }
        $keys  = array_column($data_coins, 'date_time');
        array_multisort($keys , SORT_DESC, $data_coins);

        //ประวัติการแจ้งเตือน
        $member_noti = MemberNotification::where('member_id', '=', Auth::guard('members')->user()->id)->get();
        foreach ($member_noti as $noti) {
            $noti->classroom_date = $noti->classroom_date;
            $noti->classroom_time_start = substr($noti->classroom_time_start,0,5);
            $noti->classroom_time_end = substr($noti->classroom_time_end,0,5);
        }



        update_last_action();

        return view('frontend.members.member-profile', compact('members','cousres', 'data_coins'));
    }

    public function detail($id){
        $members = Member::where('_id', $id)->where('member_status', '1')->first();

        $members->detail_aptitude = [];
        if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
            foreach (array_keys($members->member_aptitude) as $key => $aptitude_id) {
                $aptitude = Aptitude::where('_id', $aptitude_id)->first();
                if($aptitude){
                    $detail_subject[$aptitude->aptitude_name_en] = [];
                    foreach ($members->member_aptitude[$aptitude_id] as $key => $subject_id) {
                        $subject = Subject::where('_id', $subject_id)->first();
                        if($subject){
                            $detail_subject[$aptitude->aptitude_name_en][] = $subject->subject_name_en;
                        }
                    }
                }
            }
        }
        else{
            foreach (array_keys($members->member_aptitude) as $key => $aptitude_id) {
                $aptitude = Aptitude::where('_id', $aptitude_id)->first();
                if($aptitude){
                    $detail_subject[$aptitude->aptitude_name_th] = [];
                    foreach ($members->member_aptitude[$aptitude_id] as $key => $subject_id) {
                        $subject = Subject::where('_id', $subject_id)->first();
                        if($subject){
                            $detail_subject[$aptitude->aptitude_name_th][] = $subject->subject_name_th;
                        }
                    }
                }
            }
        }
        $members->detail_aptitude = $detail_subject;

        $members->member_school == "";
        if ($members->member_school) {
          //ข้อมูลโรงเรียน
          $school = School::select('school_name_th','school_name_en')->where('school_status', '1')->where('school_teacher.teacher_email', $members->member_email)->get();
          $members->member_school = $school;
          //return $member_bank;
        }


        $course = Course::where('member_id', $id)->where('course_status',"!=","delete")->get();
        foreach ($course as $key => $value) {
            $classroom = Classroom::where('course_id', $value->id)
            ->first();
            if($classroom){
                $course[$key]->student = count($classroom->classroom_student);
                $course[$key]->student_name = $classroom->classroom_student;
            }
            if(Auth::guard('members')->user()){
                $classroom_register = Classroom::where('course_id', $value->id)
                ->where('classroom_student.student_id', '=',  Auth::guard('members')->user()->id)
                ->first();
                if($classroom_register){
                    $course[$key]->register = "register";
                }
            }
            $aptitude = Aptitude::where('_id', $value->course_group)->first();
            $course[$key]->aptitude_detail = $aptitude;

            $subject = Subject::where('_id', $value->course_subject)->first();
            $course[$key]->subject_detail = $subject;
        }

        // $members_id = $id;

        update_last_action();

        return view('frontend.members.member-detail', compact('members', 'course'));
    }

    public function member_course(Request $request){
        $members = Member::where('_id', $request->id)->where('member_status', '1')->first();
        $course = Course::where('member_id', $request->id)->where('course_status',"!=","delete")->paginate(4);
        $members_id = $request->id;

        foreach ($course as $key => $value) {
            $classroom = Classroom::where('course_id', $value->id)
            ->first();
            if($classroom){
                $course[$key]->student = count($classroom->classroom_student);
                $course[$key]->student_name = $classroom->classroom_student;
            }
            if(Auth::guard('members')->user()){
                $classroom_register = Classroom::where('course_id', $value->id)
                ->where('classroom_student.student_id', '=',  Auth::guard('members')->user()->id)
                ->first();
                if($classroom_register){
                    $course[$key]->register = "register";
                }
            }
        }

        if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
            App::setLocale('en');
        }
        else{
            App::setLocale('th');
        }

        $free_course = Lang::get('frontend/members/title.free_course');
        $course_document = Lang::get('frontend/members/title.course_document');
        $download_button = Lang::get('frontend/members/title.download_button');
        $course_detail = Lang::get('frontend/members/title.course_detail');

        $data = array(
          'course' => $course,
          'free_course' => $free_course,
          'course_document' => $course_document,
          'download_button' => $download_button,
          'course_detail' => $course_detail,
        );

        // update_last_action();

        return json_encode($data);

        // return view('frontend.members.member-detail',  compact('members', 'course', 'members_id'));
    }

    public function edit(){
        $members = Member::where('_id', '=', Auth::guard('members')->user()->id)->where('member_status', '1')->first();
        $month = array("มกราคม ","กุมภาพันธ์ ","มีนาคม ","เมษายน ","พฤษภาคม ","มิถุนายน ","กรกฎาคม ","สิงหาคม ","กันยายน ","ตุลาคม ","พฤศจิกายน ","ธันวาคม ");
        $chk = '';
        foreach(array_keys($members->member_aptitude) as $i => $item) {
            if($members->member_aptitude[$item]){
                    $Subject = Subject::whereIn('subject_id', $members->member_aptitude[$item])->get();
                    $chk .= $Subject;
                    $value[get_aptitude_level($item)] = $Subject;
            }
        }
        if($chk){
            $members['subject_detail'] = $value;
        }else{
            $members['subject_detail'] = [];
        }


        $aptitude = Aptitude::all();
        foreach($aptitude as $i => $item) {
            if($aptitude[$i]->aptitude_subject){
                    $Subject = Subject::whereIn('subject_id', $aptitude[$i]->aptitude_subject)->get();
                    $data[$item->aptitude_name] = $Subject;
            }
        }
        $aptitude['subject_detail'] = $data;

        $events = Event::where('event_status', '1')
                            ->where('event_start_date', '<=', date('Y-m-d'))
                            ->where('event_end_date', '>=', date('Y-m-d'))
                            ->orderBy('created_at','asc')
                            ->get();

        update_last_action();

        return view('frontend.members.member-edit', compact('members','month','aptitude','events'));
    }

    public function imgprofile(Request $request){
        $fielName =  Str::random(7).time().".jpg";

        $url = public_path("/storage/memberProfile/".$fielName);
        $file = compress_image($request->img, $url, 80);

        $members = Member::where('_id', Auth::guard('members')->user()->id)->where('member_status', '1')->first();
        $path = public_path("/storage/memberProfile/".$members->member_img);
        File::delete($path);

        $members->member_img = $fielName;
        $members->save();

        return $fielName;
    }

    public function imgprofilereload() {
        return redirect('members/profile/')->with('imgprofile', 'success');
    }

    public function changeaccount() {
        if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
            App::setLocale('en');
        }
        else{
            App::setLocale('th');
        }

        $members = Member::where('_id', '=',  Auth::guard('members')->user()->id)->where('member_status', '1')->first();
        if($members->member_role == "teacher"){
            $members->member_role = "student";
            $members->save();
            return redirect('/')->with('changeaccount', trans('frontend/layouts/modal.student_role'));
        } else{
            $members->member_role = "teacher";
            $members->save();
            return redirect('/')->with('changeaccount', trans('frontend/layouts/modal.teacher_role'));
        }
    }

    public function updatedata(Member $members){
        if($members->member_status=='2'){
            $subject_list = array();
            $aptitude = Aptitude::orderBy('created_at','asc')->get();;

            foreach($aptitude as $i => $item) {
                foreach ($item->aptitude_subject as $key => $subject_id) {
                    $Subject = Subject::where('_id', $subject_id)->first();
                    $subject_list[] = $Subject;
                }
                $aptitude[$i]->subject_detail = $subject_list;
                $subject_list = [];
            }
            $members->detail_aptitude = [];
            foreach (array_keys($members->member_aptitude) as $key => $aptitude_id) {
                $aptitude_detail = Aptitude::where('_id', $aptitude_id)->first();
                if($aptitude_detail){
                    $detail_subject[$aptitude_detail->aptitude_name_en] = [];
                    foreach ($members->member_aptitude[$aptitude_id] as $key => $subject_id) {
                        $subject = Subject::where('_id', $subject_id)->first();
                        if($subject){
                            $detail_subject[$aptitude_detail->aptitude_name_en][] = $subject->subject_name_en;
                        }
                    }
                }
            }
            $members->detail_aptitude = $detail_subject;
            //dd($members->detail_aptitude["primary school 1"]);
                // $subject = Subject::where('_id', '5d64aab8108c191ca400234d');
                // $subject->orderby('created_at','desc');
                // $res = $subject->get();
                // dd($res);

                //$aptitude['subject_detail'] = $data;

            $events = Event::where('event_status', '1')
                            ->where('event_start_date', '<=', date('Y-m-d'))
                            ->where('event_end_date', '>=', date('Y-m-d'))
                            ->orderBy('created_at','asc')
                            ->get();

            update_last_action();
            return view('frontend.members.member-update', compact('members', 'aptitude', 'events'));
        }
        else{
            update_last_action();
            return view('backend.404');
        }
    }

    public function list_student(Request $request){

        $classroom = Classroom::where('course_id', '=', $request->course_id)
            ->select('classroom_student')
            ->groupBy('course_id')->first();
        $students = $classroom->classroom_student;

        update_last_action();

        //return $students;
        return view('frontend.members.list_student', compact('students'));
    }

    public function student_status($course_id){
        $course = Course::where('_id', '=', $course_id)->first();
        $i = '0';
        // print_r($course->course_category);
        $list_student = [];
        if (($course->course_category == "Public") || ($course->course_category == "School")) {
          $classroom = Classroom::where('course_id', $course->_id)->first();
          foreach ($classroom->classroom_student as $key => $value) {

                $list_student[$i]['student_name'] = $value['student_fname']." ".$value['student_lname'];
                $list_student[$i]['student_email'] = $value['student_email'];
                $list_student[$i]['student_tell'] = $value['student_tell'];
                $list_student[$i]['student_date'] = date('d/m/Y', strtotime($value['student_date_regis']));
                $list_student[$i]['category'] = $course->course_category;
                $list_student[$i]['student_id'] = $value['student_id'];
                $i = $i+1;
          }
        }else {
          foreach ($course->course_student as $value) {
              $student = Member::where('member_email', '=', $value)->where('member_status', '1')->first();

              if($student){
                $list_student[$i]['student_name'] = $student->member_fname." ".$student->member_lname;
                $list_student[$i]['student_email'] = $student->member_email;
                $list_student[$i]['student_tell'] = $student->member_tell;
                $list_student[$i]['category'] = "Private";
                $list_student[$i]['student_id'] = $student->_id;
                $status = Classroom::where('course_id', '=', $course_id)
                    ->where('classroom_student.student_id', '=', $student->id)
                    ->first();

                if($status){
                    $list_student[$i]['student_status'] = "1";
                    $list_student[$i]['student_date'] = date('d/m/Y', strtotime($status->created_at));
                }else{
                    $list_student[$i]['student_status'] = "0";
                }
                $i = $i+1;
              }else {
                // dd("0000");
                $list_student[$i] = [
                  'student_email' => "-",
                  'student_name' => "-",
                  'student_status' => "0",
                  'student_tell' => "-",
                  'student_date' => "-",
                  'category' => "Private",
                  'student_id' => ''
                ];
              }
          }
        }


        return json_encode($list_student);
    }

    public function update_profile_member(Request $request){
      // dd($request);
        $update_aptitude = array();
        $aptitude = Aptitude::orderBy('created_at','asc')->get();
        foreach ($aptitude as $key1 => $value1) {
            $check_same = 0;
            $update_aptitude[$value1['_id']] = array();

            foreach ($request['data']['member_aptitude'] as $key2 => $value2) {
                //$value1['_id'] = aptitude_id ทั้งหมด
                //$key2 = aptitude_id ที่เลือก

                if($value1['_id'] == $key2){
                    $check_same = 1;
                    $update_aptitude[$value1['_id']] = $value2;
                }
            }
        }

        $input =  $request['data'];

        //dd($request['data']['member_aptitude']);
        //dd($request['data']['member_aptitude']['5dc9f4d1108c1930d800560e']);
        $education = array();
        if(!empty($input['member_institution1'])){
            $education['มัธยมศึกษา'] = $input['member_institution1'];
        }
        if(!empty($input['member_institution2'])){
            $education['ปริญญาตรี'] = [$input['member_major2'],$input['member_institution2']];
        }
        if(!empty($input['member_institution3'])){
            $education['ปริญญาโท'] = [$input['member_major3'],$input['member_institution3']];
        }
        if(!empty($input['member_institution4'])){
            $education['ปริญญาเอก'] = [$input['member_major4'],$input['member_institution4']];
        }

        // dd($education);

        // dd($input['member_bank']);
        $origDate = $input['member_Bday'];
        $date = str_replace('/', '-', $origDate );
        $member_Bday = date("Y-m-d", strtotime($date));

        $fname = trim($input['member_fname']);
        $lname = trim($input['member_lname']);
        $check_member_name = Member::where('member_id', '!=', $input['member_id'])->where('member_fname',$fname)->where('member_lname',$lname)->where('member_status','1')->first();

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
          $data_school = Member::where('member_id', $input['member_id'])->where('member_status', '1')->first();

          if ($data_school->member_school) { /// มีโรงเรียนมา
            $school_teacher = School::where('school_status', '1')->where('school_teacher.teacher_email', $data_school->member_email)->first();
            if ($school_teacher) {
               $check_school = "มีรายชื่ออยู่ในโรงเรียน";
               $text_school = $input['member_school'];
            }else {
              $check_school = "ไม่มีรายชื่ออยู่ในโรงเรียน";
            }
          }else { // เลือกโรงเรียนมาแล้ว แต่ไม่เคยมีข้อมูลโรงเรียนในโปรไฟล์ตัวเอง
            $school_teacher = School::where('school_status', '1')->where('school_teacher.teacher_email', $data_school->member_email)->first();
            if ($school_teacher) {
               $check_school = "มีรายชื่ออยู่ในโรงเรียน (ไม่เคยมีข้อมูลโรงเรียนในโปรไฟล์ตัวเอง)";
               $text_school = $input['member_school'];
            }else {
              $check_school = "ไม่มีรายชื่ออยู่ในโรงเรียน (ไม่เคยมีข้อมูลโรงเรียนในโปรไฟล์ตัวเอง)";
            }
          }

        }else {
          $check_school = "ยังไม่ได้เลือกโรงเรียน";
        }


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

        // dd($check_school,$text_school);

        $member_update = Member::where('member_id', '=', $input['member_id'])->where('member_status', '1')->first();
        $member_update->member_Bday = $member_Bday;
        $member_update->member_email = $input['member_email'];
        $member_update->member_tell = $input['member_tell'];
        $member_update->member_idLine = $input['member_idLine'];
        $member_update->member_sername = $input['member_sername'];
        $member_update->member_fname = $input['member_fname'];
        $member_update->member_lname = $input['member_lname'];
        $member_update->member_nickname = $input['member_nickname'];
        $member_update->member_rate_start = intval(preg_replace("/[,]/", "", $input['member_rate_start']));
        $member_update->member_rate_end = intval(preg_replace("/[,]/", "", $input['member_rate_end']));
        $member_update->member_address = $input['member_address'];
        $member_update->member_exp = ($input['member_exp']=='')?array():$input['member_exp'];
        $member_update->member_cong = ($input['member_cong']=='')?array():$input['member_cong'];
        $member_update->member_aptitude = $update_aptitude;
        $member_update->member_school = $text_school;
        $member_update->member_education = $education;
        $member_update->save();

        // if ($check_school != "ยังไม่ได้เลือกโรงเรียน") {
        //   $up_data=[];
        //   $up_data_u=[];
        //   $detal_date_School = School::where('_id', $input['member_school'])->get();
        //   // dd($detal_date_School[0]->school_teacher);
        //   if ($detal_date_School) {
        //     foreach ($detal_date_School[0]->school_teacher as $key => $value) {
        //       array_push($up_data,$value);
        //     }
        //
        //     foreach ($detal_date_School[0]->school_student as $key => $value) {
        //       array_push($up_data_u,$value);
        //     }
        //   }
        //
        //
        //   if ($check_school != "เลือกโรงเรียนถูก") {
        //     $data_school = ([
        //         'teacher_id' => $input['member_id'],
        //         'teacher_fname' => $input['member_fname'],
        //         'teacher_lname' => $input['member_lname'],
        //         'teacher_email' => $input['member_email'],
        //         'teacher_tell' => $input['member_tell'],
        //     ]);
        //     array_push($up_data,$data_school);
        //   }
        //   if ($check_school != "เลือกโรงเรียนถูก") {
        //     $data_school_u = ([
        //         'student_id' => $input['member_id'],
        //         'student_fname' => $input['member_fname'],
        //         'student_lname' => $input['member_lname'],
        //         'student_email' => $input['member_email'],
        //         'student_tell' => $input['member_tell'],
        //     ]);
        //
        //
        //     array_push($up_data_u,$data_school_u);
        //   }
        //   // dd($up_data);
        //
        //   $school_update = School::where('_id', $input['member_school'])->first();
        //   $school_update->school_teacher = $up_data;
        //   $school_update->school_student = $up_data_u;
        //   $school_update->save();
        // }


        $del = MemberBank::where('member_id',Auth::guard('members')->user()->id)->delete();
        if (!empty($input['member_bank'])) {
          foreach ($input['member_bank'] as $key => $value) {
              $bank_master = BankMaster::where('_id',$value[0])->first();

              $data = ([
                  'member_id' => Auth::guard('members')->user()->_id,
                  'bank_id' => $value[0],
                  'bank_name_en' => $bank_master->bank_name_en,
                  'bank_name_th' => $bank_master->bank_name_th,
                  'bank_account_number' => $value[1],
              ]);
              // dd($data);
              MemberBank::create($data);
          }
        }


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

    public function downloadStudentDoc(){
        return response()->download(storage_path("app/public/document/Student_Document_Suksa_Online.pdf"));
    }

    public function downloadTeacherDoc(){
        return response()->download(storage_path("app/public/document/Teacher_Document_Suksa_Online.pdf"));
    }

    public function check_email_forgot_password(Request $request){
        $input =  $request['data'];

        $check_email = "false";
        $email_all = Member::where('member_email', $input)->where('member_status', '1')->first();

        if ($email_all) {
          $check_email = "true";

          srand((double)microtime()*10000000);
        	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        	$ret_str = "";
        	$num = strlen($chars);
        	for($i = 0; $i < 8; $i++)
        	{
        		$ret_str.= $chars[rand()%$num];
        		$ret_str.="";
        	}
          // Hash::make($email_all['member_password']);
        	// dd($ret_str,$email_all['member_email'],$email_all['member_id'],$email_all['member_fname'].' '.$email_all['member_lname']);

          $member_update = Member::where('member_email', $input)->where('member_status', '1')->first();
          $member_update->member_password = Hash::make($ret_str);
          $member_update->save();

          //send email
          $titel = 'Reset Password';
          $subject = 'ระบบได้ทำการ Reset Password ให้เรียบร้อย';
          $from_name = 'Suksa Online';
          $from_email = 'noreply@suksa.online';
          $to_name = $email_all['member_fname'].' '.$email_all['member_lname'];
          $to_email = $email_all['member_email'];
          $description = '';
          $data = array(
              'titel' => $titel,
              'subject'=> $subject,
              'username' => $email_all['member_email'],
              'password' => $ret_str,
              'description' => $description
          );

          Mail::send('frontend.send_email_forgot_password', $data, function($message) use ($titel, $from_name, $from_email, $to_name, $to_email) {
              $message->from($from_email, $from_name);
              $message->to($to_email, $to_name);
              $message->subject($titel);
          });

        }
        // dd($email_all,$check_email);
        if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
            App::setLocale('en');
            if ($check_email == "true") {
              $text = [
                  'success' => "The system has sent a new password to the registered email",
              ];
            }else {
              $text = [
                  'false' => "Data not found. Please make a transaction again",
              ];
            }
        }
        else{
            App::setLocale('th');
            if ($check_email == "true") {
              $text = [
                  'success' => "ระบบได้ทำการส่งรหัสผ่านใหม่".'<br>'."ไปทางอีเมลที่ลงทะเบียนไว้",
              ];
            }else {
              $text = [
                  'false' => "ไม่พบข้อมูลในระบบ".'<br>'."กรุณาทำรายการใหม่อีกครั้ง",
              ];
            }

        }

        if ($check_email == "true") {
          return $text;
        }else {
          return $text;
        }

    }

    public function check_password($pass){
        // $input =  $request['data'];
        $check_password_member = Member::where('member_id', Auth::guard('members')->user()->id)->where('member_status', '1')->first();
        // dd($check_password_member['member_password']);
        if(Hash::check($pass, $check_password_member['member_password'])) {
          $pass = "true";
        }else {
          $pass = "false";
        }

        return $pass;
    }

    public function update_password_member(Request $request){
        $input =  $request['data'];
        $password_member = Member::where('member_id', Auth::guard('members')->user()->id)->where('member_status', '1')->first();
        // dd($input,$password_member);
        if ($password_member) {
          $member_update = Member::where('member_id', $password_member['member_id'])->where('member_status', '1')->first();
          $member_update->member_password = Hash::make($input['input_confirm_password']);
          $member_update->save();

          if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
              App::setLocale('en');
                $text = [
                    'success' => "Password changed successfully",
                ];
          }
          else{
              App::setLocale('th');
                $text = [
                    'success' => "เปลี่ยนรหัสผ่านสำเร็จ",
                ];
          }

        }else {
          if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
              App::setLocale('en');

                $text = [
                    'false' => "Password change unsuccessful",
                ];

          }
          else{
              App::setLocale('th');

                $text = [
                    'false' => "เปลี่ยนรหัสผ่านไม่สำเร็จ",
                ];

          }
        }

        return $text;


    }

    public function del_coures($coures_id){
      // dd($coures_id);
      $check_studen = Course::where('_id', $coures_id)->first();
      // dd($check_studen->course_category);
      if ($check_studen->course_category == 'Private') {
        $classroom = Classroom::where('course_id', $coures_id)->first();
        // dd($classroom);
        if ($classroom != null) {
          // dd($classroom->classroom_status);
          if ($classroom->classroom_status == 2 || $classroom->classroom_status == 3) {
            $classroom_update = Classroom::where('course_id', $coures_id)->update(['classroom_status' => "2"]);
            $Course_update = Course::where('_id', $coures_id)->update(['course_status' => "delete"]);
            return "success";
          }
          else {
            return "false";
          }

        }
        else {
          $classroom_update = Classroom::where('course_id', $coures_id)->update(['classroom_status' => "2"]);
          $Course_update = Course::where('_id', $coures_id)->update(['course_status' => "delete"]);
          return "success";
        }
      }
      else {
        $classroom = Classroom::where('course_id', $coures_id)->first();
        // dd($classroom);
        if ($classroom == null) {
          $classroom_update = Classroom::where('course_id', $coures_id)->update(['classroom_status' => "2"]);
          $Course_update = Course::where('_id', $coures_id)->update(['course_status' => "delete"]);
          return "success";
        }else {
            if ($check_studen->course_category == 'School') {
              // dd(date("Y-m-d H:i:s")." < ".$check_studen->course_date[0]['date']." ".$check_studen->course_date[0]['time_start']);

              if (date("Y-m-d H:i:s") < $check_studen->course_date[0]['date']." ".$check_studen->course_date[0]['time_start']) {

                foreach ($check_studen->course_student as $key => $value) {
                  // dd($value['student_email']);
                  $member = Member::where('member_email',  'LIKE','%'.$value['student_email'].'%')->where('member_status', '=', '1')->first();

                  if ($member->member_email != "") {

                    $noti_type_student = "cancel_course_school";
                    $subject = 'Suksa Online : ยกเลิกคอร์เรียนแล้ว';
                    $from_name = 'Suksa Online';
                    $from_email = 'noreply@suksa.online';
                    $teacher_fullname = $check_studen->member_fname." ".$check_studen->member_lname;
                    $course_name = $check_studen->course_name;
                    $to_name =  $member->member_fname." ".$member->member_lname; // ชื่อเต็มของนักเรียน
                    $to_email = $member->member_email; // อีเม ของนักเรียน
                    $student_id = $member->_id; // ID ของนักเรียน
                    $description = '';
                    $data = array(
                        'student_fullname'=> $to_name,
                        'teacher_fullname' => $teacher_fullname,
                        'course_name' => $course_name,
                        'course_date' => changeDate($check_studen->course_date[0]['date'], 'full_date', 'th'),
                        'course_time_start' => substr($check_studen->course_date[0]['time_start'],0,5),
                        'course_time_end' => substr($check_studen->course_date[0]['time_end'],0,5),
                        'description' => $description
                    );

                    Mail::send('frontend.send_email_cancel_course_school_student', $data, function($message) use ($subject, $from_name, $from_email, $to_name, $to_email) {
                        $message->from($from_email, $from_name);
                        $message->to($to_email, $to_name);
                        $message->subject($subject);
                    });


                    $student_noti = new MemberNotification();
                    $student_noti->course_id = $coures_id;
                    $student_noti->course_name = $check_studen->course_name;
                    $student_noti->course_datetime = $check_studen->course_date[0]['date'];
                    $student_noti->course_start_date = $check_studen->course_date[0]['time_start'];
                    $student_noti->course_end_date = $check_studen->course_date[0]['time_end'];
                    $student_noti->member_id = $member->_id;
                    $student_noti->member_email = $member->member_email;
                    $student_noti->teacher_id = Auth::guard('members')->user()->id;
                    $student_noti->teacher_fullname = $check_studen->member_fname." ".$check_studen->member_lname;
                    $student_noti->noti_type = 'cancel_course_school';
                    $student_noti->noti_status = '0';
                    $student_noti->save();

                    sendMemberNoti($member->_id);
                  }

                }
                $Course_update = Course::where('_id', $coures_id)->update(['course_status' => "delete"]);
                $classroom_update = Classroom::where('course_id', $coures_id)->update(['classroom_status' => "2"]);
                return "success";

              }
              else {

                if (date("Y-m-d H:i:s") > $check_studen->last_course_date_time) {

                  foreach ($check_studen->course_student as $key => $value) {
                    // dd($value['student_email']);
                    $member = Member::where('member_email',  'LIKE','%'.$value['student_email'].'%')->where('member_status', '=', '1')->first();

                    if ($member->member_email != "") {

                      $noti_type_student = "cancel_course_school";
                      $subject = 'Suksa Online : ยกเลิกคอร์เรียนแล้ว';
                      $from_name = 'Suksa Online';
                      $from_email = 'noreply@suksa.online';
                      $teacher_fullname = $check_studen->member_fname." ".$check_studen->member_lname;
                      $course_name = $check_studen->course_name;
                      $to_name =  $member->member_fname." ".$member->member_lname; // ชื่อเต็มของนักเรียน
                      $to_email = $member->member_email; // อีเม ของนักเรียน
                      $student_id = $member->_id; // ID ของนักเรียน
                      $description = '';
                      $data = array(
                          'student_fullname'=> $to_name,
                          'teacher_fullname' => $teacher_fullname,
                          'course_name' => $course_name,
                          'course_date' => changeDate($check_studen->course_date[0]['date'], 'full_date', 'th'),
                          'course_time_start' => substr($check_studen->course_date[0]['time_start'],0,5),
                          'course_time_end' => substr($check_studen->course_date[0]['time_end'],0,5),
                          'description' => $description
                      );

                      Mail::send('frontend.send_email_cancel_course_school_student', $data, function($message) use ($subject, $from_name, $from_email, $to_name, $to_email) {
                          $message->from($from_email, $from_name);
                          $message->to($to_email, $to_name);
                          $message->subject($subject);
                      });


                      $student_noti = new MemberNotification();
                      $student_noti->course_id = $coures_id;
                      $student_noti->course_name = $check_studen->course_name;
                      $student_noti->course_datetime = $check_studen->course_date[0]['date'];
                      $student_noti->course_start_date = $check_studen->course_date[0]['time_start'];
                      $student_noti->course_end_date = $check_studen->course_date[0]['time_end'];
                      $student_noti->member_id = $member->_id;
                      $student_noti->member_email = $member->member_email;
                      $student_noti->teacher_id = Auth::guard('members')->user()->id;
                      $student_noti->teacher_fullname = $check_studen->member_fname." ".$check_studen->member_lname;
                      $student_noti->noti_type = 'cancel_course_school';
                      $student_noti->noti_status = '0';
                      $student_noti->save();

                      sendMemberNoti($member->_id);
                    }

                  }
                  $Course_update = Course::where('_id', $coures_id)->update(['course_status' => "delete"]);
                  $classroom_update = Classroom::where('course_id', $coures_id)->update(['classroom_status' => "2"]);
                  return "success";

                }
                else {
                    return "The course cannot be deleted";
                }

              }

            }
            else {
              if ($classroom->classroom_status == 2) {
                $classroom_update = Classroom::where('course_id', $coures_id)->update(['classroom_status' => "2"]);
                $Course_update = Course::where('_id', $coures_id)->update(['course_status' => "delete"]);
                return "success";
              }else {
                return "false";
              }
            }

        }
      }


      // $Course_update->course_status = "delete";
      // $Course_update->save();

      // $ddd = Course::where('_id', $coures_id)->get();
      // dd($ddd,$coures_id);

    }

    public function get_member($value=null)
    {
      $members = Member::where('member_status', '1')->get();


      // dd($members);

      return view('frontend.get_member', compact('members'));
    }
}
