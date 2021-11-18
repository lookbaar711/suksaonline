<?php

namespace App\Http\Controllers\frontend;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Aptitude;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Classroom;
use App\Models\MemberNotification;
use App\Models\School;
use App\Models\CourseQuestions;
use App\Models\BBBSettings;
use App\Models\CourseTest;
use App\Models\CoinsLog;
use App\Models\RequestSubjects;
use Auth;
use Mail;
use App\Events\SendNotiFrontend;
use Session;
use Illuminate\Support\Str;
use App;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Excel;

class CourseController extends Controller
{
    public function index(){
        $view['success'] = [];

        update_last_action();

        return view('frontend.courses.course-all');
    }

    public function all(){
        $view['success'] = [];

        update_last_action();

        // return view('frontend.courses.course-all' ,['courses_free' => $courses_free,'courses_not_free' => $courses_not_free,'class_room' => $class_room]);
        return view('frontend.courses.course-all');
    }

    public function search_coursetFree(Request $request) {
        if ($request->data['course'] != '' || $request->data['study_group'] != '' || $request->data['subjects'] != '') {

          if ($request->data['course'] != '' && $request->data['study_group'] != '' && $request->data['subjects'] != '') { ////  ค้นหาชื่อคอร์ ระดับชั้น รายวิชา
            $courses_free = Course::where('course_type', '0')
                ->where('last_course_date_time', '>=', date('Y-m-d H:i:s'))
                ->where('course_category', "Public")
                ->where('course_status',"!=", "delete")
                ->where('course_name', 'like', '%'.$request->data['course'].'%')
                ->where('course_group',$request->data['study_group'])
                ->where('course_subject', $request->data['subjects'])
                ->where('course_status', '!=', 'cancel')
                ->orderBy('created_at','desc')
                // ->paginate(8);
                ->get();
                // print_r("ค้นหาชื่อคอร์ ระดับชั้น รายวิชา");
          }

          if ($request->data['study_group'] != '' && $request->data['subjects'] != '') { ////  ค้นหา ระดับชั้น รายวิชา
            $courses_free = Course::where('course_type', '0')
                ->where('last_course_date_time', '>=', date('Y-m-d H:i:s'))
                ->where('course_category', "Public")
                ->where('course_status',"!=", "delete")
                ->where('course_group', $request->data['study_group'])
                ->where('course_subject', $request->data['subjects'])
                ->where('course_status', '!=', 'cancel')
                ->orderBy('created_at','desc')
                // ->paginate(8);
                ->get();
                // print_r("ระดับชั้น รายวิชา");
          }else {

            if ($request->data['course'] != '' && $request->data['study_group'] != '') { ////  ค้นหาชื่อคอร์ ระดับชั้น
              $courses_free = Course::where('course_type', '0')
                  ->where('last_course_date_time', '>=', date('Y-m-d H:i:s'))
                  ->where('course_category', "Public")
                  ->where('course_status',"!=", "delete")
                  ->where('course_name','like', '%'.$request->data['course'].'%')
                  ->where('course_group', $request->data['study_group'])
                  ->where('course_status', '!=', 'cancel')
                  ->orderBy('created_at','desc')
                  // ->paginate(8);
                  ->get();
                  // print_r("ค้นหาชื่อคอร์");
            }else {
              if ($request->data['subjects'] != '') { ////  ค้นหา รายวิชา
                $courses_free = Course::where('course_type', '0')
                    ->where('last_course_date_time', '>=', date('Y-m-d H:i:s'))
                    ->where('course_category', "Public")
                    ->where('course_status',"!=", "delete")
                    ->where('course_subject', $request->data['subjects'])
                    ->where('course_status', '!=', 'cancel')
                    ->orderBy('created_at','desc')
                    // ->paginate(8);
                    ->get();
                    // print_r("รายวิชา");
              }
              if ($request->data['study_group'] != '') { ////  ค้นหา ระดับชั้น
                $courses_free = Course::where('course_type', '0')
                    ->where('last_course_date_time', '>=', date('Y-m-d H:i:s'))
                    ->where('course_category', "Public")
                    ->where('course_status',"!=", "delete")
                    ->where('course_group', $request->data['study_group'])
                    ->where('course_status', '!=', 'cancel')
                    ->orderBy('created_at','desc')
                    // ->paginate(8);
                    ->get();
                    // print_r("ระดับชั้น");
              }
              if ($request->data['course'] != '') { //// ค้นหาชื่อคอร์
                $courses_free = Course::where('course_type', '0')
                    ->where('last_course_date_time', '>=', date('Y-m-d H:i:s'))
                    ->where('course_category', "Public")
                    ->where('course_status',"!=", "delete")
                    ->where('course_name','like', '%'.$request->data['course'].'%')
                    ->where('course_status', '!=', 'cancel')
                    ->orderBy('created_at','desc')
                    // ->paginate(8);
                    ->get();
                    // print_r("ค้นหาชื่อคอร์");
              }
            }

          }


        }else {
          $courses_free = Course::where('course_type', '0')
              ->where('last_course_date_time', '>=', date('Y-m-d H:i:s'))
              ->where('course_category', "Public")
              ->where('course_status',"!=", "delete")
              ->where('course_status', '!=', 'cancel')
              ->orderBy('created_at','desc')
              // ->paginate(8);
              ->get();
              // print_r("ทั้งหมด");
        }

        $data_courses_free = collect();
        foreach ($courses_free as $key => $value) {

          $classroom = Classroom::where('course_id', $value->id)->first();
          if($classroom){
              $courses_free[$key]->student = count($classroom->classroom_student);
              $courses_free[$key]->student_name = $classroom->classroom_student;
          }

          $aptitude = Aptitude::where('_id', $value->course_group)->first();
          $subject = Subject::where('_id', $value->course_subject)->first();

          if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
              $courses_free[$key]->course_group = $aptitude->aptitude_name_en;
              $courses_free[$key]->course_subject = $subject->subject_name_en;
          }
          else{
              $courses_free[$key]->course_group = $aptitude->aptitude_name_th;
              $courses_free[$key]->course_subject = $subject->subject_name_th;
          }

          $data_courses_free->push([
            "_id" => $value->_id,
            "member_id" => $value->member_id,
            "member_fname" => $value->member_fname,
            "member_lname" => $value->member_lname,
            "member_email" => $value->member_email,
            "course_name" => $value->course_name,
            "course_detail" => $value->course_detail,
            "course_group" => $value->course_group,
            "course_subject" => $value->course_subject,
            "course_date" => $value->course_date,
            "course_type" => $value->course_type,
            "course_price" => $value->course_price,
            "course_img" => $value->course_img,
            "course_file" => $value->course_file,
            "course_student_limit" => $value->course_student_limit,
            "course_status" => $value->course_status,
            "course_category" => $value->course_category,
            "course_student" => $value->course_student,
            "course_date_start" => $value->course_date_start,
            "course_time_start" => $value->course_time_start,
            "course_time_end" => $value->course_time_end,
            "updated_at" => $value->updated_at,
            "created_at" => $value->created_at,
            "student" => $value->student,
            "student_name" => $value->student_name
          ]);

        }

