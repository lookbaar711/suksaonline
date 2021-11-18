<?php

namespace App\Http\Controllers\frontend;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentStudent;
use App\Models\AssignmentQuestions;
use App\Models\AssigmentStudentMake;
use App\Models\Member;
use App\Models\Aptitude;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\MemberNotification;
use App\Models\QueueNoti;
use App\Models\School;
use App\Models\CourseQuestions;
use App\Models\BBBSettings;
use App\Models\CourseTest;
use App\Models\CoinsLog;
use App\Models\RequestSubjects;
use Auth;
use App;
use Validator;
use DataTables;
use Excel;

class BuildHomeworkController extends Controller
{



   public function index()
   {
    $assignments = Assignment::where('teacher_id',Auth::guard('members')->user()->id)->with('getSubject','getQuestions')->get();
        $getQueryString=\Request::getRequestUri();
        $page = parse_url($getQueryString);
        if (empty($page['query'])) {
          $page = null;
        }else {
          $page = str_replace('page=','',$page['query']);
        }

        $assignments = $this->paginate($assignments,$page);

        $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
        $i = ($page * 10)- 10;

        $members = Member::where('member_status', '1')
            ->where('member_id', Auth::guard('members')->user()->id)
            ->whereNotNull('member_Bday')
            ->orderby('created_at','desc')
            ->get();

        $school = School::where('school_status', '1')->where('school_teacher.teacher_email', $members[0]->member_email)->get();

        // dd($school);

        if(empty($school[0])){
            return redirect('/')->with('not_school', 'false');
        }
        else{
            return View('frontend.homework.teacher.create_list',compact('assignments','i'));
        }


   }

   public function homework_tables()
   {
        $assignments = Assignment::where('teacher_id',Auth::guard('members')->user()->id)->with('getSubject')->get();
    // dd( Datatables::of($assignments->all())->make(true) );
      return Datatables::of($assignments->all())->make(true);
   }

   public function teachermanage($id)
   {
       $lists = [];
        $type_1 = AssignmentQuestions::where('assignment_id',$id)->where('questions_type','1')->with(['getStudentMake','getAssignment'])->first();
        if(!empty($type_1))
        {
            $lists[] = $type_1;
        }else{
            $type_1 =new \stdClass();
            $type_1->questions_type = 1;
            $type_1->assignment_id = $id;
            $lists[] = $type_1;
        }
        $type_2 = AssignmentQuestions::where('assignment_id',$id)->where('questions_type','2')->with(['getStudentMake','getAssignment'])->first();
        if(!empty($type_2))
        {
            $lists[] = $type_2;
        }else{
            $type_2 =new \stdClass();
            $type_2->questions_type = 2;
            $type_2->assignment_id = $id;
            $lists[] = $type_2;
        }
        $type_3 = AssignmentQuestions::where('assignment_id',$id)->where('questions_type','3')->with(['getStudentMake','getAssignment'])->first();
        if(!empty($type_3))
        {
            $lists[] = $type_3;
        }else{
            $type_3 =new \stdClass();
            $type_3->questions_type = 3;
            $type_3->assignment_id = $id;
            $lists[] = $type_3;
        }
        $type_4 = AssignmentQuestions::where('assignment_id',$id)->where('questions_type','4')->with(['getStudentMake','getAssignment'])->first();
        if(!empty($type_4))
        {
            $lists[] = $type_4;
        }else{
            $type_4 =new \stdClass();
            $type_4->questions_type = 4;
            $type_4->assignment_id = $id;
            $lists[] = $type_4;
        }
        $assignments = Assignment::where('_id',$id)->first();
        // dd($lists);
        $i = 1;
        return View('frontend.homework.list_homework',compact('lists','i','assignments'));
   }

   public function teacherdel($id)
   {
       $assignment = Assignment::where('_id',$id)->first();
        if($assignment->delete())
        {
            $assignmentStudent = AssignmentStudent::where('assignment_id',$id)->delete();
            MemberNotification::where('assignment_id',$id)->delete();
            return redirect()->back()->with('delsuccess','success');
        }

   }

   public function Up_multiplechoice(Request $request)
   {

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
            // print_r('<pre>');
            // print_r($value[0]);
            // print_r('<pre>');
            if ($key <= 29) {
                if($value[0] != ''){
                    $data_question = array(
                        'question_true' => $value[5],
                        'question' => $value[0],
                        'score' => $value[6],
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
            }

         }
        return $data;
      }else {
        return response()->json(['status' => false, 'message' => "รูปแบบเอกสาราไม่ถูกต้อง"]);
      }

    }



   public function from(Request $request)
   {
    $members = Member::where('_id', '=', Auth::guard('members')->user()->id)->where('member_status', '1')->first();
    $member_aptitude = $members->member_aptitude;

    $subject_detail = array();

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

    foreach (array_keys($member_aptitude) as $key){
        if(count($member_aptitude[$key]) > 0){
            $aptitude = Aptitude::where('_id', '=', $key)->first();
            if($aptitude){
                $subject_detail[$key]['aptitude_name_en'] = $aptitude->aptitude_name_en;
                $subject_detail[$key]['aptitude_name_th'] = $aptitude->aptitude_name_th;
            }
        }
    }
    return View('frontend.homework.teacher.from_create',compact('subject_detail','school_detail','members'));
   }

   public function assignment(Request $request)
   {
       $inputs = $request->all();
        $status = (isset($inputs['status']) ? $inputs['status'] : '');
        $subject =  (!empty($inputs['subject']) ? $inputs['subject'] : null);
        $title =  (!empty($inputs['title']) ? $inputs['title'] : null);
        $query = new AssignmentStudent;
        $query = $query->where('student_id',Auth::guard('members')->user()->_id);
            if($status != '' && $status != null)
            {
                $query = $query->where('send_assignment_status',$status);
            }
            if($title != '' && $title != null)
            {
                $assignments = Assignment::where('assignment_name','like','%'.$title.'%')->get();
                $IdQuery = [];
                foreach($assignments as $key => $assignment)
                {
                    $IdQuery[$key] = $assignment['_id'];
                }
                $query = $query->whereIn('assignment_id',$IdQuery);
            }
            if($subject != '' || $subject != null)
            {
                $assignments = Assignment::where('subject_id',$subject)->get();
                $IdQuery = [];
                foreach($assignments as $key => $assignment)
                {
                    $IdQuery[$key] = $assignment['_id'];
                }
                $query = $query->whereIn('assignment_id',$IdQuery);
            }
        $getQueryString=\Request::getRequestUri();
        $page = parse_url($getQueryString);
        if (empty($page['query'])) {
            $page = null;
        }else {
            $page = str_replace('page=','',$page['query']);
        }

        $datas = $query->orderBy('send_assignment_status','asc')
                ->orderBy('send_assignment_date','asc')
                ->with(['getAssignment'])
                ->get();

                $page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
        $i = ($page * 10)- 10;
        $datas = $this->paginate($datas,$page);
       $subjects = Subject::where('is_master','1')->get();
       return view('frontend.homework.assignment',compact('datas','subjects','i','title','status','subject'));

   }

   public function assignmentlist($id)
   {

        $questions = AssignmentQuestions::where('assignment_id',$id)->with(['getStudentMake','getStudentMakemember'])->orderBy('questions_type','asc')->get();
        $assignment = Assignment::where('_id',$id)->first();
        return view('frontend.homework.litsassignment',compact('questions','assignment'));
   }

