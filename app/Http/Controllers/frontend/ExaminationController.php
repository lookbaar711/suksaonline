<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

//model//
use App\Models\CourseQuestions;
use App\Models\CourseTest;
use App\Models\Classroom;
use App\Models\MemberNotification;


class ExaminationController extends Controller
{
    public function index(Request $request)
    {
        $datas = [];
        return view('frontend.examination.start',compact('datas'));
    }

    public function pretest($course_id,CourseQuestions $coursequestions)
    {
        $datas = $coursequestions->where('course_id',$course_id)->first();
        if($datas){
            return view('frontend.examination.pretest',compact('datas'));
        }else{
            return redirect()->back()->with('classroom_closed', 'ไม่พบห้องเรียน');
        }

    }


    public function postpretest(Request $request,CourseQuestions $coursequestions,CourseTest $coursetest,MemberNotification $membernotification)
    {
        $datas = $request->all();
        $answer_questions = $coursequestions->where('course_id',$datas['course_id'])->first();
        //answer เก็บค่าคำตอบ
        $answer = [];
        // จัด answer หาให้ใช้ function array_intersect
        foreach($answer_questions->questions as $key => $itme)
        {
            $answer[$itme['question_no']] = $itme['answer'];
        }

        // คำตอบที่เลือก
        $select_answer = $datas['question_no'];

        foreach($datas['question_no'] as $key => $answer_select)
        {
            $pretest_questions[] = ([
                'question_no' => strval($key),
                'select_answer' => $answer_select,
            ]);
        }
        $check_sum = $coursetest->where('course_id',$datas['course_id'])->where('member_id',Auth::guard('members')->user()->_id)->first();
        // dd($check_sum);
        if($check_sum){
            if(count($check_sum->pretest_questions) > 1){
                $today = date('Y-m-d');
                $noti_id = $membernotification->where('course_id',$check_sum->course_id)->where('member_id',Auth::guard('members')->user()->_id)->where('noti_type','open_course_student')->where('classroom_date', '=', $today)->orderby('classroom_time_start','desc')->first();

                $urlhost = 'http://'.$_SERVER['HTTP_HOST'].'/classroom/check/'.$noti_id->id;
                $check_answer = $check_sum->pretest_score;

                return redirect('courses/'.$datas['course_id'])->with('alertpretest', [strval($check_answer),$urlhost]);
            }
        }

        // if()
        // $check_answer = array_intersect($answer,$select_answer);
        $check_answer = array_intersect_assoc($answer,$select_answer);

        $data['course_id'] = $datas['course_id'];
        $data['member_id'] = Auth::guard('members')->user()->_id;
        $data['pretest_questions'] = $pretest_questions;
        $data['pretest_score'] = count($check_answer);
        $data['posttest_questions'] = ([]);
        $data['posttest_score'] = 0;
        $coursetest->create($data);

        $today = date('Y-m-d');
        $noti_id = $membernotification->where('course_id',$datas['course_id'])->where('member_id',Auth::guard('members')->user()->_id)->where('noti_type','open_course_student')->where('classroom_date', '=', $today)->orderby('classroom_time_start','desc')->first();

        $urlhost = 'http://'.$_SERVER['HTTP_HOST'].'/classroom/check/'.$noti_id->id;

        return redirect('courses/'.$datas['course_id'])->with('alertpretest', [strval(count($check_answer)),$urlhost]);

    }



