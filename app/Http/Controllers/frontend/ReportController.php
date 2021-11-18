<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

use App\Models\Member;
use App\Models\Course;
use App\Models\Classroom;
use App\Models\CourseTest;
use App\Models\CourseQuestions;
use Auth;
use PDF;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        //ตรอจสอบการเข้าห้องเรียนของนักเรียน จากการทำ pretest
        $check_in_studen = Classroom::where('course_id',$id)->first();

        foreach ($check_in_studen->classroom_student as $key => $studen_class)
        {
            //ตรวจสอบว่าทำ pretest
            $check_pretest = CourseTest::where('course_id',$id)->where('member_id',$studen_class['student_id'])->count();
            if($check_pretest > 0)
            {
                $student_detail[$key] = array(
                    'student_id' => $studen_class['student_id'],
                    'student_fname' => $studen_class['student_fname'],
                    'student_lname' => $studen_class['student_lname'],
                    'student_email' => $studen_class['student_email'],
                    'student_date_regis' => $studen_class['student_date_regis'],
                    'student_tell' => $studen_class['student_tell'],
                    'student_checkin' => '1',
                    'check_student_inclass' => (!empty($studen_class['check_student_inclass'])) ? $studen_class['check_student_inclass'] : 0,
                );

                //ตรวจสอบว่าทำ posttest
                // $check_posttest = CourseTest::where('course_id',$id)->where('member_id',$studen_class['student_id'])->first();
                // if(!empty($check_in_studen->classroom_checkin) && $check_in_studen->classroom_checkin != '5')
                // {
                //     if(count($check_posttest->posttest_questions) > 0)
                //     {
                //         $student_detail[$key] = array(
                //             'student_id' => $studen_class['student_id'],
                //             'student_fname' => $studen_class['student_fname'],
                //             'student_lname' => $studen_class['student_lname'],
                //             'student_email' => $studen_class['student_email'],
                //             'student_date_regis' => $studen_class['student_date_regis'],
                //             'student_tell' => $studen_class['student_tell'],
                //             'student_checkin' => '1',
                //             'check_student_inclass' => (!empty($studen_class['check_student_inclass'])) ? $studen_class['check_student_inclass']+2 : 0,
                //         );
                //     }else{
                //         $student_detail[$key] = array(
                //             'student_id' => $studen_class['student_id'],
                //             'student_fname' => $studen_class['student_fname'],
                //             'student_lname' => $studen_class['student_lname'],
                //             'student_email' => $studen_class['student_email'],
                //             'student_date_regis' => $studen_class['student_date_regis'],
                //             'student_tell' => $studen_class['student_tell'],
                //             'student_checkin' => '1',
                //             'check_student_inclass' => (!empty($studen_class['check_student_inclass'])) ? $studen_class['check_student_inclass'] +1 : 0,
                //         );
                //     }
                // }else
                // {
                //     $student_detail[$key] = array(
                //         'student_id' => $studen_class['student_id'],
                //         'student_fname' => $studen_class['student_fname'],
                //         'student_lname' => $studen_class['student_lname'],
                //         'student_email' => $studen_class['student_email'],
                //         'student_date_regis' => $studen_class['student_date_regis'],
                //         'student_tell' => $studen_class['student_tell'],
                //         'student_checkin' => '1',
                //         'check_student_inclass' => (!empty($studen_class['check_student_inclass'])) ? $studen_class['check_student_inclass'] : 0,
                //     );
                // }


            }
            else
            {
                $student_detail[$key] = array(
                    'student_id' => $studen_class['student_id'],
                    'student_fname' => $studen_class['student_fname'],
                    'student_lname' => $studen_class['student_lname'],
                    'student_email' => $studen_class['student_email'],
                    'student_date_regis' => $studen_class['student_date_regis'],
                    'student_tell' => $studen_class['student_tell'],
                    'student_checkin' => '0',
                    'check_student_inclass' => (!empty($studen_class['check_student_inclass'])) ? $studen_class['check_student_inclass'] : 0,
                );
            }

        }
        $check_in_studen->classroom_checkin = '5';
        $check_in_studen->classroom_student = $student_detail;
        $check_in_studen->save();

        $cousres = Course::where('_id', $id)
            // ->where('member_id',Auth::guard('members')->user()->id)
            ->where('course_category','School')
            ->with(['getMember','getaAtitude','getSubject','getClassroom','getcoursequestions','getSchool'])
            ->orderBy('created_at','desc')
            ->first();
            if($cousres):
                // นับคนเข้าเรียนและขาดเรียน
                $check_studen = Classroom::where('course_id',$cousres->id)->first();
                $studen_inroom = 0;
                $studen_noinroom = 0;
                $studenname_noinroom = '';

                foreach($check_studen->classroom_student as $key => $value)
                {
                    $check_pretest = CourseTest::where('course_id',$id)->where('member_id',$value['student_id'])->count();
                    if($check_pretest > 0)
                    {
                        $check_posttest = CourseTest::where('course_id',$id)->where('member_id',$value['student_id'])->first();
                        if(count($check_posttest->posttest_questions) > 0)
                        {
                            $student_detail[$key] = array(
                                'student_id' => $value['student_id'],
                                'student_fname' => $value['student_fname'],
                                'student_lname' => $value['student_lname'],
                                'student_email' => $value['student_email'],
                                'student_date_regis' => $value['student_date_regis'],
                                'student_tell' => $value['student_tell'],
                                'student_checkin' => $value['student_checkin'],
                                'check_student_inclass' => (!empty($value['check_student_inclass'])) ? $value['check_student_inclass']+2 : 0,
                            );
                        }else{
                            $student_detail[$key] = array(
                                'student_id' => $value['student_id'],
                                'student_fname' => $value['student_fname'],
                                'student_lname' => $value['student_lname'],
                                'student_email' => $value['student_email'],
                                'student_date_regis' => $value['student_date_regis'],
                                'student_tell' => $value['student_tell'],
                                'student_checkin' => $value['student_checkin'],
                                'check_student_inclass' => (!empty($value['check_student_inclass'])) ? $value['check_student_inclass'] +1 : 0,
                            );
                        }
                    }
                    else
                    {
                        $student_detail[$key] = array(
                                    'student_id' => $value['student_id'],
                                    'student_fname' => $value['student_fname'],
                                    'student_lname' => $value['student_lname'],
                                    'student_email' => $value['student_email'],
                                    'student_date_regis' => $value['student_date_regis'],
                                    'student_tell' => $value['student_tell'],
                                    'student_checkin' => $value['student_checkin'],
                                    'check_student_inclass' => (!empty($value['check_student_inclass'])) ? $value['check_student_inclass'] : 0,
                                );
                    }


                }
                foreach($student_detail as $value)
                {
                     if(in_array($value['check_student_inclass'],[5,6]))
                    {
                        $studen_inroom = $studen_inroom+1;
                    }else
                    {
                        $studen_noinroom = $studen_noinroom+1;

                        $studenname_noinroom .= $studen_noinroom.'.'.$value['student_fname'].' ';
                    }
                }


                $cousres->studen_inroom = $studen_inroom;
                $cousres->studen_noinroom = $studen_noinroom;
                $cousres->studenname_noinroom = $studenname_noinroom;
                //logo_suksa
                $image_url = 'suksa/frontend/template/images/logo1.png';
                $cousres->image_url_logo = $image_url;

                //logo_school
                // dd($cousres->getSchool->school_image);
                if(!empty($cousres->getSchool->school_image))
                {
                    $image_school = 'storage/school/'.$cousres->getSchool->school_image;
                    $cousres->image_school = $image_school;
                }else{
                    $cousres->image_school = '';

                }

                //ดึงข้อมูลคนที่ทำแบบทดสอบ

                $studen_test = $this->makeTest($cousres->getCourseTest);

                $sum_pretest = 0;
                $sum_posttest = 0;
                $pretest = array();
                $posttest = array();
                foreach ($studen_test as $value)
                {
                    // dd($value);
                    $pretest[] = $value['pretest'];
                    $posttest[] = $value['posttest'];
                    $sum_pretest = $sum_pretest+$value['pretest'];
                    $sum_posttest = $sum_posttest+$value['posttest'];
                }
                // dd($sum_pretest);
                // dd(count($pretest));
                //

                if(count($pretest) > 0){



                    // หาค่า S.D
                    $sd_posttest = $this->stats_standard_deviation($posttest);
                    $sd_pretest = $this->stats_standard_deviation($pretest);

                    //หาค่า average
                    $average_pretest = $sum_pretest/count($pretest);
                    $average_posttest = $sum_posttest/count($posttest);
                    //หา min-max
                    $cousres->min_pretest = min($pretest);
                    $cousres->max_pretest = max($pretest);
                    $cousres->min_posttest = min($posttest);
                    $cousres->max_posttest = max($posttest);


                    //ทำกราฟ
                    // $chartsTest = $this->chartsTest($studen_test,count($cousres->getcoursequestions->questions));
                    // dd($studen_test);
                    $chartsTest = $this->chartsTests($studen_test,count($cousres->getcoursequestions->questions));
                    $cousres->charts_categories = json_encode($chartsTest['categories']);
                    $cousres->charts_data_pretest = json_encode($chartsTest['data']['pretest']);
                    $cousres->charts_data_posttest = json_encode($chartsTest['data']['posttest']);

                    // dd(json_encode($chartsTest));
                    //คำนวณความยากง่ายของข้อสอบ
                    $difficulty = $this->difficulty($cousres->id);
                    $cousres->difficultys = $difficulty;

                    $cousres->average_pretest = round($average_pretest,2);
                    $cousres->average_posttest = round($average_posttest,2);
                    $cousres->sd_pretest = round($sd_pretest,2);
                    $cousres->sd_posttest = round($sd_posttest,2);

                    $cousres->studen_test = $studen_test;

                    if($sum_posttest > 0)
                    {
                        $growth = $sum_pretest-$sum_posttest;
                        // dd($sum_pretest,$sum_posttest);
                        $growth_rate = number_format($growth/$sum_posttest,2);
                    }else{
                        $growth_rate = 0;
                    }
                    // dd($growth_rate);
                    $cousres->growth_rate = $growth_rate;
                    $collection = collect($cousres->course_student);
                    $check_classroom = $collection->where('student_classroom','!=', null);
                    if(count($check_classroom) > 0){
                        $classroom = $collection->mapWithKeys(function ($item, $key) {
                            return [$item['student_classroom'] => $item['student_classroom']];
                        });
                    }else{
                        $classroom = [];
                    }

                    $cousres->classroom = $classroom;
                    $data = array(
                        'cousres' => $cousres,
                    );

                    return view('frontend.courses.reportteacher',$data);
                }else{
                    if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){

                        return redirect()->to('/courses/'.$id)->with('classroom_closed','Unable to issue teaching reports Since this course does not have students entering the classroom.');
                    }else{

                        return redirect()->to('/courses/'.$id)->with('classroom_closed','ไม่สามารถออกรายงานการสอนได้ เนื่องจากคอร์สเรียนนี้ไม่มีนักเรียนเข้าห้องเรียน');
                    }
                }


            else:
            endif;

    }

    function  makeTest($datas)
    {
        $studen_test = [];
        foreach($datas as $key => $data)
        {
            $member = Member::where('member_id',$data->member_id)->where('member_status', '1')->first();
            $studen_test[$key]['fullname'] = @$member->member_fname.' '.@$member->member_lname;
            $studen_test[$key]['pretest'] = $data->pretest_score;
            $studen_test[$key]['posttest'] = $data->posttest_score;
            $studen_test[$key]['makeposttest'] = ($data->posttest_score ? $data->posttest_score : '-');
        }
        return $studen_test;
    }



     function stats_standard_deviation(array $a, $sample = false) {
        $n = count($a);
        if ($n === 0) {
            trigger_error("The array has zero elements", E_USER_WARNING);
            return false;
        }
        if ($sample && $n === 1) {
            trigger_error("The array has only 1 element", E_USER_WARNING);
            return false;
        }
        $mean = array_sum($a) / $n;
        $carry = 0.0;
        foreach ($a as $val) {
            $d = ((double) $val) - $mean;
            $carry += $d * $d;
        };
        if ($sample) {
           --$n;
        }
        $s = $n-1;
        if($s > 0)
        {
            return sqrt($carry / $s);
        }else{
            return 0.0;
        }
    }

    function chartsTest($datas,$questions)
    {
        $charts = [['pretest-posttest', 'pretest', 'posttest']];
        foreach($datas as $key => $data)
        {
            $charts[$key+1][] = $data['fullname'];
            $charts[$key+1][] = $data['pretest'];
            $charts[$key+1][] = $data['posttest'];
        }
        return $charts;
    }


    function chartsTests($datas,$questions)
    {
        if(count($datas) > 0)
        {


        $i = 0;
        $pretest = [];
        $posttest = [];
        foreach ($datas as $element) {
            $pretest[$element['pretest']][] = $i+1;
            if($element['makeposttest'] != '-'){
                $posttest[$element['posttest']][] = $i+1;
            }
        }

        //เก็บค่าคะแนนในแกน x 10 คะแนน
        // $datacharts = [['pretest-posttest', 'pretest', 'posttest']];
        // array_multisort($pretest);
        for ($i=0; $i <= $questions; $i++) {
            $charts[$i]['score'] = $i;
            $charts[$i]['pretest'] =0;
            $charts[$i]['posttest'] = 0;
        }
        foreach($pretest as $key => $val)
        {
            $charts[$key]['pretest'] = count($val);
        }

        if(count($posttest) > 0){
            foreach($posttest as $key => $val)
            {
                $charts[$key]['posttest'] = count($val);
            }
        }else{

        }

        // dd($datas);
        $datacharts['data']['pretest']['name'] = 'pretest';
        $datacharts['data']['posttest']['name'] = 'posttest';

        foreach($charts as $key => $val)
        {
            $datacharts['categories'][] = $val['score'];
            $datacharts['data']['pretest']['data'][] = $val['pretest'];
            $datacharts['data']['posttest']['data'][] = $val['posttest'];
        }
        $datacharts['data']['pretest']['color'] = 'rgb(219, 68, 55)';
        $datacharts['data']['posttest']['color'] = 'rgb(66, 133, 244)';
        $datacharts['data']['pretest']['enableMouseTracking'] = false;
        $datacharts['data']['posttest']['enableMouseTracking'] = false;
        }else{
            $datacharts['categories'][] = [];
            $datacharts['data']['pretest']['name'] = 'pretest';
            $datacharts['data']['posttest']['name'] = 'posttest';

            $datacharts['data']['pretest']['data'][] = 0;
            $datacharts['data']['posttest']['data'][] = 0;

            $datacharts['data']['pretest']['color'] = 'rgb(219, 68, 55)';
            $datacharts['data']['posttest']['color'] = 'rgb(66, 133, 244)';
            $datacharts['data']['pretest']['enableMouseTracking'] = false;
            $datacharts['data']['posttest']['enableMouseTracking'] = false;
        }
        // dd(json_encode($datacharts['data']['pretest']));
        return $datacharts;
    }

    function difficulty($id)
    {
        $answer_questions = CourseQuestions::where('course_id',$id)->first();

        //answer เก็บค่าคำตอบที่ถูก
        $answer = [];
        //เก็บจำนวนคนที่ตอบถูกในแต่ละข้อ
        $questions_no = [];
        // dd($answer_questions->questions);
        foreach($answer_questions->questions as $key =>  $itme)
        {
            $answer[$key]['question_no'] = $itme['question_no'];
            $answer[$key]['answer'] = $itme['answer'];

            $question['question_no_'.$itme['question_no']] = $itme['question'];
            $questions_no['question_no_'.$itme['question_no']] = 0;
        }
        $CourseTests = CourseTest::where('course_id',$id)->get();

        foreach($CourseTests as $key => $CourseTest)
        {
            foreach($CourseTest->posttest_questions as $key2 => $posttest)
            {
                // dd($posttest);
                if($answer[$key2]['question_no'] == $posttest['question_no'] && $answer[$key2]['answer'] == $posttest['select_answer'])
                {
                    $num = $key2+1;
                    $questions_no['question_no_'.$num] = $questions_no['question_no_'.$num]+1;
                }
            }
        }

        $difficulty = [];
        // เก็บค่าที่คำนวนพร้อมกับคำถาม
        if(count($CourseTests) > 0){
            foreach($questions_no as $key => $question_no)
            {
                $P = round($question_no/count($CourseTests),2);

                if($P >= 0.80)
                {
                    $difficulty[$key]['P'] = 'ง่ายมาก(ควรปรับปรงุหรือตัดทิ้ง)';
                }else if($P >= 0.60)
                {
                    $difficulty[$key]['P'] = 'ค่อนข้างง่าย';
                }else if($P >= 0.40)
                {
                    $difficulty[$key]['P'] = 'ปานกลาง';
                }else if($P >= 0.20)
                {
                    $difficulty[$key]['P'] = 'ค่อนข้างยาก';
                }else{
                    $difficulty[$key]['P'] = 'ยากมาก(ควรปรับปรุงหรือตัดทิ้ง)';
                }
                // $difficulty[$key]['P_num'] = round($question_no/count($CourseTests),2);
                $difficulty[$key]['question'] = $question[$key];

            }
        }

       return $difficulty;
    }





}