   public function savechoice(Request $request)
   {
        $datas = $request->all();

       $answer_questions = AssignmentQuestions::where('_id',$request->assignment_questions_id)->first();


        foreach($datas['question_on'] as $key => $answer_select)
        {
            $questions_select[] = ([
                'question_on' => strval($key),
                'select_answer' => $answer_select,
            ]);
        }

        $collection = collect($answer_questions->questions);
        foreach($collection as $key => $items)
        {

            if($items['question_on'] == $questions_select[$key]['question_on'])
            {
                if($items['answer'] == $questions_select[$key]['select_answer'])
                {
                    $data[] = $items;
                }else {
                  $items["question_score"] = "0";
                  $data[] = $items;
                }
            }

        }
        // dd($data);
        $score = collect($data);

        //check make
        $check = AssigmentStudentMake::where('assignment_id',$request->assignment_id)
                                    ->where('assignment_questions_id',$request->assignment_questions_id)
                                    ->where('assigment_student_id',Auth::guard('members')->user()->_id)
                                    ->first();
        $arrayData['assignment_id'] = $request->assignment_id;
        $arrayData['assignment_questions_id'] = $request->assignment_questions_id;
        $arrayData['assigment_student_id'] = Auth::guard('members')->user()->_id;
        $arrayData['questions_type'] = $answer_questions->questions_type;
        $arrayData['questions_make'] = $questions_select;
        $arrayData['score'] = $score->sum('question_score');

        if(!empty($check))
        {
            $arrayData['updated_at'] = date('Y-m-d H:i:s');
            $arrayData['updated_by'] = Auth::guard('members')->user()->_id;
            AssigmentStudentMake::where('assignment_id',$request->assignment_id)
            ->where('assignment_questions_id',$request->assignment_questions_id)
            ->where('assigment_student_id',Auth::guard('members')->user()->_id)
            ->update($arrayData);
        }else
        {
            $arrayData['created_at'] = date('Y-m-d H:i:s');
            $arrayData['created_by'] = Auth::guard('members')->user()->_id;
            AssigmentStudentMake::create($arrayData);
        }
        $checkstudenmake = AssigmentStudentMake::where('assignment_id',$request->assignment_id)->where('assigment_student_id',Auth::guard('members')->user()->_id)->count();
        $checkAssigment = AssignmentQuestions::where('assignment_id',$request->assignment_id)->count();
        if($checkAssigment === $checkstudenmake)
        {
            $checkstatue = AssignmentStudent::where('assignment_id',$request->assignment_id)->where('student_id',Auth::guard('members')->user()->_id)->first();

            if($checkstatue == '2'){
                $updatestudent['send_assignment_status'] = '2';
            }else{
                $updatestudent['send_assignment_status'] = '2';
            }

            $updatestudent['send_assignment_date'] = date('Y-m-d');
            AssignmentStudent::where('assignment_id',$request->assignment_id)->where('student_id',Auth::guard('members')->user()->_id)->update($updatestudent);
        }else{
            $updatestudent['send_assignment_status'] = '0';
            $updatestudent['send_assignment_date'] = date('Y-m-d');
            AssignmentStudent::where('assignment_id',$request->assignment_id)->where('student_id',Auth::guard('members')->user()->_id)->update($updatestudent);
        }

        return redirect('assignment/list/'.$request->assignment_id)->with('makehomework','success');
   }

   public function savematching(Request $request)
   {

        $datas = $request->all();
       $answer_questions = AssignmentQuestions::where('_id',$request->assignment_questions_id)->first();

       $answer = [];
        foreach($answer_questions->questions_studen as $key => $itme)
        {
            $answer[$itme['question_on']] = $itme['answer'];
        }

        foreach($datas['select_answer'] as $key => $answer_select)
        {
            $select_answer[$datas['question_on'][$key]] = $datas['select_answer'][$key];

            $questions_select[] = ([
                'question_on' => $datas['question_on'][$key],
                'select_answer' => $answer_select,
            ]);
        }
        $check_answer = array_intersect_assoc($answer,$select_answer);
        $collection = collect($answer_questions->questions);
        $score = $collection->whereIn('question_on', $check_answer);

        $check = AssigmentStudentMake::where('assignment_id',$request->assignment_id)
                                    ->where('assignment_questions_id',$request->assignment_questions_id)
                                    ->where('assigment_student_id',Auth::guard('members')->user()->_id)
                                    ->first();
        $arrayData['assignment_id'] = $request->assignment_id;
        $arrayData['assignment_questions_id'] = $request->assignment_questions_id;
        $arrayData['assigment_student_id'] = Auth::guard('members')->user()->_id;
        $arrayData['questions_type'] = $answer_questions->questions_type;
        $arrayData['questions_make'] = $questions_select;
        $arrayData['score'] = $score->sum('question_score');

        if(!empty($check))
        {
            $arrayData['updated_at'] = date('Y-m-d H:i:s');
            $arrayData['updated_by'] = Auth::guard('members')->user()->_id;
            AssigmentStudentMake::where('assignment_id',$request->assignment_id)
            ->where('assignment_questions_id',$request->assignment_questions_id)
            ->where('assigment_student_id',Auth::guard('members')->user()->_id)
            ->update($arrayData);
        }else
        {

            $arrayData['created_at'] = date('Y-m-d H:i:s');
            $arrayData['created_by'] = Auth::guard('members')->user()->_id;
            AssigmentStudentMake::create($arrayData);
        }
        $checkstudenmake = AssigmentStudentMake::where('assignment_id',$request->assignment_id)->where('assigment_student_id',Auth::guard('members')->user()->_id)->count();
        $checkAssigment = AssignmentQuestions::where('assignment_id',$request->assignment_id)->count();
        if($checkAssigment === $checkstudenmake)
        {
            $checkstatue = AssignmentStudent::where('assignment_id',$request->assignment_id)->where('student_id',Auth::guard('members')->user()->_id)->first();

            if($checkstatue == '2'){
                $updatestudent['send_assignment_status'] = '2';
            }else{
                $updatestudent['send_assignment_status'] = '2';
            }

            $updatestudent['send_assignment_date'] = date('Y-m-d');
            AssignmentStudent::where('assignment_id',$request->assignment_id)->where('student_id',Auth::guard('members')->user()->_id)->update($updatestudent);
        }else{
            $updatestudent['send_assignment_status'] = '0';
            $updatestudent['send_assignment_date'] = date('Y-m-d');
            AssignmentStudent::where('assignment_id',$request->assignment_id)->where('student_id',Auth::guard('members')->user()->_id)->update($updatestudent);
        }

        return redirect('assignment/list/'.$request->assignment_id)->with('makehomework','success');
   }

   public function saveshotanswer(Request $request)
   {
        $datas = $request->all();
       $answer_questions = AssignmentQuestions::where('_id',$request->assignment_questions_id)->first();

       $answer = [];
        foreach($answer_questions->questions as $key => $item)
        {
            $answer[] =([
                'question_on' => $item['question_on'],
                'question' => $item['question'],
                'answer' => str_replace(' ','',$item['answer']),
                'question_score' => $item['question_score'],
            ]);

        }
        foreach($datas['select_answer'] as $key => $answer_select)
        {
            $questions_select[] = ([
                'question_on' => $key,
                'select_answer' => str_replace(' ','',$answer_select),
            ]);
        }
        $collection_answer = collect($answer);
        foreach($collection_answer as $key => $items)
        {

            if($items['question_on'] == $questions_select[$key]['question_on'])
            {
                $array_answer = explode(',',$items['answer']);
                $array_select = explode(',',$questions_select[$key]['select_answer']);
                $check = array_intersect($array_select,$array_answer);
                if(!empty($check))
                {
                    $questions_test[$key] = $items;
                }
            }
        }

        if(!empty($questions_test))
        {
            $sum = collect($questions_test)->sum('question_score');
        }else
        {
            $sum = 0;
        }

        $check = AssigmentStudentMake::where('assignment_id',$request->assignment_id)
                                    ->where('assignment_questions_id',$request->assignment_questions_id)
                                    ->where('assigment_student_id',Auth::guard('members')->user()->_id)
                                    ->first();
        $arrayData['assignment_id'] = $request->assignment_id;
        $arrayData['assignment_questions_id'] = $request->assignment_questions_id;
        $arrayData['assigment_student_id'] = Auth::guard('members')->user()->_id;
        $arrayData['questions_type'] = $answer_questions->questions_type;
        $arrayData['questions_make'] = $questions_select;
        $arrayData['score'] = $sum;
        if(!empty($check))
        {
            $arrayData['updated_at'] = date('Y-m-d H:i:s');
            $arrayData['updated_by'] = Auth::guard('members')->user()->_id;
            AssigmentStudentMake::where('assignment_id',$request->assignment_id)
            ->where('assignment_questions_id',$request->assignment_questions_id)
            ->where('assigment_student_id',Auth::guard('members')->user()->_id)
            ->update($arrayData);
        }else
        {

            $arrayData['created_at'] = date('Y-m-d H:i:s');
            $arrayData['created_by'] = Auth::guard('members')->user()->_id;
            AssigmentStudentMake::create($arrayData);
        }
        $checkstudenmake = AssigmentStudentMake::where('assignment_id',$request->assignment_id)->where('assigment_student_id',Auth::guard('members')->user()->_id)->count();
        $checkAssigment = AssignmentQuestions::where('assignment_id',$request->assignment_id)->count();
        if($checkAssigment === $checkstudenmake)
        {
            $checkstatue = AssignmentStudent::where('assignment_id',$request->assignment_id)->where('student_id',Auth::guard('members')->user()->_id)->first();

            if($checkstatue == '2'){
                $updatestudent['send_assignment_status'] = '2';
            }else{
                $updatestudent['send_assignment_status'] = '2';
            }

            $updatestudent['send_assignment_date'] = date('Y-m-d');
            AssignmentStudent::where('assignment_id',$request->assignment_id)->where('student_id',Auth::guard('members')->user()->_id)->update($updatestudent);
        }else{
            $updatestudent['send_assignment_status'] = '0';
            $updatestudent['send_assignment_date'] = date('Y-m-d');
            AssignmentStudent::where('assignment_id',$request->assignment_id)->where('student_id',Auth::guard('members')->user()->_id)->update($updatestudent);
        }

        return redirect('assignment/list/'.$request->assignment_id)->with('makehomework','success');
   }

