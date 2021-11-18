<?php
namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use View;
use App\Models\School;
use App\Models\Member;
use App\Http\Controllers\Controller;
use Mail;
use Input;
use Excel;

class SchoolController extends Controller
{

    /**
     * Show a list of all the users.
     *
     * @return View
     */

    public function index(){
        $schools = School::where('school_status','1')->orderBy('created_at','desc')->get();
        // dd($school);
        return view('backend.school.school-all', compact('schools'));
    }

    public function create(Request $request)
    {
        return view('backend.school.school-create');
    }


    public function store(Request $request)
    {
        $check_school = School::where('school_status','1')->where('school_name_th','like','%'.$request->school_name_th.'%')->count();
        if($check_school > 0)
        {
            return redirect()->back()->with('error','ชื่อโรงเรียนนี้มีอยู่แล้ว');
        }

        $rules = [

            'school_logo' => 'required|mimes:jpeg,jpg,png '
        ];
        $messages = [

          'school_logo.required' => 'กรุณาอัพโหลดไฟล์',
          'school_logo.mimes'=> 'ไฟล์จะต้องเป็น jpeg,jpg,png เท่านั้น',
        ];

        $validator = Validator::make($request->all(),$rules,$messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $fileUpload = $request->school_logo;

        $fielName =  Str::random(7).time().".".$fileUpload->getClientOriginalExtension();
        $image_path = Storage::disk('public')->path('school/');
        if (!file_exists($image_path)) {
            mkdir($image_path, 0777, true);
        }

        $url = public_path("/storage/school/".$fielName);
        $file = compress_images($request->school_logo, $url, 80);

        $school_create['school_name_th'] = $request->school_name_th;
        $school_create['school_name_en'] = $request->school_name_en;
        $school_create['school_email'] = $request->school_email;
        $school_create['school_tell'] = $request->school_tell;
        $school_create['school_teacher'] = [];
        $school_create['school_student'] = [];
        $school_create['school_status'] = '1';
        $school_create['school_image'] = $fielName;

        School::create($school_create);
            return redirect()->to('backend/school')
            ->with('success','เพิ่มข้อมูลโรงเรียนเรียบร้อย.');

    }

    public function edit($id)
    {
        $edit = School::where('_id',$id)->first();
        // dd($edit);
        $image = "/storage/school/".$edit->school_image;
        return view('backend.school.school-edit', compact('edit','image'));
    }

    public function updateschool(Request $request)
    {
        $check_school = School::where('_id','!=',$request->id)->where('school_status','1')->where('school_name_th','like','%'.$request->school_name_th.'%')->count();

        if($check_school > 0)
        {
            return redirect()->back()->with('error','ชื่อโรงเรียนนี้มีอยู่แล้ว');
        }

        $rules = [

            'school_logo' => 'mimes:png,jpeg,jpg'
        ];
        $messages = [
            'school_logo.mimes'=> 'ไฟล์จะต้องเป็น jpeg,jpg,png เท่านั้น',
        ];

        $validator = Validator::make($request->all(),$rules,$messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }



        $fileUpload = $request->school_logo;
        if($fileUpload)
        {
            $school = School::where('_id',$request->id)->first();
            if($school && $school->school_image != '')
            {
                    Storage::disk('public')->delete('school/'.$school->school_image);
            }

            $fielName =  Str::random(7).time().".".$fileUpload->getClientOriginalExtension();
            $image_path = Storage::disk('public')->path('school/');
            if (!file_exists($image_path)) {
                mkdir($image_path, 0777, true);
            }

            $url = public_path("/storage/school/".$fielName);
            $file = compress_images($request->school_logo, $url, 80);
            $school_update['school_image'] = $fielName;
         }

        $school_update['school_name_th'] = $request->school_name_th;
        $school_update['school_name_en'] = $request->school_name_en;
        $school_update['school_email'] = $request->school_email;
        $school_update['school_tell'] = $request->school_tell;
        // $school_update['school_teacher'] = [];
        // $school_update['school_student'] = [];


        School::where('_id',$request->id)->update($school_update);
        return redirect()->to('backend/school')
            ->with('success','อัพเดทข้อมูลโรงเรียนเรียบร้อย.');

    }

    public function destroyschool($id)
    {
        $school = School::where('_id', '=', $id)->first();
        $school->school_status = '0';
        $school->save();

        // $sh = School::where('_id', '=', $id)->first();
        //
        // // dd($sh);
        // foreach ($sh->school_teacher as $teacher_key => $teacher_value) {
        //   // dd($teacher_value['teacher_email']);
        //   $member = Member::where('member_email', '=', $teacher_value['teacher_email'])->first();
        //   $member->member_school = '';
        //   $member->save();
        // }
        //
        // foreach ($sh->school_student as $student_key => $student_value) {
        //   $member = Member::where('member_email', '=', $student_value['teacher_email'])->first();
        //   $member->member_school = '';
        //   $member->save();
        // }

        $member = Member::where('member_school', '=', $id)->first();
        $member->member_school = '';
        $member->save();

        return redirect('backend/school')
                        ->with('success','ลบข้อมูลโรงเรียนเรียบร้อย');
    }

    public function student($id, Request $request)
    {
        // $type = request('type'); // or
        // $type = $request->type; // or
        // $type = $request->input('type'); // or
        // $getQueryString=url()->full();


        $school =  School::select('school_student','school_name_th')->where('_id', '=', $id)->first();
        $collection = collect($school->school_student);
        $datas = $collection->sortBy('student_classroom')->groupBy('student_classroom');
        $i = 1;
        $students = [];
        foreach ($datas as $key => $value) {
           $students[$key]['num'] = $i++;
           $students[$key]['data'] = $value;
        }
        $school_id = $id;

        $getQueryString=\Request::getRequestUri();
        $page = parse_url($getQueryString);
        // dd($page);
        if (empty($page['query'])) {
          $page = null;
        }else {
          $page = str_replace('page=','',$page['query']);
        }


        $students = $this->paginate($students,intval($page));
        return view('backend.school.school_student',compact('school','students','school_id'));

    }

    public function paginate($items, $page = 1 ,$perPage = 5, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count() , $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
        // return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function createstudent(Request $request)
    {
        $schools =  School::select('school_student')->where('_id', '=', $request->school_id)->count();
        if($schools > 0)
        {
            $schools =  School::select('school_student')->where('_id', '=', $request->school_id)->first();

            foreach ($schools['school_student'] as $key => $school) {
                $data_student_detail[] = ([
                    'student_class' => @$school['student_class'],
                    'student_classroom' => @$school['student_classroom'],
                    'student_room' => intval(@$school['student_room']),
                    'student_email' => trim(@$school['student_email']),
                    'student_fname' => @$school['student_fname'],
                    'student_lname' => @$school['student_lname'],
                    'student_tel' => @$school['student_tel'],
                    'student_id' => @$school['student_id'],
                ]);
            }

            $member = Member::where('member_email', trim($request->student_email))->where('member_type','student')->where('member_status','1')->first();
            // $member = Member::where('member_email', trim($request->student_email))->first();
            $data_student_detail[] = ([
                'student_class' => $request->student_class,
                'student_classroom' => $request->student_class.'/'.intval($request->student_room),
                'student_room' => intval($request->student_room),
                'student_email' => $request->student_email,
                'student_fname' => $request->student_fname,
                'student_lname' => $request->student_lname,
                'student_tel' => $request->student_tel,
                'student_id' => @$member->_id,
            ]);
            $update_student['school_student'] = $data_student_detail;
            School::where('_id', $request->school_id)->update($update_student);
            return redirect()->back()->with('success','เพิ่มข้อมูลนักเรียนสำเร็จ');
        }else{
            return redirect()->back()->with('error','ไม่พบข้อมูลโรงเรียน');
        }

    }

    public function updatestudent(Request $request)
    {
        // dd($request->all());
        $schools =  School::select('school_student')->where('_id', '=', $request->school_id)->first();
        foreach ($schools['school_student'] as $key => $school) {
            if($request->student_old_email == $school['student_email'])
            {
                $member = Member::where('member_email', trim($request->student_email))->where('member_type','student')->where('member_status','1')->first();
                // $member = Member::where('member_email', trim($request->student_email))->first();
                $data_student_detail[] = ([
                    'student_class' => $request->student_class,
                    'student_classroom' => $request->student_class.'/'.intval($request->student_room),
                    'student_room' => intval($request->student_room),
                    'student_email' => trim($request->student_email),
                    'student_fname' => $request->student_fname,
                    'student_lname' => $request->student_lname,
                    'student_tel' => $request->student_tel,
                    'student_id' => @$member->_id,
                ]);
            }else{
                $data_student_detail[] = ([
                    'student_class' => @$school['student_class'],
                    'student_classroom' => @$school['student_classroom'],
                    'student_room' => intval(@$school['student_room']),
                    'student_email' => trim(@$school['student_email']),
                    'student_fname' => @$school['student_fname'],
                    'student_lname' => @$school['student_lname'],
                    'student_tel' => @$school['student_tel'],
                    'student_id' => @$school['student_id'],
                ]);
            }
        }
        $update_student['school_student'] = $data_student_detail;
        School::where('_id', $request->school_id)->update($update_student);
        return redirect()->back()->with('success','อัพเดทข้อมูลนักเรียนสำเร็จ');

    }

    public function deletestudent(Request $request)
    {
        // dd($request->all());
        $schools =  School::select('school_student')->where('_id', '=', $request->school_id)->first();
        foreach ($schools['school_student'] as $key => $school) {
            if(trim($request->email) != trim($school['student_email']))
            {
                $data_student_detail[] = ([
                    'student_class' => @$school['student_class'],
                    'student_classroom' => @$school['student_classroom'],
                    'student_room' => @$school['student_room'],
                    'student_email' => trim(@$school['student_email']),
                    'student_fname' => @$school['student_fname'],
                    'student_lname' => @$school['student_lname'],
                    'student_tel' => @$school['student_tel'],
                    'student_id' => @$school['student_id'],
                ]);
            }
        }
        $update_student['school_student'] = $data_student_detail;
        School::where('_id', $request->school_id)->update($update_student);
        return redirect()->back()->with('success','ลบข้อมูลนักเรียนสำเร็จ');

    }

    public function importstudent(Request $request)
    {
        $schools = School::where('school_status','1')->orderBy('created_at')->get();
        return view('backend.school.import_student',compact('schools'));
    }

    public function importstorestudent(Request $request)
    {
        $school =  School::select('school_student')->where('_id', '=', $request->school_id)->count();
        if($school > 0)
        {
            $rules = [

                'file_student' => 'required|mimes:xlsx,xls '
            ];
            $messages = [

              'file_student.required' => 'กรุณาอัพโหลดไฟล์',
              'file_student.mimes'=> 'ไฟล์จะต้องเป็น xlsx,xls เท่านั้น',
            ];

            $validator = Validator::make($request->all(),$rules,$messages);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $fileUpload = $request->file_student;
            if($request->hasFile('file_student'))
            {
                $fielName =  Str::random(7).time().'.'.$fileUpload->getClientOriginalExtension();

                $destinationPath = public_path().'/storage/import/';
                $destinationPath2 = public_path().'/storage/import/'.$fielName;
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $fileUpload->move($destinationPath,$fielName);

               $results = Excel::load($destinationPath2, function($reader) {})->get();

               $school =  School::select('school_student')->where('_id', '=', $request->school_id)->first();
               $data_student_detail = $school['school_student'];
               $heading = ['name','lastname','class','room','tel','email'];

               if(array_filter($results->getHeading()) == $heading){

                    foreach($results->all() as $school)
                    {
                        if($school['name'] != ''&& $school['lastname'] != ''){
                            $room = ($school['room'] == '' ? 1 : $school['room']);
                            $member = Member::where('member_email', trim(@$school['email']))->where('member_type','student')->where('member_status','1')->first();
                            // $member = Member::where('member_email', trim($school['email']))->first();
                            $data_student_detail[] = ([
                                'student_class' => @$school['class'],
                                'student_room' => @$room,
                                'student_classroom' => @$school['class'].'/'.@$room,
                                'student_email' => trim(@$school['email']),
                                'student_fname' => @$school['name'],
                                'student_lname' => @$school['lastname'],
                                'student_tel' => @$school['tel'],
                                'student_tel' => @$member->_id,
                                'student_id' => @$school['student_id'],
                            ]);
                        }

                    }
                    $update_student['school_student'] = $data_student_detail;
                    School::where('_id', $request->school_id)->update($update_student);
                    unlink($destinationPath2);
                    return redirect()->to('backend/school/student/'.$request->school_id)->with('success','นำเข้าข้อมูลนักเรียนสำเร็จ.');
               }else{
                    unlink($destinationPath2);
                    return redirect()->back()->with('error','เเบบฟอร์มไพล์ไม่ถูกต้องกรุณาตรวจสอบไพล์')->withInput();
               }

            }
        }else{
            return redirect()->back()->with('error','ไม่พบข้อมูลโรงเรียน');
        }

    }

    public function donwloadimportstudent()
    {
        $pathToFile = public_path().'/suksa/backend/import/importstudent.xlsx';
        return response()->download($pathToFile);

    }

    public function teacher($id)
    {
        if($id)
        {
            $teachers =  School::select('school_teacher','school_name_th')->where('_id', '=', $id)->first();
            if($teachers){
                $school_id = $id;
                // dd(count($teachers));
                return view('backend.school.school_teacher',compact('teachers','school_id'));
             }
        }
    }

    public function createteacher(Request $request)
    {
        // dd($request->all());
        $schools =  School::select('school_teacher')->where('_id', '=', $request->school_id)->first();

        if(count($schools['school_teacher']) > 0)
        {
            foreach ($schools['school_teacher'] as $key => $teacher) {
                    $data_student_detail[] = ([
                        'teacher_fname' =>  @$teacher['teacher_fname'],
                        'teacher_lname' => @$teacher['teacher_lname'],
                        'teacher_email' => trim(@$teacher['teacher_email']),
                        'teacher_tel' => @$teacher['teacher_tel'],
                    ]);
            }
        }

        $data_student_detail[] = ([
            'teacher_fname' => $request->teacher_fname,
            'teacher_lname' => $request->teacher_lname,
            'teacher_email' => trim($request->teacher_email),
            'teacher_tel' => $request->teacher_tel,
        ]);
        $update_student['school_teacher'] = $data_student_detail;
        School::where('_id', $request->school_id)->update($update_student);
        return redirect()->back()->with('success','เพิ่มข้อมูลครูสำเร็จ');
    }

    public function updateteacher(Request $request)
    {
        $schools =  School::select('school_teacher')->where('_id', '=', $request->school_id)->first();

        foreach ($schools['school_teacher'] as $key => $teacher) {
            if($request->teacher_old_email == $teacher['teacher_email'])
            {
                $data_teacher_detail[] = ([
                    'teacher_email' => trim($request->teacher_email),
                    'teacher_fname' => $request->teacher_fname,
                    'teacher_lname' => $request->teacher_lname,
                    'teacher_tel' => $request->teacher_tel,
                ]);
            }else{
                $data_teacher_detail[] = ([
                    'teacher_fname' => @$teacher['teacher_fname'],
                    'teacher_lname' => @$teacher['teacher_lname'],
                    'teacher_email' => trim(@$teacher['teacher_email']),
                    'teacher_tel' => @$teacher['teacher_tel'],
                ]);
            }
        }
        if(count($data_teacher_detail) == 0)
        {
            $data_teacher_detail = [];
        }
        $update_student['school_teacher'] = $data_teacher_detail;
        School::where('_id', $request->school_id)->update($update_student);
        return redirect()->back()->with('success','แก้ไขข้อมูลครูสำเร็จ');
    }

    public function deleteteacher(Request $request)
    {
        $schools =  School::select('school_teacher')->where('_id', '=', $request->school_id)->first();
        foreach ($schools['school_teacher'] as $key => $teacher) {
            if(trim($request->email) != trim($teacher['teacher_email']))
            {
                $data_teacher_detail[] = ([
                    'teacher_fname' => @$teacher['teacher_fname'],
                    'teacher_lname' => @$teacher['teacher_lname'],
                    'teacher_email' => trim(@$teacher['teacher_email']),
                    'teacher_tel' => @$teacher['teacher_tel'],
                ]);
            }
        }
        $update_student['school_teacher'] = $data_teacher_detail;
        School::where('_id', $request->school_id)->update($update_student);
        return redirect()->back()->with('success','ลบข้อมูลครูสำเร็จ');
        // $message = 'ลบข้อมูลสำเร็จ';
        // $status = 'success';
        // $data = [];
        // return response()->json(['message'=>$message,'data'=>$data,'status'=>$status], 200);
    }


    public function importteacher(Request $request)
    {
        $schools = School::where('school_status','1')->orderBy('created_at')->get();
        return view('backend.school.import_teacher',compact('schools'));
    }

    public function importstoreteacher(Request $request)
    {
        $school =  School::select('school_teacher')->where('_id', '=', $request->school_id)->count();
        if($school > 0)
        {
            $rules = [

                'file_teacher' => 'required|mimes:xlsx,xls '
            ];
            $messages = [

              'file_teacher.required' => 'กรุณาอัพโหลดไฟล์',
              'file_teacher.mimes'=> 'ไฟล์จะต้องเป็น xlsx,xls เท่านั้น',
            ];

            $validator = Validator::make($request->all(),$rules,$messages);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $fileUpload = $request->file_teacher;
            // dd($fileUpload->getClientOriginalExtension());
            if($request->hasFile('file_teacher'))
            {
                $fielName =  Str::random(7).time().'.'.$fileUpload->getClientOriginalExtension();

                $destinationPath = public_path().'/storage/import/';
                $destinationPath2 = public_path().'/storage/import/'.$fielName;
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $fileUpload->move($destinationPath,$fielName);

               $results = Excel::load($destinationPath2, function($reader) {})->get();
               $teachers =  School::select('school_teacher')->where('_id', '=', $request->school_id)->first();
               $data_teacher_detail = $teachers['school_teacher'];
               $heading = ['name','lastname','tel','email'];
               if(array_filter($results->getHeading()) == $heading){
                    foreach($results as $key => $teacher)
                    {
                        if($teacher['name'] != '' && $teacher['lastname'] != '')
                        {

                          $member = Member::where('member_email', trim(@$teacher['email']))->first();
                          $member->member_school = $request->school_id;
                          $member->save();

                            $data_teacher_detail[] = ([
                                'teacher_fname' => @$teacher['name'],
                                'teacher_lname' => @$teacher['lastname'],
                                'teacher_email' => trim(@$teacher['email']),
                                'teacher_tel' => @$teacher['tel'],
                            ]);
                        }

                    }

                $update_student['school_teacher'] = $data_teacher_detail;
                School::where('_id', $request->school_id)->update($update_student);
                    unlink($destinationPath2);
                return redirect()->to('backend/school/teacher/'.$request->school_id)->with('success','นำเข้าข้อมูลครูสำเร็จ.');
               }else{
                unlink($destinationPath2);
                return redirect()->back()->with('error','เเบบฟอร์มไพล์ไม่ถูกต้องกรุณาตรวจสอบไพล์')->withInput();
               }


            }
        }else{
            return redirect()->back()->with('error','ไม่พบข้อมูลโรงเรียน');
        }

    }



    public function donwloadimportteacher()
    {
        $pathToFile = public_path().'/suksa/backend/import/importteacher.xlsx';
        return response()->download($pathToFile);
    }

    public function destroy(Member $users)
    {
        $users->member_status = '3';
        $users->save();
        return redirect('backend/users/index')->with('success', 'ลบนักเรียนเรียบร้อย');
    }



    public function updatedata($id)
    {
        $schools = School::where('_id',$id)->first();
        // dd($schools);
        foreach($schools->school_student as $school_student)
        {

                $member = Member::where('member_email', trim(@$school_student['student_email']))->where('member_type','student')->where('member_status','1')->first();
               $arrayData[] = ([
                'student_class' => $school_student['student_class'],
                'student_room' => $school_student['student_room'],
                'student_classroom' => $school_student['student_classroom'],
                'student_email' => $school_student['student_email'],
                'student_fname' => $school_student['student_fname'],
                'student_lname' =>  $school_student['student_lname'],
                'student_tel' => $school_student['student_tel'],
                'student_id' => @$member->_id,
               ]);


        }
        $schools->school_student = $arrayData;
        $schools->save();
    }

}
