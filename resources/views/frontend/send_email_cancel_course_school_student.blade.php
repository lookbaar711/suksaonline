<?php
    //$student_fullname = 'aaaa';
    //$course_name = 'ภาษาไทย';
    //$teacher_fullname = 'bbbb';
    //$course_date = '2019-10-09';
    //$course_time_start = '10:00';
    //$course_time_end = '11:00';

    $hostname = get_hostname();
?>

<div class="row">
    <div style="padding-left: -10px;">
        <img src="http://{{ $hostname }}/suksa/frontend/template/images/icons/favicon1.png" style="width: 110px; height: 110px;">
    </div>

    <div style="padding-bottom: 15px; font-size: 14px;">
        <label>สวัสดี <b>{{ $student_fullname }}</b></label><br>
        <label style="font-size: 16px;">ยกเลิกคอร์สเรียนแล้ว</label><br><br>
        <label>คุณสมัครเรียน {{ $course_name }} คอร์สเรียนถูกยกเลิกแล้ว</label><br>
    	   <label>ของอาจารย์ {{ $teacher_fullname }}</label><br>
        <label>วันที่สอน {{ $course_date }} เวลา {{ $course_time_start }} - {{ $course_time_end }} น.</label><br><br>

        <label>Thanks,</label><br>
        <label>Suksa Team</label> <label><a href="http://{{ $hostname }}">www.{{ $hostname }}</a></label>
    </div>

    <div style="border-top: 1px solid #eee; padding-top: 13px;">
        <label style="font-size: 12px;">Customer Support (Mon-Fri 09:00-18:00) <b style="font-size: 16px;">02 982 9999</b></label><br>
        <label style="font-size: 12px;">Copyright © 2019 Education. All rights reserved.</label>
    </div>
</div>