   public function savelonganswer(Request $request)
   {
        $datas = $request->all();
        $answer_questions = AssignmentQuestions::where('_id',$request->assignment_questions_id)->first();
        foreach($datas['select_answer'] as $key => $answer_select)
        {
            $questions_select[] = ([
                'question_on' => $key,
                'select_answer' => $answer_select,
            ]);
        }

        $check = AssigmentStudentMake::where('assignment_id',$request->assignment_id)
                                    ->where('assignment_questions_id',$request->assignment_questions_id)
                                    ->where('assigment_student_id',Auth::guard('members')->user()->_id)
                                    ->first();

        $arrayData['assignment_id'] = $request->assignment_id;
        $arrayData['assignment_questions_id'] = $request->assignment_questions_id;
        $arrayData['assigment_student_id'] = Auth::guard('members')->user()->_id;
        $arrayData['questions_type'] = $answer_questions->questions_type;
        $arrayData['questions_make'] = $questions_select;
        $arrayData['score'] = null;
        if(!empty($check))
        {
            $arrayData['updated_at'] = date('Y-m-d H:i:s');
            $arrayData['updated_by'] = Auth::guard('members')->user()->_id;
            AssigmentStudentMake::where('assignment_id',$request->assignment_id)
            ->where('assignment_questions_id',$request->assignment_questions_id)
            ->where('assigment_student_id',Auth::guard('members')->user()->_id)
            ->update($arrayData);
        }else
        {

            $arrayData['created_at'] = date('Y-m-d H:i:s');
            $arrayData['created_by'] = Auth::guard('members')->user()->_id;
            AssigmentStudentMake::create($arrayData);
        }
        $checkstudenmake = AssigmentStudentMake::where('assignment_id',$request->assignment_id)->where('assigment_student_id',Auth::guard('members')->user()->_id)->count();
        $checkAssigment = AssignmentQuestions::where('assignment_id',$request->assignment_id)->count();
        if($checkAssigment === $checkstudenmake)
        {
            $checkstatue = AssignmentStudent::where('assignment_id',$request->assignment_id)->where('student_id',Auth::guard('members')->user()->_id)->first();

            if($checkstatue == '2'){
                $updatestudent['send_assignment_status'] = '2';
            }else{
                $updatestudent['send_assignment_status'] = '2';
            }

            $updatestudent['send_assignment_date'] = date('Y-m-d');
            AssignmentStudent::where('assignment_id',$request->assignment_id)->where('student_id',Auth::guard('members')->user()->_id)->update($updatestudent);
        }else{
            $updatestudent['send_assignment_status'] = '0';
            $updatestudent['send_assignment_date'] = date('Y-m-d');
            AssignmentStudent::where('assignment_id',$request->assignment_id)->where('student_id',Auth::guard('members')->user()->_id)->update($updatestudent);
        }

        return redirect('assignment/list/'.$request->assignment_id)->with('makehomework','success');
   }

   public function make($id)
   {
        $questions = AssignmentQuestions::where('_id',$id)->with(['getAssignment'])->first();
        if($questions->questions_type == 1)
        {
            $edits = AssigmentStudentMake::where('assignment_questions_id',$id)->where('assigment_student_id',Auth::guard('members')->user()->_id)->first();
            return view('frontend.homework.question.multiplechoice',compact('questions','edits'));
        }elseif($questions->questions_type == 2){
            $edits = AssigmentStudentMake::where('assignment_questions_id',$id)->where('assigment_student_id',Auth::guard('members')->user()->_id)->first();
            return view('frontend.homework.question.matching',compact('questions','edits'));
        }elseif($questions->questions_type == 3){
            $edits = AssigmentStudentMake::where('assignment_questions_id',$id)->where('assigment_student_id',Auth::guard('members')->user()->_id)->first();
            return view('frontend.homework.question.shotanswer',compact('questions','edits'));
        }else if($questions->questions_type == 4){
            $edits = AssigmentStudentMake::where('assignment_questions_id',$id)->where('assigment_student_id',Auth::guard('members')->user()->_id)->first();
            return view('frontend.homework.question.longanswer',compact('questions','edits'));
        }
   }

