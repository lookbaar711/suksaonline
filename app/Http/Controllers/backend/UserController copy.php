<?php
namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use View;
use App\Models\Member;
use App\Models\School;
use App\Http\Controllers\Controller;
use Mail;
class UserController extends Controller
{

    /**
     * Show a list of all the users.
     *
     * @return View
     */

    public function index(){

        $users = Member::where('member_type', '=', 'student')->where('member_status', '=', '1')->orderBy('created_at','desc')->get();

        //ข้อมูลโรงเรียน
        foreach ($users as $key_users => $value_users) {
          // print_r('<per>');
          // dd($value_users[$key_users]->member_school);
          // print_r('<per>');

          // $value_users[$key_users]->member_school == "";
          if ($value_users->member_school) {
            $school = School::select('school_name_th')->where('school_student.student_email', $value_users->member_email)->first();

            // $value_users[$key_users]->member_school = $school;
          }
        }

        //dd($members);
        return view('backend.users.user-all', compact('users'));
    }
    public function indextest(){


        return view('backend.users.user-allbackup');
    }

    public function create()
    {

    }

    public function show()
    {

    }

    public function destroy(Member $users)
    {
        $users->member_status = '3';
        $users->save();
        return redirect('backend/users/index')->with('success', 'ลบนักเรียนเรียบร้อย');
    }

    public function serverside(Request $request)
    {
        $columns = array(
            0 =>'id',
            1 =>'fullname',
            2 =>'email',
            3 =>'tel',
            4 =>'created_at',
            5 =>'options',
        );



        $totalData = Member::where('member_type', '=', 'student')->where('member_status', '=', '1')->count();
        $totalFiltered = $totalData;
        $limit = intval($request->input('length'));
        $skip = $request->input('draw');
        $start = $request->input('start');
        $order = 'created_at';
        $dir = 'desc';
        $members = Member::where('member_type', '=', 'student')->where('member_status', '=', '1');

        if(!empty($request->input('search.value')))
        {
            $search = $request->input('search.value');
            // $members = $members->where('member_email', 'LIKE','%'.$search.'%');
            $members = $members->where(function ($query) use ($search){
                $query = $query->where('member_email', 'LIKE','%'.$search.'%')
                            ->orWhere('member_fname', 'LIKE','%'.$search.'%')
                            ->orWhere('member_lname', 'LIKE','%'.$search.'%')
                            ->orWhere('member_tell', 'LIKE','%'.$search.'%')
                            ->orWhere('created_at', 'LIKE','%'.$search.'%');
            });

            $totalFiltered = Member::where('member_type', '=', 'student')->where('member_status', '=', '1')
                                    ->where(function ($query) use ($search){
                                        $query = $query->where('member_email', 'LIKE','%'.$search.'%')
                                                    ->orWhere('member_fname', 'LIKE','%'.$search.'%')
                                                    ->orWhere('member_lname', 'LIKE','%'.$search.'%')
                                                    ->orWhere('member_tell', 'LIKE','%'.$search.'%')
                                                    ->orWhere('created_at', 'LIKE','%'.$search.'%');
                                    })
                                    ->count();
        }

        $members = $members->orderBy($order,$dir)
            ->limit($limit)
            ->get();



        $data = array();

        if(!empty($members))
        {
            $i = 1;
            foreach ($members as $member)
            {
                $action =  route('users.destroy',$member->id);
                $token = csrf_field();
                // $show =  route('posts.show',$member->id);
                // $edit =  route('posts.edit',$member->id);

                $nestedData['id'] = $i++;
                $nestedData['fullname'] = $member->member_fname.' '.$member->member_lname;
                $nestedData['email'] = $member->member_email;
                $nestedData['tel'] = $member->member_tell;
                $nestedData['created_at'] = date('d-m-Y H:i',strtotime($member->created_at));
                $nestedData['options'] = "<form action='{$action}' method='POST'>
                                            {$token}
                                            <input name='_method' type='hidden' value='DELETE'>
                                            <button type='submit' class='btn btn-danger'><i class='livicon' data-name='user-ban' data-size='15' data-c='#fff' data-hc='#fff' data-loop='true'  style='width: 50px; height: 50px;'>
                                            </i>ลบ</button>
                                        </form>";

                $data[] = $nestedData;

            }
        }

        $json_data = array(
                    "draw"            => intval($request->input('draw')),
                    "recordsTotal"    => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data"            => $data
                    );

        echo json_encode($json_data);

    }



}
