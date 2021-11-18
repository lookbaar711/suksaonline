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
        return view('backend.users.user-all');
    }

    public function create()
    {

    }

    public function show()
    {

    }

    public function destroy($id)
    {
      // dd($id);
        $users = Member::where('_id', '=', $id)->first();
        $users->member_status = '3';
        $users->save();
        // return redirect('backend/users/index')->with('success', 'ลบนักเรียนเรียบร้อย');
        $text = [
          'success' => "ลบผู้สอนเรียบร้อย"
        ];

        return $text;
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
        $start = intval($request->input('start'));
        $order = 'created_at';
        $dir = 'desc';
        $members = Member::where('member_type', '=', 'student')->where('member_status', '=', '1')->offset($start);

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
                if ($member->online_status == "1") {
                  $online_status = '<button class="btn btn-success" onclick="return on_line(`'.$member->_id.'`);"><i class="fa fa-toggle-on" aria-hidden="true"></i> ออนไลน์</button>';
                }else {
                  $online_status = '<button type="button" class="btn btn-secondary" ><i class="fa fa-toggle-off" aria-hidden="true"></i> ออฟไลน์</button>';
                }
                $nestedData['id'] = $i++;
                $nestedData['fullname'] = $member->member_fname.' '.$member->member_lname;
                $nestedData['email'] = $member->member_email;
                $nestedData['tel'] = $member->member_tell;
                $nestedData['created_at'] = date('d/m/Y H:i',strtotime($member->created_at));
                $nestedData['options'] = '<button class="btn btn-danger" onclick="return confirm(`'.$member->_id.'`);"><i class="fa fa-user-times" aria-hidden="true" style="margin-right: 5px"></i> ลบ</button>'.$online_status  ;
                // <form action='{$action}' method='POST'>
                //                             {$token}
                //                             <input name='_method' type='hidden' value='DELETE'>
                //                             <button type='submit' class='btn btn-danger'>
                //                             <i class='fa fa-user-times' aria-hidden='true' style='margin-right: 5px'></i>
                //                             ลบ</button>
                //                         </form>

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

    public function on_line($id)
    {
      $members = Member::where('_id', '=', $id)->first();
      $members->online_status = '0';
      $members->last_action_at = date('Y-m-d H:i:s');
      $members->save();

      Auth::guard('members')->logout();

      // return redirect('backend/members/all')
      //                 ->with('success','ลบผู้สอนเรียบร้อย');
      $text = [
        'success' => "ออฟไนย์สมาชิกเรียบร้อย"
      ];

      return $text;
    }



}