        $course = $data_courses_free;
        $data_newe = array();
          //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        //Create a new Laravel collection from the array data
        $collection = Collect($course)->sortBy('time_start')->sortBy('date');
        //Define how many items we want to be visible in each page
        $per_page = 8;

        //Slice the collection to get the items to display in current page
        $currentPageResults = $collection->slice(($currentPage-1) * $per_page, $per_page)->all();

        //Create our paginator and add it to the data array
        $data_newe['coursies'] = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);

        //Set base url for pagination links to follow e.g custom/url?page=6
        $data_newe['coursies']->setPath($request->url());
        // dd($data);

        update_last_action();

        $courses_free = $data_newe['coursies'];

        $data = $arrayName = array('courses_free' => $courses_free  );
        return json_encode($data);
    }

    public function search_course_not_free(Request $request) {

        // $output = '';
        // print_r(count($request->data));
        if ($request->data['course'] != '' || $request->data['study_group'] != '' || $request->data['subjects'] != '') {
          if ($request->data['course'] != '' && $request->data['study_group'] != '' && $request->data['subjects'] != '') {
            $courses_not_free = Course::where('course_type', '1')
                ->where('last_course_date_time', '>=', date('Y-m-d H:i:s'))
                ->where('course_category', "Public")
                ->where('course_status',"!=", "delete")
                ->where('course_name', 'like', '%'.$request->data['course'].'%')
                ->where('course_group',$request->data['study_group'])
                ->where('course_subject', $request->data['subjects'])
                ->where('course_status', '!=', 'cancel')
                ->orderBy('created_at','desc')
                // ->paginate(8);
                ->get();
                // print_r("ค้นหาชื่อคอร์ ระดับชั้น รายวิชา");
          }

          if ($request->data['study_group'] != '' && $request->data['subjects'] != '') { ////  ค้นหา ระดับชั้น รายวิชา
            $courses_not_free = Course::where('course_type', '1')
                ->where('last_course_date_time', '>=', date('Y-m-d H:i:s'))
                ->where('course_category', "Public")
                ->where('course_status',"!=", "delete")
                ->where('course_group', $request->data['study_group'])
                ->where('course_subject', $request->data['subjects'])
                ->where('course_status', '!=', 'cancel')
                ->orderBy('created_at','desc')
                // ->paginate(8);
                ->get();
                // print_r("ระดับชั้น รายวิชา");
          }
          else {
            if ($request->data['course'] != '' && $request->data['study_group'] != '') { ////  ค้นหาชื่อคอร์ ระดับชั้น
              $courses_not_free = Course::where('course_type', '1')
                  ->where('last_course_date_time', '>=', date('Y-m-d H:i:s'))
                  ->where('course_category', "Public")
                  ->where('course_status',"!=", "delete")
                  ->where('course_name','like', '%'.$request->data['course'].'%')
                  ->where('course_group', $request->data['study_group'])
                  ->where('course_status', '!=', 'cancel')
                  ->orderBy('created_at','desc')
                  // ->paginate(8);
                  ->get();
                  // print_r("ค้นหาชื่อคอร์");
            }else {

              if ($request->data['subjects'] != '') { ////  ค้นหา รายวิชา
                $courses_not_free = Course::where('course_type', '1')
                    ->where('last_course_date_time', '>=', date('Y-m-d H:i:s'))
                    ->where('course_category', "Public")
                    ->where('course_status',"!=", "delete")
                    ->where('course_subject', $request->data['subjects'])
                    ->where('course_status', '!=', 'cancel')
                    ->orderBy('created_at','desc')
                    // ->paginate(8);
                    ->get();
                    // print_r("รายวิชา");
              }
              if ($request->data['study_group'] != '') { ////  ค้นหา ระดับชั้น
                $courses_not_free = Course::where('course_type', '1')
                    ->where('last_course_date_time', '>=', date('Y-m-d H:i:s'))
                    ->where('course_category', "Public")
                    ->where('course_status',"!=", "delete")
                    ->where('course_group', $request->data['study_group'])
                    ->where('course_status', '!=', 'cancel')
                    ->orderBy('created_at','desc')
                    // ->paginate(8);
                    ->get();
                    // print_r("ระดับชั้น");
              }
              if ($request->data['course'] != '') { //// ค้นหาชื่อคอร์
                $courses_not_free = Course::where('course_type', '1')
                    ->where('last_course_date_time', '>=', date('Y-m-d H:i:s'))
                    ->where('course_category', "Public")
                    ->where('course_status',"!=", "delete")
                    ->where('course_name','like', '%'.$request->data['course'].'%')
                    ->where('course_status', '!=', 'cancel')
                    ->orderBy('created_at','desc')
                    // ->paginate(8);
                    ->get();
                    // print_r("ค้นหาชื่อคอร์");
              }
            }
          }
        }
        else {
          $courses_not_free = Course::where('course_type', '1')
              ->where('last_course_date_time', '>=', date('Y-m-d H:i:s'))
              ->where('course_category', "Public")
              ->where('course_status',"!=", "delete")
              ->where('course_status', '!=', 'cancel')
              ->orderBy('created_at','desc')
              // ->paginate(8);
              ->get();
              // print_r("44444444444444");
        }

        // dd($courses_not_free);
        $data_courses_not_free  = collect();
        foreach ($courses_not_free as $key => $value) {
          $classroom = Classroom::where('course_id', $value->id)->first();
          if($classroom){
              $courses_not_free[$key]->student = count($classroom->classroom_student);
              $courses_not_free[$key]->student_name = $classroom->classroom_student;
          }

          $aptitude = Aptitude::where('_id', $value->course_group)->first();
          $subject = Subject::where('_id', $value->course_subject)->first();

          if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
              $courses_not_free[$key]->course_group = $aptitude->aptitude_name_en;
              $courses_not_free[$key]->course_subject = $subject->subject_name_en;
          }
          else{
              $courses_not_free[$key]->course_group = $aptitude->aptitude_name_th;
              $courses_not_free[$key]->course_subject = $subject->subject_name_th;
          }

          $data_courses_not_free->push([
            "_id" => $value->_id,
            "member_id" => $value->member_id,
            "member_fname" => $value->member_fname,
            "member_lname" => $value->member_lname,
            "member_email" => $value->member_email,
            "course_name" => $value->course_name,
            "course_detail" => $value->course_detail,
            "course_group" => $value->course_group,
            "course_subject" => $value->course_subject,
            "course_date" => $value->course_date,
            "course_type" => $value->course_type,
            "course_price" => $value->course_price,
            "course_img" => $value->course_img,
            "course_file" => $value->course_file,
            "course_student_limit" => $value->course_student_limit,
            "course_status" => $value->course_status,
            "course_category" => $value->course_category,
            "course_student" => $value->course_student,
            "course_date_start" => $value->course_date_start,
            "course_time_start" => $value->course_time_start,
            "course_time_end" => $value->course_time_end,
            "updated_at" => $value->updated_at,
            "created_at" => $value->created_at,
            "student" => $value->student,
            "student_name" => $value->student_name
          ]);
        }

        $course1 = $data_courses_not_free;
        $data_new1 = array();
          //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        //Create a new Laravel collection from the array data
        $collection = Collect($course1)->sortBy('time_start')->sortBy('date');
        //Define how many items we want to be visible in each page
        $per_page = 8;

        //Slice the collection to get the items to display in current page
        $currentPageResults = $collection->slice(($currentPage-1) * $per_page, $per_page)->all();

        //Create our paginator and add it to the data array
        $data_new1['courses_not_free'] = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);

        //Set base url for pagination links to follow e.g custom/url?page=6
        $data_new1['courses_not_free']->setPath($request->url());
        // dd($data);

        update_last_action();

        $courses_not_free = $data_new1['courses_not_free'];


        // $class_room = Classroom::paginate(8);
        $data = $arrayName = array('courses_not_free' => $courses_not_free);
        return json_encode($data);
    }

    public function get_study_group(Request $request) {
      $data = Aptitude::orderBy('created_at','asc')->get();
      $datas = [];
      foreach ($data as $key => $value) {
          if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
              App::setLocale('en');
              $datas[] = [
                  'id_aptitude' => $value['_id'],
                  'id' => $value['aptitude_subject'],
                  'name' => $value['aptitude_name_en'],
              ];
          }else{
              App::setLocale('th');
              $datas[] = [
                  'id_aptitude' => $value['_id'],
                  'id' => $value['aptitude_subject'],
                  'name' => $value['aptitude_name_th'],
              ];
          }

      }

      return json_encode(array(
          'results' => $datas,
      ));
    }

    public function get_subjects(Request $request){

      if ($request->id) {
        // print_r("1");
        $data = Aptitude::where('_id',$request->id)->first();
      }else {
        $data = Aptitude::orderBy('created_at','asc')->get();
        $datas = [];
        return json_encode(array(
            'results' => $datas,
        ));
      }

      // dd($data->aptitude_subject);
      $datas = [];
      foreach ($data->aptitude_subject as $key => $subject_id) {
        $subject = Subject::where('_id', $subject_id)->first();
        if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
            App::setLocale('en');
            $datas[] = [
                'id' => $subject->id,
                'subject_id' => $subject->id,
                'subject_name' => $subject->subject_name_en,
            ];
        }else{
            App::setLocale('th');
            $datas[] = [
                'id' => $subject->id,
                'subject_id' => $subject->id,
                'subject_name' => $subject->subject_name_th,
            ];
        }


      }

      return json_encode(array(
          'results' => $datas,
      ));

    }

    public function show($id){
        $courses = Course::where('_id', $id)->first();
        if($courses->course_category=="Private"){
          if(isset(Auth::guard('members')->user()->member_email)){
            if(in_array(Auth::guard('members')->user()->member_email, $courses->course_student) ||
              $courses->member_id==Auth::guard('members')->user()->id){

            }
            else{
              return redirect()->back()->with('alerterror', 'คุณไม่มีสิทธิ์เข้าคอร์สเรียนนี้');
            }
          }
          else{
            return redirect()->back()->with('alerterror', 'คุณไม่มีสิทธิ์เข้าคอร์สเรียนนี้');
          }
        }

        //ถ้า login แล้ว
        if(Auth::guard('members')->user()){
            $status = 'empty';
            $classroom = Classroom::where('course_id', $courses->id)->first();
            //ถ้ามีคนสมัครแล้ว
            if(!empty($classroom)){
                foreach ($classroom->classroom_student as $key => $value) {
                   if(Auth::guard('members')->user()->member_email == $value['student_email']){
                        $status = 'exit';
                   }
                }
                $courses->stutent = count($classroom->classroom_student);
            }
            //ถ้าเป็นอาจารย์ที่สร้างคอร์ส
            if($course_check = Course::where('_id', $id)->where('member_id', Auth::guard('members')->user()->id)->first()){
                $status = 'teacher';
            }
        }
        //ถ้ายังไม่ได้ login
        else{
            $status = 'empty';
        }
        //หาชื่อกลุ่มความถนัด และวิชา
        $aptitude = Aptitude::where('_id', $courses->course_group)->first();
        $courses->aptitude_detail = $aptitude;

        $subject = Subject::where('_id', $courses->course_subject)->first();
        $courses->subject_detail = $subject;

        //เช็คว่าเป็นวันสุดท้ายป่าวของ คอร์ส ป่าว
        $class_close = Classroom::where('course_id', $courses->id)->where('classroom_category','School')->orderby('classroom_date','desc')->orderby('classroom_time_end','desc')->first();
        if ($class_close) {
            $class_close_room = $class_close->classroom_date.' '.$class_close->classroom_time_end;
            // $class_close_room = date('Y-m-d H:i',strtotime($class_close_room.'+2 hour'));
            $class_close_room = date('Y-m-d H:i',strtotime($class_close_room));
        }


        update_last_action();

        //return date('Y-m-d H:i:s')."  ".date('Y-m-d H:i:s', strtotime($courses->course_date." ".$courses->course_time_start));
        $members = Member::where('_id', '=', $courses->member_id)->where('member_status', '1')->first();
        if(!$members){
            return view('backend.404');
        }

        //return $status;

        return view('frontend.courses.course-detail', compact('courses', 'status', 'members','class_close_room'));
    }

    public function create(){
        if(Auth::guard('members')->user()->member_role =='teacher'){
          $members = Member::where('_id', '=', Auth::guard('members')->user()->id)->where('member_status', '1')->first();
          $member_aptitude = $members->member_aptitude;

          $subject_detail = array();

          foreach (array_keys($member_aptitude) as $key){
              if(count($member_aptitude[$key]) > 0){
                  $aptitude = Aptitude::where('_id', '=', $key)->first();
                  if($aptitude){
                      $subject_detail[$key]['aptitude_name_en'] = $aptitude->aptitude_name_en;
                      $subject_detail[$key]['aptitude_name_th'] = $aptitude->aptitude_name_th;
                  }
              }
          }

          $school_detail = array();
          $school = School::where('school_status', '1')->where('school_teacher.teacher_email', $members->member_email)->first();
          // dd($school,$members->member_email,$members);
          if(isset($members->member_school) && isset($school->_id)){
            $school_detail['school_id'] = $members->member_school;
            $school_detail['school_name_th'] = $school->school_name_th;
            $school_detail['school_name_en'] = $school->school_name_en;
            $school_detail['school_student'] = $school->school_student;
          }
          else{
            $school_detail['school_id'] = '';
            $school_detail['school_name_th'] = '';
            $school_detail['school_name_en'] = '';
            $school_detail['school_student'] = '';
          }

          update_last_action();

          return view('frontend.courses.course-create', compact('subject_detail','school_detail'));
        }
        else{
          return redirect('/');
        }
    }

    public function store(Request $request){

        //return $request;

        // dd($request);
        $check_date = explode("/",$request->course_date[1]);
        $format_check_date = $check_date[2].'-'.$check_date[1].'-'.$check_date[0];

        $first_date = date('Y-m-d', strtotime($format_check_date));
        $first_time = date('H:i:s', strtotime($request->time_start[1].":00"));
        $first_datetime = date('Y-m-d H:i:s', strtotime($first_date.' '.$first_time));

        if($request->course_img){
            //อัพรูปปกคอร์สเรียน
            $fielName =  Str::random(7).time().".".$request->course_img->getClientOriginalExtension();
            //$path = $request->course_img->move(public_path("/storage/course"), $fielName);
            $course_path = public_path("/storage/course/");
            $course_file_path = public_path("/storage/coursefile/");
            if (!file_exists($course_path)) {
                mkdir($course_path, 0777, true);
            }
            if (!file_exists($course_file_path)) {
                mkdir($course_file_path, 0777, true);
            }
            $url = public_path("/storage/course/".$fielName);
            $path = compress_images($request->course_img, $url, 80);
        }
        else{
            $fielName=$request->img2;
        }


        if($request->course_file){
            //อัพไฟล์
            $course_file =  Str::random(7).time().".".$request->course_file->getClientOriginalExtension();
            $file = $request->course_file->move(public_path("/storage/coursefile"), $course_file);
        }
        else{
            $course_file=$request->file2;
        }


        $course_date = [];
        //order course date
        foreach ($request->course_date as $i => $value) {
            if($i!="0"){
                $select_date = explode("/",$value);
                $date = $select_date[2].'-'.$select_date[1].'-'.$select_date[0];

                $course_date[] = ([
                    'date' => $date,
                    'time_start' => $request->time_start[$i].":00",
                    'time_end' => $request->time_end[$i].":00",
                ]);
            }
        }

        $last_key = count($course_date) - 1;
        $last_course_date_time = $course_date[$last_key]['date'].' '.$course_date[$last_key]['time_end'];

        sort($course_date);


        //ราคา คอร์สเรียน / ฟรี
        if(!empty($request->course_price)){
            $course_price = str_replace(",","",$request->course_price); //ลบเครื่องหมาย ,
        }
        else{
            $course_price="0";
        }

        //สถานะคอร์ส
        if(isset($request->course_status)){
            $status = $request->course_status;
        }
        else{
            $status = "close";
        }
        // dd($request->select_student_level,$request);
        $course_student = [];
        if(($request->course_category == "Private") || ($request->course_category == "School")){

          if ($request->course_category == "School") {
            $course_student_limit = count($request->course_student)-1;

            // foreach ($request->select_student_level as $key => $value) {
            //     // if($key == $request->select_student_level){
            //         $course_student[] = $value;
            //     // }
            // }
            $course_student = json_decode($request->select_student_level);
          }else {
            $course_student_limit = count($request->course_student)-1;
            foreach ($request->course_student as $key => $value) {
                if($key!="0"){
                    $course_student[] = $value;
                }
            }
          }

        }
        else{
            $course_student_limit = intval($request->course_student_limit);
        }
        // dd($course_student);
        $data = ([
            'member_id' => Auth::guard('members')->user()->id,
            'member_fname' => Auth::guard('members')->user()->member_fname,
            'member_lname' => Auth::guard('members')->user()->member_lname,
            'member_email' => Auth::guard('members')->user()->member_email,
            'course_name' => $request->course_name,
            'course_detail' => $request->course_detail,
            'course_group' => $request->course_group,
            'course_subject' => $request->course_subject,
            'course_date' => $course_date,
            'course_type' => $request->course_type,
            // 'course_price' => number_format(intval(preg_replace("/[,]/", "", $course_price))),
            //'course_price' =>  number_format(intval($course_price)), //มี number_format เก็บเป็น 1,000
            'course_price' =>  intval($course_price), //เก็บเป็นตัวเลข
            'course_img' => $fielName,
            'course_file' => $course_file,
            'course_student_limit' => $course_student_limit,
            'course_status' => $status,
            'course_category' => $request->course_category,
            'course_student' => $course_student,
            // 'course_date_start' => date('Y-m-d', strtotime($request->course_date[1])),
            'course_date_start' => $format_check_date,
            'course_time_start' => $request->time_start[1].":00",
            'course_time_end' => $request->time_end[1].":00",

            'last_course_date_time' => $last_course_date_time,
            'school_id' => $request->member_school,
        ]);
        if($request->id) {
            Course::where('_id',$request->id)->update($data); //แก้ไขการ สร้างคอร์สเรียน
        }
        else{
            Course::create($data); //สร้างคอร์สเรียน
        }

        if ($request->course_category == "School") {
          $data_question = [];

          for ($i=0; $i < 10; $i++) {
            $question_no = $i+1;
            $question_text = "question_".($i+1);
            $question = $request->$question_text;

            $answer_text__text1 = "answer_text_".($i == 0 ? "":$i). 1;
            $answer_text1 = $request->$answer_text__text1;

            $answer_text__text2 = "answer_text_".($i == 0 ? "":$i). 2;
            $answer_text2 = $request->$answer_text__text2;

            $answer_text__text3 = "answer_text_".($i == 0 ? "":$i). 3;
            $answer_text3 = $request->$answer_text__text3;

            $answer_text__text4 = "answer_text_".($i == 0 ? "":$i). 4;
            $answer_text4 = $request->$answer_text__text4;

            $answer_text = "answer_".($i+1);
            $answer = $request->$answer_text;

            $question = [
              "question_no" => "$question_no",
              "question" => $question,
              "choice_1" => $answer_text1,
              "choice_2" => $answer_text2,
              "choice_3" => $answer_text3,
              "choice_4" => $answer_text4,
              "answer" => $answer
            ];

              array_push($data_question,$question);

          }

          $course_id2 = Course::where('member_id', '=', Auth::guard('members')->user()->id)
              ->orderby('created_at','desc')
              ->first();

            $data_question2 = [
              'course_id' => $course_id2->id,
              'questions' => $data_question,
            ];

          CourseQuestions::create($data_question2); //สร้างแบบทดสอบ
          $courses = $course_id2;
          $open_classroom = $this->create_classroom_school($course_id2->id,$courses);

          // dd($open_classroom);

        }

        if($status=="open"){
            //ถ้าเป็นคอร์ส Private ให้ส่ง noti หานักเรียน
            // if(($request->course_category=="Private") || ($request->course_category=="School")){
            if(($request->course_category=="Private")){
                //ถ้าสถานะคอร์สเป็น open (เปิดรับสมัครแล้วเท่านั้น) ถึงจะส่ง noti นักเรียน ไม่ต้องส่งหาอาจารย์แล้ว
                $course_id = Course::where('member_id', '=', Auth::guard('members')->user()->id)
                    ->orderby('created_at','desc')
                    ->first();
                $count_datetime = count($course_date)-1;
                $teacher_fullname = Auth::guard('members')->user()->member_fname." ".Auth::guard('members')->user()->member_lname;

                // //insert noti to teacher
                // $teacher_noti = new MemberNotification();
                // $teacher_noti->course_id = $course_id->id;
                // $teacher_noti->course_name = $request->course_name;
                // $teacher_noti->course_datetime = $course_date;
                // $teacher_noti->course_start_date = $course_date[0]['date'];
                // $teacher_noti->course_end_date = $course_date[$count_datetime]['date'];
                // $teacher_noti->member_id = Auth::guard('members')->user()->id;
                // $teacher_noti->member_email = Auth::guard('members')->user()->member_email;
                // $teacher_noti->teacher_id = Auth::guard('members')->user()->id;
                // $teacher_noti->teacher_fullname = $teacher_fullname;
                // $teacher_noti->noti_course_type = $request->course_category;
                // $teacher_noti->noti_type = 'invite_course_teacher';
                // $teacher_noti->noti_status = '0';
                // $teacher_noti->save();

                // sendMemberNoti(Auth::guard('members')->user()->id);

                //insert noti to student
                foreach ($request->course_student as $key => $value) {
                    if($key!="0"){
                    $member_id = Member::where('member_email', '=', $value)
                                    ->where('member_status', '=', '1')
                                    ->first();
                        if($member_id){
                            $student_noti = new MemberNotification();
                            $student_noti->course_id = $course_id->id;
                            $student_noti->course_name = $request->course_name;
                            $student_noti->course_datetime = $course_date;
                            $student_noti->course_start_date = $course_date[0]['date'];
                            $student_noti->course_end_date = $course_date[$count_datetime]['date'];
                            $student_noti->member_id = $member_id->id;
                            $student_noti->member_email = $value;
                            $student_noti->teacher_id = Auth::guard('members')->user()->id;
                            $student_noti->teacher_fullname = $teacher_fullname;
                            // $student_noti->noti_type = ($request->course_category=="Private")?'invite_course_student' : 'invite_course_student_school';
                            $student_noti->noti_type = ($request->course_category=="Private")?'invite_course_student' : '';
                            // $student_noti->noti_type = 'invite_course_student';
                            $student_noti->noti_status = '0';
                            $student_noti->save();

                            sendMemberNoti($member_id->id);
                        }
                    }
                }
            }

        }
        // dd($open_classroom);
        // Alert แก้ไขคอร์ส กับ สร้างคอร์สสำเร็จ
        if($request->id){
            return redirect()->route('members.profile')->with('alerteditcourse', 'แก้ไขข้อมูลคอร์สเรียนเรียบร้อย');
            // return redirect()->route('members.profile')->with('course', 'แก้ไขข้อมูลคอร์สเรียนเรียบร้อย');
        }else{
            return redirect()->route('members.profile')->with('course', 'success');
        }
    }

    public function create_classroom_school($course_id,$courses)
    {
      $check_limit = Classroom::where('course_id', '=', $course_id)->first();

      $check_course = Classroom::where('classroom_student.student_id', '=', Auth::guard('members')->user()->id)->where('course_id', '=', $course_id)->first();

      if($check_course){
          return redirect()->back()->with('alerterror', 'คุณได้สมัครเรียนไปแล้ว');
      }


      $classroom_datetime = array();

      //เช็คว่ามีคนสมัครเรียนแล้วหรือยัง
      $classroom = Classroom::where('course_id', $course_id)->first();
      $check_opening = '0';
      $data_student_detail = [];
      // dd($courses->course_student);
      // dd($courses->course_student[0]);
      // หาข้อมูลนักเรียนโดยใช้ อีเมนักเรียน จะสมัครให้อัตโนมัติ
      foreach ($courses->course_student as $key_student => $value_student) {
        // dd($value_student);
        $student_email = $value_student['student_email'];
        $data_student = Member::where('member_email', 'LIKE',"%$student_email%")->where('member_status', '1')->first();
        // dd($data_student);
        if ($data_student) {
          $data_student_detail[] = ([
              'student_id' => $data_student->_id,
              'student_fname' => $data_student->member_fname,
              'student_lname' => $data_student->member_lname,
              'student_email' => $data_student->member_email,
              'student_date_regis' => date('Y-m-d'),
              'student_tell' => $data_student->member_tell,
              'student_checkin' => '0',
          ]);
        }

      }
      // dd($courses);
          foreach ($courses->course_date as $key => $value) {

              $teacher_detail = ([
                  'teacher_id' => $courses->member_id,
                  'teacher_fname' => $courses->member_fname,
                  'teacher_lname' => $courses->member_lname,
                  'teacher_email' => $courses->member_email,
                  'teacher_checkin' => '0',
              ]);
              $data = ([
                  'course_id' => $course_id,
                  'classroom_name' => $courses->course_name,
                  'classroom_date' => $value['date'],
                  'classroom_time_start' => $value['time_start'],
                  'classroom_time_end' => $value['time_end'],
                  'classroom_teacher' => $teacher_detail,
                  'classroom_student' => $data_student_detail,
                  'classroom_status' => '0',
                  'classroom_category' => $courses->course_category,
              ]);

              $datetime = array(
                  'classroom_date' => $value['date'],
                  'classroom_time_start' => $value['time_start'],
                  'classroom_time_end' => $value['time_end'],
              );

              array_push($classroom_datetime,$datetime);
              // array_push($data,$classroom_datetime);
              // dd($data);
              Classroom::create($data); // สร้างห้องเรียน
          }



      $count_datetime = count($classroom_datetime)-1;

      $courses_free = Course::where('course_type', '0')->get();
      $courses_notfree = Course::where('course_type', '1')->get();

      $course_id = $course_id;
      $course_name = $courses->course_name;
      $course_date = $courses->course_date[0]['date'];
      $course_time_start = $courses->course_time_start;
      $course_time_end = $courses->course_time_end;

      // $student_fullname = Auth::guard('members')->user()->member_fname.' '.Auth::guard('members')->user()->member_lname;
      // $student_id = Auth::guard('members')->user()->id;
      $teacher_fullname = $courses->member_fname.' '.$courses->member_lname;
      $teacher_id = $courses->member_id;

      //send email to student ตามจำนวนนักเรียนที่กรอก อีเม สมัครมา
      foreach ($data_student_detail as $student_key => $student_value) {

        $noti_type_student = "register_course_student";
        $subject = 'Suksa Online : สมัครเรียนสำเร็จ';
        $from_name = 'Suksa Online';
        $from_email = 'noreply@suksa.online';
        $to_name =  $student_value['student_fname']." ".$student_value['student_lname']; // ชื่อเต็มของนักเรียน
        $to_email = $student_value['student_email']; // อีเม ของนักเรียน
        $student_id = $student_value['student_id']; // ID ของนักเรียน
        $description = '';
        $data = array(
            'student_fullname'=> $to_name,
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
        $student_noti->member_fullname = $to_name;
        $student_noti->teacher_id = $teacher_id;
        $student_noti->teacher_fullname = $teacher_fullname;
        $student_noti->noti_type = $noti_type_student;
        $student_noti->noti_status = '0';
        $student_noti->save();

        //send noti
        sendMemberNoti($student_id);

      }
      // dd($data_student_detail);


      // $current_date = date('Y-m-d');
      // $current_time = date('H:i:s');
      // dd($classroom);
      //มีห้องเรียนกำลังเปิดอยู่

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

    // แก้ไข
    public function edit($id){
        /*
        $course= Course::where('_id',$id)->get();
        // dd($course);
        $members = Member::where('_id', '=', Auth::guard('members')->user()->id)->first();
        $subject_detail = $members->member_aptitude;
        $data=['subject_detail'=>$subject_detail,'course'=>$course[0],];

        update_last_action();

        return view('frontend.courses.course-create',$data);
        */


        $course = Course::where('_id',$id)->first();

        $members = Member::where('_id', '=', Auth::guard('members')->user()->id)->where('member_status', '1')->first();
        //$member_aptitude = $members->member_aptitude;

        $subjects = $members->member_aptitude;
        $subjects_info = $subjects[$course->course_group];

        $subjects_list = array();

        foreach ($subjects_info as $key){
            $subject = Subject::where('_id', '=', $key)->first();

            $subjects_list[$key]['subject_name_en'] = $subject->subject_name_en;
            $subjects_list[$key]['subject_name_th'] = $subject->subject_name_th;
        }


        $subject_detail = array();

        foreach (array_keys($subjects) as $key){
            if(count($subjects[$key]) > 0){
                $aptitude = Aptitude::where('_id', '=', $key)->first();

                $subject_detail[$key]['aptitude_name_en'] = $aptitude->aptitude_name_en;
                $subject_detail[$key]['aptitude_name_th'] = $aptitude->aptitude_name_th;
            }
        }

        $school_detail = array();

        if(isset($members->member_school)){
            $school = School::where('school_status', '1')->where('school_teacher.teacher_email', $members->member_email)->first();

            $school_detail['school_id'] = $members->member_school;
            $school_detail['school_name_th'] = $school->school_name_th;
            $school_detail['school_name_en'] = $school->school_name_en;
            $school_detail['school_student'] = $school->school_student;
        }
        else{
            $school_detail['school_id'] = '';
            $school_detail['school_name_th'] = '';
            $school_detail['school_name_en'] = '';
            $school_detail['school_student'] = '';
        }

        update_last_action();

        return view('frontend.courses.course-create', compact('subject_detail','course','subjects_list','school_detail'));

    }
    // แก้ไข
    public function subject($id){
        // $members = Member::where('_id', '=', Auth::guard('members')->user()->id)->first();

        // return $members->member_aptitude[$id];

        $members = Member::where('_id', '=', Auth::guard('members')->user()->id)->where('member_status', '1')->first();
        $member_aptitude = $members->member_aptitude[$id];

        $datas = array();
        $i = 0;
        foreach ($member_aptitude as $key => $value) {

            $subject = Subject::where('_id',$value)->first();

            if(isset($subject)){
              if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
                  $datas[$i]['subject_id'] = $value;
                  $datas[$i]['subject_name'] = $subject->subject_name_en;
              }
              else{
                  $datas[$i]['subject_id'] = $value;
                  $datas[$i]['subject_name'] = $subject->subject_name_th;
              }
              $i++;
            }
        }

        return $datas;
    }

    public function opencourse(Course $course){
        if($course->course_category=="Private"){
            foreach ($course->course_student as $key => $value) {
                $member_id = Member::where('member_email', '=', $value)
                            ->where('member_status', '=', '1')
                            ->first();
                $count_datetime = count($course->course_date)-1;
                $teacher_fullname = Auth::guard('members')->user()->member_fname." ".Auth::guard('members')->user()->member_lname;
                if($member_id){
                    $student_noti = new MemberNotification();
                    $student_noti->course_id = $course->id;
                    $student_noti->course_name = $course->course_name;
                    $student_noti->course_datetime = $course->course_date;
                    $student_noti->course_start_date = $course->course_date[0]['date'];
                    $student_noti->course_end_date = $course->course_date[$count_datetime]['date'];
                    $student_noti->member_id = $member_id->id;
                    $student_noti->member_email = $value;
                    $student_noti->teacher_id = Auth::guard('members')->user()->id;
                    $student_noti->teacher_fullname = $teacher_fullname;
                    $student_noti->noti_type = 'invite_course_student';
                    $student_noti->noti_status = '0';
                    $student_noti->save();

                    sendMemberNoti($member_id->id);
                }
            }
        }
        $course->course_status = 'open';
        $course->save();

        return redirect('members/profile')->with('alertsuccess', 'เปิดคอร์สเรียนแล้ว');
    }

    public function status_create($status){
        if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
            App::setLocale('en');
        }
        else{
            App::setLocale('th');
        }
        if($status=="course"){
            Session::put('variableName', trans('frontend/layouts/title.course_online'));
        }else{
            Session::put('variableName', trans('frontend/members/title.teaching_history'));
        }

        return redirect()->route('courses.create');
    }

    public function uploadImage(Request $req){

        if ($req->hasFile('picture')) {

            $image_path = Storage::disk('public')->path('courses/test');
            if (!file_exists($image_path)) {
                mkdir($image_path, 0777, true);
            }
            $fileUpload = $req->picture;
            $fielName =  Str::random(7).time().".".$fileUpload->getClientOriginalExtension();
            $url = public_path("/storage/courses/test/".$fielName);
            $file = compress_images($req->picture, $url, 80);
            return response()->json(['status' => $fielName,'path' =>$file ], 201);
        }
    }

    public function Up_quiz_file(Request $request){

      if ($request->file('file')->extension() == "xlsx" || $request->file('file')->extension() == "xls") {
        $path = Storage::putFile('public/courses', $request->file('file'));
        $path = substr(Storage::url($path), 1);
        $excel = Excel::load($path, function($reader) {
          $reader->skipRows(3);
          $reader->noHeading();
        })->toArray();
        Storage::delete($path);
        $data = [];
        $data_question = [];
        $data_question2 = [];
        $data_question3 = [];
        $i = 1;
        foreach ($excel as $key => $value) {

            $data_question = array(
              'question_true' => $value[5],
              'question' => $value[0],
            );

            $data_question2 = array(
              'answer_a' => $value[1],
              'answer_b' => $value[2],
              'answer_c' => $value[3],
              'answer_d' => $value[4],
            );

             array_push($data_question, $data_question2);
             array_push($data, $data_question);
         }
        return $data;
      }else {
        // code...
      }

    }

}
