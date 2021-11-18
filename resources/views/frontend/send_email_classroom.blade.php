<?php
    //$name = 'aaaa';
    //$course = 'bbbb';
    //$teacher_fullname = 'cccc';
	//$url = 'dddd';
	//$start_date = '2019-10-09';
    //$start_time = '10:00';
	//$end_time = '11:00';

    $hostname = 'suksa.online';
?>

<div class="row">
    <div style="padding-left: -10px;">
        <img src="http://{{ $hostname }}/suksa/frontend/template/images/icons/favicon1.png" style="width: 110px; height: 110px;">
    </div>

    <div style="padding-bottom: 15px; font-size: 14px;">
        <label>สวัสดี <b>{{ $name }}</b></label><br>
        @if($teacher_fullname == $name)
			<label style="font-size: 16px;">ถึงเวลาเข้าสอน {{ $course }}</label><br><br>
			<label>ของคุณ <a href="{{ $url }}">Click! เข้าสู่ห้องเรียน</a></label><br>
		@else
			<label style="font-size: 16px;">ถึงเวลาเข้าเรียน {{ $course }}</label><br><br>
			<label>กับผู้สอน {{ $teacher_fullname }} <a href="{{ $url }}">Click! เข้าสู่ห้องเรียน</a></label><br>
        @endif 

        <label>วันที่นัด {{ changeDate($start_date, 'full_date', 'th') }} เวลา {{ substr($start_time,0,5) }}-{{ substr($end_time,0,5) }} น. </label><br><br>

        <label>Thanks,</label><br>
        <label>Suksa Team</label> <label><a href="http://{{ $hostname }}">www.{{ $hostname }}</a></label>
    </div>

    <div style="border-top: 1px solid #eee; padding-top: 13px;">
        <label style="font-size: 12px;">Customer Support (Mon-Fri 09:00-18:00) <b style="font-size: 16px;">02 982 9999</b></label><br>
        <label style="font-size: 12px;">Copyright © 2019 Education. All rights reserved.</label>
    </div>   
</div>