   public function create_multiplechoice(Request $request,$assignment_id,$question_id = null)
   {
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
       $assignment = Assignment::where('_id',$assignment_id)->first();
       update_last_action();
       if(!empty($question_id)):
        $datas = AssignmentQuestions::where('_id',$question_id)->first();
        return view('frontend.homework.create_multiplechoice', compact('datas','subject_detail','school_detail','assignment'));
       else:
        return view('frontend.homework.create_multiplechoice', compact('subject_detail','school_detail','assignment'));
       endif;
     }
     else{
       return redirect('/');
     }


   }

   public function create_matching(Request $request,$assignment_id,$question_id = null)
   {
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
       $assignment = Assignment::where('_id',$assignment_id)->first();
       update_last_action();
       if(!empty($question_id)):
        $datas = AssignmentQuestions::where('_id',$question_id)->first();
        return view('frontend.homework.create_matching', compact('datas','subject_detail','school_detail','assignment'));
       else:
        return view('frontend.homework.create_matching', compact('subject_detail','school_detail','assignment'));
       endif;


     }
     else{
       return redirect('/');
     }
   }

   public function create_shotanswer(Request $request,$assignment_id,$question_id = null)
   {
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

       $assignment = Assignment::where('_id',$assignment_id)->first();
       update_last_action();
       if(!empty($question_id)):
        $datas = AssignmentQuestions::where('_id',$question_id)->first();
        return view('frontend.homework.create_shotanswer', compact('datas','assignment'));
       else:
        return view('frontend.homework.create_shotanswer', compact('assignment'));
       endif;


     }
     else{
       return redirect('/');
     }
   }

   public function savecreatematching(Request $request)
   {
       $countquestion = count(array_filter($request->question))-1;
        $i = 1;
        foreach ($request->question as $key => $question)
        {
            if($question)
            {
                $ArrayQuestionTeacher[] = ([
                    'question_on' => $i++,
                    'question' => $question,
                    'choice' => $request->choice[$key],
                    'answer' => $request->answer[$key],
                    'question_score' => $request->score_question[$key],
                    ]);
                $choice[] = $request->choice[$key];
                $answer[] = $request->answer[$key];
            }
        }
        $num_array = [];

        $cehck_num = $this->random_key($num_array,count($answer));
        if($cehck_num)
        {
            foreach($ArrayQuestionTeacher as $key => $data)
            {
                $ArrayQuestionStuden[] = ([
                    'question_on' => $data['question_on'],
                    'question' => $data['question'],
                    'choice' => $choice[$cehck_num[$key]],
                    'answer' => $answer[$cehck_num[$key]],
                    'question_score' => $data['question_score'],
                ]);
            }
            $ArrayCreate['assignment_id'] = $request->assignment_id;
            $ArrayCreate['questions'] = $ArrayQuestionTeacher;
            $ArrayCreate['questions_studen'] = $ArrayQuestionStuden;
            $ArrayCreate['questions_type'] = '2';
            $ArrayCreate['created_at'] = date('Y-m-d H:i:s');
            $ArrayCreate['created_by'] =  Auth::guard('members')->user()->_id;
            AssignmentQuestions::create($ArrayCreate);
            return redirect()->to('homework/teacher/manage/'.$request->assignment_id);
        }

   }

   function random_key($a,$b)
   {
        $n = $b-1;
        $bb = $b;
        $a[] = rand(0,$n);
        $a = array_unique($a);
        if(count($a) == $bb)
        {
            return $a;
        }else{
            return $this->random_key($a,$b);
        }
   }

   public function savecreateshotanswer(Request $request)
   {
        foreach ($request->question as $key => $question)
        {
            if($question)
            {
                $ArrayQuestion[] = ([
                    'question_on' => $key,
                    'question' => $question,
                    'answer' => $request->answer[$key],
                    'question_score' => $request->score_question[$key],
                    ]);
            }
        }
        $ArrayCreate['assignment_id'] = $request->assignment_id;
        $ArrayCreate['questions'] = $ArrayQuestion;
        $ArrayCreate['questions_type'] = '3';
        $ArrayCreate['created_at'] = date('Y-m-d H:i:s');
        $ArrayCreate['created_by'] =  Auth::guard('members')->user()->_id;
        AssignmentQuestions::create($ArrayCreate);
        return redirect()->to('homework/teacher/manage/'.$request->assignment_id);
   }

   public function save_create_multiplechoice(Request $request)
    {
        // dd($request);
        $multiplechoice = [];
        $i = 1;
        $ke = 0;
        foreach ($request->question as $key => $value) {
            $answer_text = "answer".$i;
            $answer = $request->$answer_text;
           // print_r('<pre>');
           // print_r($request->$answer_text);
           // print_r('<pre>');
            $questions[] = ([
                "question_on"=> ($i),
                "question"=> $value,
                "choice_1"=> $request->answer_texta[$ke],
                "choice_2"=> $request->answer_textb[$ke],
                "choice_3"=> $request->answer_textc[$ke],
                "choice_4"=> $request->answer_textd[$ke],
                "answer"=> $answer,
                "question_score"=> $request->score_question[$ke]
            ]);
            $i++;
            $ke++;
        }

        $multiplechoice = [
            'questions_type' => "1",
            'questions' => $questions,
            'assignment_id' => $request->assignment_id,
        ];

        AssignmentQuestions::create($multiplechoice);
        // dd($multiplechoice);
        return redirect()->to('homework/teacher/manage/'.$request->assignment_id);
    }

   public function edit_multiplechoice($assignment_id,$question_id)
   {
    //    dd($assignment_id,$question_id);
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
       $assignment = Assignment::where('_id',$assignment_id)->first();
       update_last_action();
       if(!empty($question_id)):
        $datas = AssignmentQuestions::where('_id',$question_id)->first();
        return view('frontend.homework.create_multiplechoice', compact('datas','subject_detail','school_detail','assignment'));
       else:
        return view('frontend.homework.create_multiplechoice', compact('subject_detail','school_detail','assignment'));
       endif;


     }
     else{
       return redirect('/');
     }
   }

   public function save_edit_multiplechoice(Request $request)
   {
     // dd($request->score_question);

    $multiplechoice_edit = [];
    $i = 1;
    $ke = 0;
    $score_no = 1;
    $score_question = [];
    $question_no = 1;
    $question = [];

    foreach ($request->score_question as $ke0 => $val) {
      $score_question[$score_no] = $val;
      $score_no++;
    }

    foreach ($request->question as $ke1 => $val1) {
      $question[$question_no] = $val1;
      $question_no++;
    }

    // dd($request,$request->answer_texta);
    foreach ($question as $key => $value) {
        $answer_text = "answer".$i;
        $answer = $request->$answer_text;
        // print_r('<pre>');
        // print_r($request->answer_texta[$ke]);
        // print_r('<pre>');
         $questions[] = ([
             "question_on"=> ($i),
             "question"=> $value,
             "choice_1"=> $request->answer_texta[$ke],
             "choice_2"=> $request->answer_textb[$ke],
             "choice_3"=> $request->answer_textc[$ke],
             "choice_4"=> $request->answer_textd[$ke],
             "answer"=> $answer,
             "question_score"=> $score_question[$i]
         ]);
         $i++;
         $ke++;
     }

     $multiplechoice_edit = [
      'questions_type' => "1",
      'questions' => $questions,
      'assignment_id' => $request->assignment_id,
     ];

     // dd($multiplechoice_edit);

     AssignmentQuestions::where('_id',$request->question_id)->update($multiplechoice_edit);
     return redirect()->to('homework/teacher/manage/'.$request->assignment_id);
   }

   public function saveeditmatching(Request $request)
   {
        foreach ($request->question as $key => $question) {
            if($question)
            {
                $ArrayEdit[] = ([
                    'question_on' => $key,
                    'question' => $question,
                    'choice' => $request->choice[$key],
                    'answer' => $request->answer[$key],
                    'question_score' => $request->score_question[$key],
                    ]);
                    $choice[] = $request->choice[$key];
                    $answer[] = $request->answer[$key];
            }

        }
        $num_array = [];

        $cehck_num = $this->random_key($num_array,count($answer));
        if($cehck_num)
        {
            foreach($ArrayEdit as $key => $data)
            {
                $ArrayQuestionStuden[] = ([
                    'question_on' => $data['question_on'],
                    'question' => $data['question'],
                    'choice' => $choice[$cehck_num[$key]],
                    'answer' => $answer[$cehck_num[$key]],
                    'question_score' => $data['question_score'],
                ]);
            }

            $array_update['questions'] = $ArrayEdit;
            $array_update['questions_studen'] = $ArrayQuestionStuden;
            $array_update['updated_at'] = date('Y-m-d H:i:s');
            $array_update['updated_by'] = Auth::guard('members')->user()->_id;
            AssignmentQuestions::where('_id',$request->question_id)->where('assignment_id',$request->assignment_id)->update($array_update);
            return redirect()->to('homework/teacher/manage/'.$request->assignment_id);
        }

   }
   public function saveeditshotanswer(Request $request)
   {
        foreach ($request->question as $key => $question) {
            if($question)
            {
                $ArrayEdit[] = ([
                    'question_on' => $key,
                    'question' => $question,
                    'answer' => $request->answer[$key],
                    'question_score' => $request->score_question[$key],
                    ]);
            }

        }
        $array_update['questions'] = $ArrayEdit;
        $array_update['updated_at'] = date('Y-m-d H:i:s');
        $array_update['updated_by'] = Auth::guard('members')->user()->_id;
        // dd($array_update);
        AssignmentQuestions::where('_id',$request->question_id)->where('assignment_id',$request->assignment_id)->update($array_update);
        return redirect()->to('homework/teacher/manage/'.$request->assignment_id);
        // dd($request->all(),$ArrayEdit);
   }

//    public function create_shotanswer()
//    {
//      if(Auth::guard('members')->user()->member_role =='teacher'){
//        $members = Member::where('_id', '=', Auth::guard('members')->user()->id)->where('member_status', '1')->first();
//        $member_aptitude = $members->member_aptitude;