    public function checktimeclass(Request $request,Classroom $classroom)
    {
        $course_id = $request->get('course_id');
        $check = $classroom->select('classroom_name','classroom_date','classroom_time_start','classroom_time_end')->where('course_id',$course_id)->first();
        //เช็คว่ามี course_id จริงไหม
        if($check):
            //ตรวจสอบวันที่
            if($check->classroom_date != date('Y-m-d')):
                $success = false;
                $html = 'ห้องเรียนถูกปิดแล้ว';
                return response()->json(['html'=>$html,'status'=>$success], 200);
            else:
                //ถ้าตรวจสอบวันที่ผ่าน แล้ว มาตรวจสอบ เวลา
                if($check->classroom_time_end > date('H:i:s')):
                    $success = false;
                    $html = 'ห้องเรียนถูกปิดแล้ว';
                    return response()->json(['html'=>$html,'status'=>$success], 200);
                else:
                    $success = true;
                    return response()->json(['status'=>$success], 200);
                endif;
            endif;
        else:
            $success = false;
            $html = 'ไม่พบห้องเรียนนี้';
            return response()->json(['html'=>$html,'status'=>$success], 200);
        endif;

    }

    public function posttest($course_id,CourseQuestions $coursequestions,CourseTest $coursetest)
    {
        $datas = $coursequestions->where('course_id',$course_id)->first();
        if($datas){
            $check = $coursetest->where('course_id',$course_id)->where('member_id',Auth::guard('members')->user()->_id)->first();
            if($check){
                if(count($check->posttest_questions) > 0)
                {
                    if(Auth::guard('members')->user()->member_lang != 'en'):
                        $messing = 'คุณได้ทำแบบทดสอบหลังเรียนไปแล้ว';
                    else:
                        $messing = 'You have done the test after studying';
                    endif;
                    return redirect()->back()->with('classroom_closed', $messing);
                }else{
                    return view('frontend.examination.posttest',compact('datas'));
                }
            }else{
                if(Auth::guard('members')->user()->member_lang != 'en'):
                    $messing = 'คุณไม่ได้ทำแบบทดสอบก่อนเรียน';
                else:
                    $messing = "You didn't take the test before studying";
                endif;
                return redirect()->back()->with('classroom_closed', $messing);
            }

        }else{
            if(Auth::guard('members')->user()->member_lang != 'en'):
                $messing = 'ไม่พบห้องเรียน';
            else:
                $messing = 'no class room';
            endif;
            return redirect()->back()->with('classroom_closed', 'ไม่พบห้องเรียน');
        }

    }

    public function postposttest(Request $request,CourseQuestions $coursequestions,CourseTest $coursetest,MemberNotification $membernotification)
    {
        $datas = $request->all();
        $answer_questionss = $coursequestions->where('course_id',$datas['course_id'])->first();
        //answer เก็บค่าคำตอบ
        $answer = [];
        // จัด answer หาให้ใช้ function array_intersect
        foreach($answer_questionss->questions as $key => $itme)
        {
            $answer[$itme['question_no']] = $itme['answer'];
        }

        // คำตอบที่เลือก
        $select_answer = $datas['question_no'];


        foreach($datas['question_no'] as $key => $answer_select)
        {
            $posttest_questions[] = ([
                'question_no' => strval($key),
                'select_answer' => $answer_select,
            ]);
        }

        // $check_answer = array_intersect($answer,$select_answer);
        $check_answer = array_intersect_assoc($answer,$select_answer);

        $data['course_id'] = $datas['course_id'];
        $data['member_id'] = Auth::guard('members')->user()->_id;
        $data['posttest_questions'] = $posttest_questions;
        $data['posttest_score'] = count($check_answer);
        $coursetest->where('course_id',$datas['course_id'])->where('member_id',Auth::guard('members')->user()->_id)->update($data);

        return redirect('courses/'.$datas['course_id'])->with('alertposttest', strval(count($check_answer)));
    }

    // public function checkurlclass(Request $request,MemberNotification $membernotification)
    // {
    //     $course_id = $request->get('course_id');
    //     $today = date('Y-m-d');
    //     $url = $membernotification->where('course_id',$course_id)->where('member_id',Auth::guard('members')->user()->_id)->where('noti_type','open_course_student')->where('classroom_date', '=', $today)->orderby('classroom_time_start','desc')->first();
    //     $success = true;
    //     $html = url('classroom/check/'.$url->id);
    //     return response()->json(['html'=>$html,'status'=>$success], 200);
    // }
}
