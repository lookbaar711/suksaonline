<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Subject;
use App\Models\Aptitude;
use App\Models\School;
use App\Models\MemberNotification;
use Auth;

class MainController extends Controller {


	/**
	 * Message bag.
	 *
	 * @var Illuminate\Support\MessageBag
	 */
	protected $messageBag = null;

    /**
     * Initializer.
     *
     */
	public function __construct()
	{
		$this->messageBag = new MessageBag;

	}

    public function getFrontend()
    {
			$members = Member::where('member_status', '1')->whereNotNull('member_Bday')->take(3)->orderBy('created_at','asc')->get();
    	// $members = Member::where('member_status', '1')->whereNotNull('member_Bday')->orderBy('created_at', 'desc')->take(4)->get();

    	update_last_action();

    	return view('frontend.index', compact('members'));
	}

	public function backend()
    {
			$members_new = Member::where('member_status', '=', '0')->get(); // จำนวนอาจารย์ที่ยังไม่อนุมัติ
			$members_online_status = Member::where('member_status',  '1')->where('online_status', '1')->get(); // จำนวนคนเข้าใช้ระบบทั้งหมด
			$member_status = Member::where('member_status',  '1')->get(); // จำนวนคนทั้งหมดในระบบ
			$members_old = Member::where('member_status', '=', '1')->where('member_type', '=', 'teacher')->get(); // จำนวนอาจารย์ที่อนุมัติแล้ว
			$members_student = Member::where('member_status', '=', '1')->where('member_type', '=', 'student')->get(); // จำนวนนักเรียน
			$subject = Subject::all(); // จำนวนวิชาในระบบ
			$aptitudes = Aptitude::all(); // จำนวนกลุ่มความถนัด
			$school = School::where('school_status', "1")->get(); // จำนวนโรงเรียนทั้งหมดที่ยังไม่ถูกลบ
			// dd($members_online_status);
			$data = [count($members_new), count($members_old), count($subject),count($members_student),count($aptitudes),count($school),count($members_online_status),count($member_status)];

		// $members_new = Member::where('member_status', '=', '0')->get();
		// $members_old = Member::where('member_status', '=', '1')->where('member_type', '=', 'teacher')->get();
		// $members_student = Member::where('member_status', '=', '1')->where('member_type', '=', 'student')->get();
		// $subject = Subject::all();
		// $data = [count($members_new), count($members_old), count($subject),count($members_student)];

		update_last_action();
		//return $data;
		return view('backend.index', compact('data'));
    }

    public function showView($name=null)
    {
		$members = Member::all();
		update_last_action();

        return view($name,compact('members'));
	}
}