//        $subject_detail = array();

//        foreach (array_keys($member_aptitude) as $key){
//            if(count($member_aptitude[$key]) > 0){
//                $aptitude = Aptitude::where('_id', '=', $key)->first();
//                if($aptitude){
//                    $subject_detail[$key]['aptitude_name_en'] = $aptitude->aptitude_name_en;
//                    $subject_detail[$key]['aptitude_name_th'] = $aptitude->aptitude_name_th;
//                }
//            }
//        }

//        $school_detail = array();
//        $school = School::where('school_status', '1')->where('school_teacher.teacher_email', $members->member_email)->first();
//        // dd($school,$members->member_email,$members);
//        if(isset($members->member_school) && isset($school->_id)){
//          $school_detail['school_id'] = $members->member_school;
//          $school_detail['school_name_th'] = $school->school_name_th;
//          $school_detail['school_name_en'] = $school->school_name_en;
//          $school_detail['school_student'] = $school->school_student;
//        }
//        else{
//          $school_detail['school_id'] = '';
//          $school_detail['school_name_th'] = '';
//          $school_detail['school_name_en'] = '';
//          $school_detail['school_student'] = '';
//        }

//        update_last_action();

//        return view('frontend.homework.create_shotanswer', compact('subject_detail','school_detail'));
//      }
//      else{
//        return redirect('/');
//      }
//    }

   public function create_longanswer($assignment_id)
   {
        $assignment_id = $assignment_id;
        $assignment = Assignment::where('_id',$assignment_id)->first();
        update_last_action();
        return view('frontend.homework.create_longanswer', compact('assignment_id','assignment'));
        // return view('frontend.homework.create_longanswer_copy', compact('assignment_id','assignment'));

   }

   public function save_create_longanswer(Request $request)
   {
    //  dd($request);

    $longanswer = [];
    foreach ($request->question as $key => $question) {
        if($question)
        {
            $ArrayEdit[] = ([
                'question_on' => ($key+1),
                'question' => $question,
                'question_score' => $request->score_question[$key],
                ]);

        }
    }

    $longanswer = [
        'questions_type' => "4",
        'questions' => $ArrayEdit,
        'assignment_id' => $request->assignment_id,
    ];

    // dd($longanswer);
    AssignmentQuestions::create($longanswer);
    return redirect()->to('homework/teacher/manage/'.$request->assignment_id);

   }

   public function edit_longanswer($assignment_id,$question_id)
   {
    //    dd($assignment_id,$question_id);
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
       $assignment = Assignment::where('_id',$assignment_id)->first();
       update_last_action();
       if(!empty($question_id)):
        $datas = AssignmentQuestions::where('_id',$question_id)->first();
        return view('frontend.homework.create_longanswer', compact('datas','subject_detail','school_detail','assignment'));
       else:
        return view('frontend.homework.create_longanswer', compact('subject_detail','school_detail','assignment'));
       endif;


     }
     else{
       return redirect('/');
     }
   }

   public function save_edit_longanswer(Request $request)
   {

    $longanswer = [];
    $no = 1;
    foreach ($request->question as $key => $question) {
        if($question)
        {
            $ArrayEdit[] = ([
                'question_on' => $no,
                'question' => $question,
                'question_score' => $request->score_question[$key],
                ]);

                $no++;
        }
    }

    $longanswer = [
        'questions_type' => "4",
        'questions' => $ArrayEdit,
        'assignment_id' => $request->assignment_id,
        'question_id' => $request->question_id,
    ];
    // dd($longanswer);

    AssignmentQuestions::where('_id',$request->question_id)->update($longanswer);
    return redirect()->to('homework/teacher/manage/'.$request->assignment_id);
   }

   public function teacher(Request $request)
   {
    //    dd($request->all());
       $inputs = $request->all();

       if(!empty($inputs['title']))
       {
        $title = $inputs['title'];
       }else{
           $title = '';
       }

       if(!empty($inputs['subject']))
       {
        $subject = $inputs['subject'];
       }else{
           $subject = '';
       }

       if(!empty($inputs['status']))
       {
        $status = $inputs['status'];
       }else{
           $status = '';
       }
    //    dd($status);
        $query = new Assignment;
            if($title != '')
            {
                $query = $query->where('assignment_name','like','%'.$title.'%');
            }
            if($subject != '')
            {
                $query = $query->where('subject_id',$subject);
            }
            if($status != '')
            {
                $query = $query->where('assignment_status',$status);
            }
        $datas = $query->where('teacher_id',Auth::guard('members')->user()->id)->with(['getSubject','getQuestions'])->orderBy('assignment_status','asc')->orderBy('created_at','desc')->get();
        $subjects = Subject::where('is_master','1')->get();
        // dd($datas);
        return view('frontend.homework.teacher.list',compact('datas','subjects','subject','title','status'));
   }

   public function teacherlist($id)
   {
       $assignmentstudents = AssignmentStudent::where('assignment_id',$id)->with(['getStudent','getQuestionsType'])->get();
       $types = AssignmentQuestions::select('questions_type','questions')->where('assignment_id',$id)->orderBy('questions_type','asc')->get();
       $datas = [];

       foreach($assignmentstudents as $key => $assignmentstudent)
       {


            $datas[$key]['fullname'] = @$assignmentstudent->getStudent->member_fname.' '.@$assignmentstudent->getStudent->member_lname;
            $senddate =  AssigmentStudentMake::select('assignment_questions_id','assigment_student_id','assignment_id','score','created_at')->where('assignment_id',$assignmentstudent->assignment_id)
                        ->where('assigment_student_id',$assignmentstudent->student_id)
                        ->orderBy('created_at','desc')
                        ->first();
            if(!empty($senddate))
            {
                $datas[$key]['senddate'] = date('Y-m-d H:i',strtotime($senddate->created_at));
            }else{
                $datas[$key]['senddate'] = null;
            }
            foreach($assignmentstudent->getQuestionsType as $key2 => $questionstype)
            {
                if($questionstype->questions_type == 1){
                    $studemake = AssigmentStudentMake::select('assignment_questions_id','assigment_student_id','assignment_id','score','questions_type','created_at')->where('assignment_id',$assignmentstudent->assignment_id)
                                ->where('assigment_student_id',$assignmentstudent->student_id)
                                ->where('questions_type','1')
                                ->with(['getAssignmentQuestion'])
                                ->first();
                    $datas[$key]['make'][$key2] = (!empty($studemake) ? $studemake : null);
                }else if($questionstype->questions_type == 2){
                    $studemake = AssigmentStudentMake::select('assignment_questions_id','assigment_student_id','assignment_id','score','questions_type','created_at')->where('assignment_id',$assignmentstudent->assignment_id)
                                ->where('assigment_student_id',$assignmentstudent->student_id)
                                ->where('questions_type','2')
                                ->with(['getAssignmentQuestion'])
                                ->first();
                    $datas[$key]['make'][$key2] = (!empty($studemake) ? $studemake : null);

                }else if($questionstype->questions_type == 3){
                    $studemake = AssigmentStudentMake::select('assignment_questions_id','assigment_student_id','assignment_id','score','questions_type','created_at')->where('assignment_id',$assignmentstudent->assignment_id)
                                ->where('assigment_student_id',$assignmentstudent->student_id)
                                ->where('questions_type','3')
                                ->with(['getAssignmentQuestion'])
                                ->first();
                    $datas[$key]['make'][$key2] = (!empty($studemake) ? $studemake : null);

                }else if($questionstype->questions_type == 4){
                    $studemake = AssigmentStudentMake::select('assignment_questions_id','assigment_student_id','assignment_id','score','questions_type','created_at')->where('assignment_id',$assignmentstudent->assignment_id)
                                ->where('assigment_student_id',$assignmentstudent->student_id)
                                ->where('questions_type','4')
                                ->with(['getAssignmentQuestion'])
                                ->first();
                    $datas[$key]['make'][$key2] = (!empty($studemake) ? $studemake : null);
                }
            }
       }
    //    dd($types);
       $assignment = Assignment::where('_id',$id)->first();
       return view('frontend.homework.teacher.checked',compact('datas','types','assignment'));
   }

   public function checkedquestion($assignment_id,$student_id,$questions_id)
   {
       $studemake = AssigmentStudentMake::where('assignment_questions_id',$questions_id)->where('assigment_student_id',$student_id)->first();
       $questions = AssignmentQuestions::where('_id',$questions_id)->with(['getAssignment'])->first();
        // dd($questions);
        if($questions->questions_type == 1){
            return view('frontend.homework.teacher.checkeds.multiplechoice',compact('questions','studemake'));
        }elseif($questions->questions_type == 2){
            return view('frontend.homework.teacher.checkeds.matching',compact('questions','studemake'));
        }elseif($questions->questions_type == 3){
            $answer = [];
            foreach($questions->questions as $key => $item)
            {
                $answer[] =([
                    'question_on' => $item['question_on'],
                    'question' => $item['question'],
                    'answer' => str_replace(' ','',$item['answer']),
                    'question_score' => $item['question_score'],
                ]);

            }

            foreach($studemake->questions_make as $key => $answer_select)
            {
                $questions_select[] = ([
                    'select_answer' => str_replace(' ','',$answer_select),
                ]);
            }
            $collection_answer = collect($answer);
            // dd($collection_answer,$questions_select);
            foreach($collection_answer as $key => $items)
            {

                if($items['question_on'] == $questions_select[$key]['select_answer']['question_on'])
                {
                    $array_answer = explode(',',$items['answer']);
                    $array_select = explode(',',$questions_select[$key]['select_answer']['select_answer']);
                    $check = array_intersect($array_select,$array_answer);
                    if(!empty($check))
                    {
                        $questions_test[$key] = $items;
                        $questions_test[$key]['check'] = 'true';
                    }else{
                        $questions_test[$key] = $items;
                        $questions_test[$key]['check'] = 'false';
                    }
                }
            }
            return view('frontend.homework.teacher.checkeds.shotanswer',compact('questions','studemake','questions_test'));
        }elseif($questions->questions_type == 4){
            return view('frontend.homework.teacher.checkeds.longanswer',compact('questions','studemake'));
        }

   }

   public function teachercheck(Request $request)
   {
    // dd($request);
        $questions = AssignmentQuestions::where('_id',$request->assignment_questions_id)->first();
        $score = 0;
        $question_on = 1;



        foreach($request->question_check as $check)
        {
            $question_check[] = ([
                'question_on' => $question_on,
                'select' => $check,
            ]);

            $question_on++;
        }

        // dd(array_sum($request->question_check),$question_check);

        $array_update['score'] = array_sum($request->question_check);
        $array_update['teacher_check'] = $question_check;
        AssigmentStudentMake::where('assignment_questions_id',$request->assignment_questions_id)
                                        ->where('assignment_id',$request->assignment_id)
                                        ->where('assigment_student_id',$request->student_id)
                                        ->update($array_update);
        return redirect('homework/teacher/assignment/'.$request->assignment_id)->with('makehomework','success');
   }

    public function ConjobSendNotiTeacher()
    {
        $today = date('Y-m-d');
        $querys = Assignment::where('assignment_status','1')->where('assignment_date_start','<=',$today)->orderBy('created','asc')->get();
        foreach($querys as $query)
        {
            $query->assignment_status = '2';
            if($query->save())
            {
                if(request()->getHttpHost() == 'suksa.online')
                {
                    $hostname = 'https://'.request()->getHttpHost();
                }else{
                    $hostname = 'http://'.request()->getHttpHost();
                }

                $arrayCreate['assignment_id'] = $query->_id;
                $arrayCreate['assignment_name'] = $query->assignment_name;
                $arrayCreate['assignment_date_start'] = $query->assignment_date_start;
                $arrayCreate['assignment_date_end'] = $query->assignment_date_end;
                $arrayCreate['member_id'] = $query->teacher_id;
                $arrayCreate['member_fullname'] = $query->assignment_teacher['teacher_fname'].' '.$query->assignment_teacher['teacher_lname'];
                $arrayCreate['noti_type'] = 'checkend_homework';
                $arrayCreate['noti_status'] = '0';
                $arrayCreate['queue_status'] = '0';
                QueueNoti::create($arrayCreate);


            }



        }

    }

    public function ConjobSendNotiTeacherq()
    {

        $today = date('Y-m-d');
        $querys = QueueNoti::where('queue_status','0')->whereNotNull('assignment_id')->limit(50)
        ->orderBy('assignment_id','asc')
        ->get();

        foreach($querys as $query)
        {
            // dd($query->assignment_id);
            $noti = MemberNotification::where('assignment_id',$query->assignment_id)->where('noti_type','checkend_homework')->first();
            // dd($noti);
            if(empty($noti))
            {
                // dd($query);
                $query->queue_status = '1';
                if($query->save())
                {
                    $arrayCreate['assignment_id'] = $query->assignment_id;
                    $arrayCreate['assignment_name'] = $query->assignment_name;
                    $arrayCreate['assignment_date_start'] = $query->assignment_date_start;
                    $arrayCreate['assignment_date_end'] = $query->assignment_date_end;
                    $arrayCreate['member_id'] = $query->member_id;
                    $arrayCreate['member_fullname'] = $query->member_fullname;
                    $arrayCreate['noti_type'] = 'checkend_homework';
                    $arrayCreate['noti_status'] = '0';
                    $arrayCreate['check_update'] = '0';
                    MemberNotification::create($arrayCreate);
                    $niti_send = MemberNotification::where('assignment_id',$query->assignment_id)->where('noti_type','checkend_homework')->first();
                    if(!empty($niti_send))
                    {
                        if($niti_send->check_update == '0')
                        {
                            $niti_send->check_update = '1';
                            if($niti_send->save())
                            {

                                sendMemberNoti($niti_send->member_id);
                            }
                        }
                        // $niti_send->save();
                    }
                }
            }



        }

    }

    public function create_homework_topics(Request $request)
    {
        $assignment_teacher = (object) [
            "teacher_id"=> $request->data['assignment_teacher']['teacher_id'],
            "teacher_fname"=> $request->data['assignment_teacher']['teacher_fname'],
            "teacher_lname"=> $request->data['assignment_teacher']['teacher_lname'],
            "teacher_email"=> $request->data['assignment_teacher']['teacher_email']
        ];


        $data = ([
            "subject_id" => $request->data['subject_id'],
            "assignment_name" => $request->data['assignment_name'],
            "assignment_detail" => "",
            "assignment_date_start" => date('Y-m-d',strtotime($request->data['assignment_date_start'])),
            "assignment_time_start" => $request->data['assignment_time_start'],
            "assignment_date_end" => date('Y-m-d',strtotime($request->data['assignment_date_end'])),
            "assignment_time_end" => $request->data['assignment_time_end'],
            "assignment_status" => "1",
            "assignment_teacher" => $assignment_teacher,
            "teacher_id" => $request->data['teacher_id'],
            "student_classroom" => $request->data['student_classroom'],
            "aptitude_id" => $request->data['aptitude_id'],
        ]);

        $newObject =  Assignment::create($data);
        $assignment_student = json_decode($request->data['assignment_student']);
        foreach ($assignment_student as $key => $value) {
            $AssignmentStudent_data = ([
                "student_email"=> $value->student_email,
                "student_fname"=> $value->student_fname,
                "student_lname"=> $value->student_lname,
                "student_class"=> $value->student_class,
                "student_classroom"=> $value->student_classroom,
                "student_room"=> $value->student_room,
                "student_tel"=> $value->student_tel,
                "student_id"=> @$value->student_id,
                "assignment_id"=> $newObject->_id,
                "send_assignment_status"=> "1",
            ]);


            AssignmentStudent::create($AssignmentStudent_data);
                $arrayAdd['assignment_id'] = $newObject->_id;
                $arrayAdd['assignment_name'] = $newObject->assignment_name;
                $arrayAdd['assignment_date_start'] = $newObject->assignment_date_start;
                $arrayAdd['assignment_time_start'] = $newObject->assignment_time_start;
                $arrayAdd['assignment_date_end'] = $newObject->assignment_date_end;
                $arrayAdd['assignment_time_end'] = $newObject->assignment_time_end;
                $arrayAdd['teacher_id'] = $newObject->teacher_id;
                $arrayAdd['teacher_fullname'] = $request->data['assignment_teacher']['teacher_fname'].' '.$request->data['assignment_teacher']['teacher_lname'];
                $arrayAdd['member_id'] = @$value->student_id;
                $arrayAdd['member_fullname'] = $value->student_fname.' '.$value->student_lname;
                $arrayAdd['student_id'] = @$value->student_id;
                $arrayAdd['student_fullname'] = $value->student_fname.' '.$value->student_lname;
                $arrayAdd['noti_type'] = 'homework_studen';
                $arrayAdd['noti_status'] = '0';
            $noti = MemberNotification::create($arrayAdd);
            if($noti)
            {
                sendMemberNoti($noti->member_id);
            }
        }

        return response()->json(['status' => true, 'message' => "success"]);

    }

    private function paginate($items, $page = 1 ,$perPage = 10, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count() , $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
        // return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function ConjobUpdateStatus()
    {
        $datas = Assignment::where('assignment_status','2')
                ->offset(0)
                ->limit(10)
                ->with(['getStudens','getQuestions','getStudentMake'])
                ->orderBy('created_at','asc')
                ->get();
        foreach($datas as $data)
        {
            $num_stude = count($data->getStudens);
            $num_questions = count($data->getQuestions);
            $sutden_use_make = $num_stude*$num_questions;
            $count_checked = count($data->getStudentMake);
            if($num_questions == 0)
            {
                $data->assignment_status = '5';
                $data->save();
            }else{
                if($count_checked == $sutden_use_make)
                {
                    $data->assignment_status = '3';
                    $data->save();
                }
            }

        }
    }

    public function assignmenassay($assignmen,$question)
    {
        $questions = AssignmentQuestions::where('_id',$question)->first();
        $studemake = AssigmentStudentMake::where('assignment_questions_id',$question)->where('assignment_id',$assignmen)->where('assigment_student_id',Auth::guard('members')->user()->id)->first();

        if($questions->questions_type == '1')
        {
            return view('frontend.homework.studen.multiplechoice',compact('questions','studemake'));
        }else if($questions->questions_type == '2')
        {
            return view('frontend.homework.studen.matching',compact('questions','studemake'));
        }else if($questions->questions_type == '3')
        {
            $answer = [];
            foreach($questions->questions as $key => $item)
            {
                $answer[] =([
                    'question_on' => $item['question_on'],
                    'question' => $item['question'],
                    'answer' => str_replace(' ','',$item['answer']),
                    'question_score' => $item['question_score'],
                ]);

            }

            foreach($studemake->questions_make as $key => $answer_select)
            {
                $questions_select[] = ([
                    'select_answer' => str_replace(' ','',$answer_select),
                ]);
            }
            $collection_answer = collect($answer);
            // dd($collection_answer,$questions_select);
            foreach($collection_answer as $key => $items)
            {

                if($items['question_on'] == $questions_select[$key]['select_answer']['question_on'])
                {
                    $array_answer = explode(',',$items['answer']);
                    $array_select = explode(',',$questions_select[$key]['select_answer']['select_answer']);
                    $check = array_intersect($array_select,$array_answer);
                    if(!empty($check))
                    {
                        $questions_test[$key] = $items;
                        $questions_test[$key]['check'] = 'true';
                    }else{
                        $questions_test[$key] = $items;
                        $questions_test[$key]['check'] = 'false';
                    }
                }
            }
            return view('frontend.homework.studen.shotanswer',compact('questions','questions_test','studemake'));

        }else if($questions->questions_type == '4')
        {
            return view('frontend.homework.studen.longanswer',compact('questions','studemake'));
        }
    }

    public function export($id)
    {
        $assignmentstudents = AssignmentStudent::where('assignment_id',$id)->with(['getStudent','getQuestionsType'])->get();
        $types = AssignmentQuestions::select('questions_type','questions')->where('assignment_id',$id)->orderBy('questions_type','asc')->get();
        $datas = [];

       foreach($assignmentstudents as $key => $assignmentstudent)
       {
            $datas[$key]['fullname'] = @$assignmentstudent->getStudent->member_fname.' '.@$assignmentstudent->getStudent->member_lname;
            $senddate =  AssigmentStudentMake::select('assignment_questions_id','assigment_student_id','assignment_id','score','created_at')->where('assignment_id',$assignmentstudent->assignment_id)
                        ->where('assigment_student_id',$assignmentstudent->student_id)
                        ->orderBy('created_at','desc')
                        ->first();
            if(!empty($senddate))
            {
                $datas[$key]['senddate'] = date('Y-m-d H:i',strtotime($senddate->created_at));
            }else{
                $datas[$key]['senddate'] = null;
            }

            foreach($assignmentstudent->getQuestionsType as $key2 => $questionstype)
            {
                if($questionstype->questions_type == 1){
                    $studemake = AssigmentStudentMake::select('assignment_questions_id','assigment_student_id','assignment_id','score','created_at')->where('assignment_id',$assignmentstudent->assignment_id)
                                ->where('assigment_student_id',$assignmentstudent->student_id)
                                ->where('questions_type','1')
                                ->with(['getAssignmentQuestion'])
                                ->first();
                    $datas[$key]['make'][$key2] = (!empty($studemake) ? $studemake->score : 'ยังไม่ได้ทำ');
                }else if($questionstype->questions_type == 2){
                    $studemake = AssigmentStudentMake::select('assignment_questions_id','assigment_student_id','assignment_id','score','created_at')->where('assignment_id',$assignmentstudent->assignment_id)
                                ->where('assigment_student_id',$assignmentstudent->student_id)
                                ->where('questions_type','2')
                                ->with(['getAssignmentQuestion'])
                                ->first();
                    $datas[$key]['make'][$key2] = (!empty($studemake) ? $studemake->score : 'ยังไม่ได้ทำ');

                }else if($questionstype->questions_type == 3){
                    $studemake = AssigmentStudentMake::select('assignment_questions_id','assigment_student_id','assignment_id','score','created_at')->where('assignment_id',$assignmentstudent->assignment_id)
                                ->where('assigment_student_id',$assignmentstudent->student_id)
                                ->where('questions_type','3')
                                ->with(['getAssignmentQuestion'])
                                ->first();
                    $datas[$key]['make'][$key2] = (!empty($studemake) ? $studemake->score : 'ยังไม่ได้ทำ');

                }else if($questionstype->questions_type == 4){
                    $studemake = AssigmentStudentMake::select('assignment_questions_id','assigment_student_id','assignment_id','score','created_at')->where('assignment_id',$assignmentstudent->assignment_id)
                                ->where('assigment_student_id',$assignmentstudent->student_id)
                                ->where('questions_type','4')
                                ->with(['getAssignmentQuestion'])
                                ->first();
                    $datas[$key]['make'][$key2] = (!empty($studemake) ? $studemake->score : 'ยังไม่ได้ทำ');
                }
            }
       }
       $assignment = Assignment::where('_id',$id)->first();
        $arrayDatas[0] = trans('frontend/homework/title.namelastname');
        foreach($types as $key => $type)
        {
            $arrayDatas[$key+1] = HomeworkType($type->questions_type).'/'.trans('frontend/homework/title.score').'('.collect($type->questions)->sum('question_score').')';
        }
        // $items
        foreach($datas as $key => $data)
        {
            $items[$key][] = $data['fullname'];
            if(!empty($data['make']))
            {
                foreach ($data['make'] as $make)
                {
                    $items[$key][] = $make;
                }
            }
        }
        $export['title'] = $arrayDatas;
        $export['items'] = $items;

        Excel::create($assignment->assignment_name,function($excel) use($export){
            $excel->sheet('Sheet 1', function($sheet) use($export){
                $sheet->row(1,$export['title']);
                foreach($export['items'] as $key => $item)
                {
                    $sheet->row($key+2,$item);
                }

            });
        })->download('xlsx');
        #Export to CSV
    }

    public function searachassignment(Request $request)
    {
        // Auth::guard('members')->user()->id
        $query = new AssignmentStudent;
        $query = $query->where('student_id',Auth::guard('members')->user()->_id);

            if($request->status != '' && $request->status != null)
            {
                $query = $query->where('send_assignment_status',$request->status);
            }

            if($request->title != '' && $request->title != null)
            {
                $assignments = Assignment::where('assignment_name','like','%'.$request->title.'%')->get();
                $IdQuery = [];
                foreach($assignments as $key => $assignment)
                {
                    $IdQuery[$key] = $assignment['_id'];
                }
                $query = $query->whereIn('assignment_id',$IdQuery);
            }

            if($request->subject != '' || $request->subject != null)
            {
                $assignments = Assignment::where('subject_id',$request->subject)->get();
                $IdQuery = [];
                foreach($assignments as $key => $assignment)
                {
                    $IdQuery[$key] = $assignment['_id'];
                }
                $query = $query->whereIn('assignment_id',$IdQuery);
            }

        $datas = $query->orderBy('send_assignment_status','asc')
                ->orderBy('send_assignment_date','asc')
                ->with(['getAssignment'])
                ->get();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = Collect($datas);
        $per_page = 10;
        $currentPageResults = $collection->slice(($currentPage-1) * $per_page, $per_page)->all();
        $data_gen = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);
        $gen = $this->genhtml($data_gen,'stunden');
        $arrayData['datas'] = $gen['html'];
        $arrayData['paginator'] = $gen['pagination'];
        return $arrayData;
    }

    public function searachteacher(Request $request)
    {
        $query = new Assignment;
        if($request->title != '')
        {
            $query = $query->where('assignment_name','like','%'.$request->title.'%');
        }
        if($request->subject != '')
        {
            $query = $query->where('subject_id',$request->subject);
        }
        if($request->status != '')
        {
            $query = $query->where('assignment_status',$request->status);
        }
        $datas = $query->where('teacher_id',Auth::guard('members')->user()->id)->with(['getSubject','getQuestions'])->orderBy('assignment_status','asc')->orderBy('created_at','desc')->get();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = Collect($datas);
        $per_page = 10;
        $currentPageResults = $collection->slice(($currentPage-1) * $per_page, $per_page)->all();
        $data_gen = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);
        $gen = $this->genhtml($data_gen,'teacher');
        $arrayData['datas'] = $gen['html'];
        $arrayData['paginator'] = $gen['pagination'];
        return $arrayData;
    }

    public function genhtml($datas,$type)
    {
        $lang = Auth::guard('members')->user()->member_lang;
        $html = '';
        $i = 1;
        if($type == 'stunden')
        {
            foreach($datas as $data)
            {
                $html .= '<tr>';
                    $html .= '<td>'.$i++.'</td>';
                    $html .= '<td>'.$data->getAssignment->assignment_name.'</td>';
                    $html .= '<td>';
                    if($lang == 'en'){
                        $html .= $data->getAssignment->getSubject->subject_name_en;
                    }
                    else
                    {
                        $html .=  $data->getAssignment->getSubject->subject_name_th;
                    }

                    $html .='</td>';
                    $html .= '<td>'.date('d/m/Y',strtotime($data->getAssignment->assignment_date_end)).'</td>';
                    $html .= '<td>'.HomeworkStatusStuden($data->send_assignment_status).'</td>';
                    $html .= '<td style="text-align: center;"><a href="'.url('assignment/list/'.$data->assignment_id).'" class="btn btn-link link" target="_blank"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>'.trans('frontend/homework/title.detail').'</a></td>';
                $html .= '</tr>';

            }
        }else if($type == 'teacher')
        {
            foreach($datas as $data)
            {
                $html .= '<tr>';
                    $html .= '<td>'.$i++.'</td>';
                    $html .= '<td>'.$data->assignment_name.'</td>';
                    $html .= '<td>';
                    if($lang == 'en'){
                        $html .= $data->getSubject->subject_name_en;
                    }
                    else
                    {
                        $html .=  $data->getSubject->subject_name_th;
                    }
                    $html .='</td>';
                    $html .='<td>'.$data->student_classroom.'</td>';
                    $html .='<td>'.date('d/m/Y H:i',strtotime($data->assignment_date_start.' '.$data->assignment_time_start)).'</td>';
                    $html .='<td>'.date('d/m/Y H:i',strtotime($data->assignment_date_end.' '.$data->assignment_time_end)).'</td>';
                    $html .= '<td>'.HomeworkStatusTeacher($data->assignment_status).'</td>';
                    $html .= '<td>';
                    if($data->assignment_date_start.' '.$data->assignment_time_start < date('Y-m-d H:i')){
                        if(count($data->getQuestions) == 0)
                        {
                            $html .='<a href="'.url('homework/teacher/assignment/del/'.$data->_id).'" class="btn btn-danger delbtn"><i class="fa fa-trash" aria-hidden="true"></i> '.trans('frontend/homework/title.delete').'</a>';
                        }else{
                            $html .= '<a href="'.url('homework/teacher/assignment/'.$data->_id).'" class="btn btn-link link" target="_blank"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> '.trans('frontend/homework/title.detail').'</a>';
                        }
                    }else{
                            $html .= '<a href="'.url('homework/teacher/assignment/'.$data->_id).'" class="btn btn-link link" target="_blank"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> '.trans('frontend/homework/title.detail').'</a>';
                    }
                    $html .='</td>';
                $html .= '</tr>';
            }
        }
        $pagination = '';
        if($datas->total() >10)
        {
            $prev = $datas->currentPage() -1;
            $next = $datas->currentPage() + 1;
            $pagination = '<div class="page">';
                $pagination .= '<div class="numpad">';
                    $pagination .= '<nav aria-label="Page navigation">';
                        $pagination .= ' <ul class="pagination pagination">';
                        if ($datas->onFirstPage()){
                            $pagination .= '<li class="disabled"><span>«</span></li>';
                        }else{
                            $pagination .= '<li><a onclick="search_page('.$prev.')" rel="prev">«</a></li>';
                        }
                        if($datas->currentPage() > 4){

                            $pagination .= '<li class="hidden-xs"><a onclick="search_page(1)">1</a></li>';
                        }
                        if($datas->currentPage() > 5){

                            $pagination .= '<li class="hidden-xs"><a onclick="search_page(2)">2</a></li>';
                        }
                        if($datas->currentPage() > 3){

                            $pagination .= ' <li><span>...</span></li>';
                        }
                        foreach(range(1, $datas->lastPage()) as $i)
                        {
                            if($i >= $datas->currentPage() - 3 && $i <= $datas->currentPage() + 3){
                                if ($i == $datas->currentPage()){
                                    $pagination .= '<li class="active"><span>'.$i.'</span></li>';
                                }else{

                                    $pagination .= '<li><a onclick="search_page('.$i.')">'.$i.'</a></li>';
                                }
                            }
                        }
                        if($datas->currentPage() < $datas->lastPage() - 3){

                            $pagination .= '<li><span>...</span></li>';
                        }
                        if($datas->currentPage() < $datas->lastPage() - 3)
                        {
                            $pagination .= '<li class="hidden-xs"><a onclick="search_page('.$datas->lastPage().')" >'.$datas->lastPage().'</a></li>';
                        }

                        if ($datas->hasMorePages()){

                            $pagination .= '<li><a onclick="search_page('.$next.')" rel="next">»</a></li>';
                        }else{
                            $pagination .= '<li class="disabled"><span>»</span></li>';
                        }
                        $pagination .= '</ul>';
                    $pagination .= '</nav>';
                $pagination .= '</div>';
            $pagination .= '</div>';
        }


        $data['html'] = $html;
        $data['pagination'] = $pagination;
        return $data;
    }

}
