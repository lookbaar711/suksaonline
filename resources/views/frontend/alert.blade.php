<script type="text/javascript">

    var close_window = '{{ trans('frontend/layouts/modal.close_window') }}';

    @if(session('alert')=='member')
        var teacher_register_success = '{{ trans('frontend/layouts/modal.teacher_register_success') }}';
        var teacher_register_message_1 = '{{ trans('frontend/layouts/modal.teacher_register_message_1') }}';
        var teacher_register_message_2 = '{{ trans('frontend/layouts/modal.teacher_register_message_2') }}';

        Swal.fire({
            title: '<strong>'+teacher_register_success+'</u></strong>',
            imageUrl: '../suksa/frontend/template/images/logo1.png',
            imageHeight: 100,
            html:
                teacher_register_message_1+'<br>'+teacher_register_message_2,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @elseif(session('login')=='fales')
        var login_failed = '{{ trans('frontend/layouts/modal.login_failed') }}';
        var login_failed_message = '{{ trans('frontend/layouts/modal.login_failed_message') }}';

        Swal.fire({
            title: '<strong>'+login_failed+'</u></strong>',
            type: 'error',
            imageHeight: 100,
            html:
                login_failed_message,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @elseif(session('already_login')=='fales')
        var already_login = '{{ trans('frontend/layouts/modal.already_login') }}';
        var already_login_message = '{{ trans('frontend/layouts/modal.already_login_message') }}';

        Swal.fire({
            title: '<strong>'+already_login+'</u></strong>',
            type: 'error',
            imageHeight: 100,
            html:
                already_login_message,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @elseif(session('logout')=='success')
        var logout_success = '{{ trans('frontend/layouts/modal.logout_success') }}';

        Swal.fire({
            title: '<strong>'+logout_success+'</u></strong>',
            imageUrl: '../suksa/frontend/template/images/logo1.png',
            imageHeight: 100,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @elseif(session('not_school') == 'false')
      var not_school = '{{ trans('frontend/layouts/modal.not_school') }}';
      Swal.fire({
          title: '<strong>'+not_school+'</u></strong>',
          type: 'error',
          imageHeight: 100,
          showCloseButton: false,
          showCancelButton: false,
          focusConfirm: false,
          confirmButtonColor: '#28a745',
          confirmButtonText: close_window,
      });

    @elseif(session('first')=='')

          var text1 = 'Suksa Online ร่วมมือกับสำนักงานคณะกรรมการส่งเสริมการศึกษาเอกชน';
          var text2 = 'ช่วยชาติผ่านพ้น วิกฤตการณ์';
          var text3 = 'โคโรน่าไวรัส 2019';
          var text4 = 'เปิดให้ใช้ระบบห้องเรียนออนไลน์ ผ่าน Suksa Online';
          var text44 = 'ฟรี';
          var text5 = 'เปิดให้สถานศึกษาที่สนใจสามารถลงทะเบียนรับสิทธิ์ในการใช้ ระบบห้องเรียน';
          var text6 = 'ออนไลน์ ผ่าน Suksa Online  ฟรีโดยไม่มีค่าใช้จ่าย ตามเงื่อนไขของบริษัท';
          var text7 = 'โดยการให้สิทธิ์ จำนวนทั้งสิ้น 12,000 คน ฟรี 1 เดือน';
          var text8 = 'เริ่มตั้งแต่วันที่ 1 เมษายน 2563 เป็นต้นไป';
          var text9 = 'กรณีมีผู้ใช้สิทธิเต็มจำนวนแล้ว หากสถานศึกษาใดมีความประสงค์';
          var text10 = 'จะใช้ห้องเรียนออนไลน์สามารถสอบถามรายละเอียดได้';
          var tex11 = 'โดยการกรอกแบบฟอร์มความประสงค์ด้านล่างเช่นเดียวกัน';

          Swal.fire({
              imageUrl: '../suksa/frontend/template/images/ads_popup20200324.png',
              // imageHeight: 410,
              width: 540,
              html:
              // '<strong style="font-size: 15px;">'+text1+'</strong><br> <strong><h4>'+text2 +'<span style="color: #cf404e;"> &nbsp;'+ text3+'</span></strong> <br> <strong style="font-size: 18px; color: #6ab22a;">'+text4+'</strong> <strong style="font-size: 32px; color: #6ab22a;">'+text44+'</strong> </br></br> <h6 style="font-size: 15px;">'+text5+'</h6> <h6 tyle="font-size: 15px;">'+text6+'</h6> </br> <h6 tyle="font-size: 15px;">'+text7+'</h6> <h6 tyle="font-size: 15px;">'+text8+'</h6> </BR>'+
              '<a href="https://docs.google.com/forms/d/e/1FAIpQLSeKOQUQEb-K-auFchLd4uJVtFcea2dJmo0xWJDmxO2iFyQXbA/viewform" target="_blank"><button class=" sizepopup bo-rad-23 s-text3 bgwhite hov1 trans-0-5 col colorlink" > Register </button></div></div></a>',
              showCloseButton: true,
              focusConfirm: false,
              showConfirmButton: false,
          });
        // var teacher_register = '{{ trans('frontend/layouts/modal.teacher_register') }}';
        // var teacher_register_text = '{{ trans('frontend/layouts/modal.teacher_register_text') }}';
        //
        // Swal.fire({
        //     title: '<strong>'+teacher_register+'</u></strong>',
        //     imageUrl: '../suksa/frontend/template/images/20670-02.png',
        //     imageHeight: 250,
        //     html:
        //     teacher_register_text+'<br><br>'+
        //     '<a href="{!! route("members.create") !!}"><button class=" sizepopup bo-rad-23 s-text3 bgwhite hov1 trans-0-5 col colorlink" > '+teacher_register+' </button></div></div></a>',
        //     showCloseButton: true,
        //     // showCancelButton: true,
        //     focusConfirm: false,
        //     showConfirmButton: false,
        // });
        <?php
        session(['first' => 'value']);
        ?>
    @elseif(session('login')=='success')
        var welcome_to_suksa = '{{ trans('frontend/layouts/modal.welcome_to_suksa') }}';
        var welcome_to_suksa_text = '{{ trans('frontend/layouts/modal.welcome_to_suksa_text') }}';

        Swal.fire({
            title: '<strong>'+welcome_to_suksa+'</u></strong>',
            imageUrl: '../suksa/frontend/template/images/logo1.png',
            imageHeight: 100,
            html:
                welcome_to_suksa_text,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @elseif(session('alert')=='user')
        var member_register = '{{ trans('frontend/layouts/modal.member_register') }}';
        var member_register_message = '{{ trans('frontend/layouts/modal.member_register_message') }}';

        Swal.fire({
            title: '<strong>'+member_register+'</u></strong>',
            imageUrl: '../suksa/frontend/template/images/logo1.png',
            imageHeight: 100,
            html:
            member_register_message,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @elseif(session('changeaccount'))
        var switch_to = '{{ trans('frontend/layouts/modal.switch_to') }}';
        var success = '{{ trans('frontend/layouts/modal.success') }}';

        Swal.fire({
            title: '<strong>'+switch_to+' {{session('changeaccount')}} '+success+' </u></strong>',
            type: 'success',
            imageHeight: 100,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @elseif(session('coins')=='success')
        var topup_coins_success = '{{ trans('frontend/layouts/modal.topup_coins_success') }}';
        var topup_coins_message_1 = '{{ trans('frontend/layouts/modal.topup_coins_message_1') }}';
        var topup_coins_message_2 = '{{ trans('frontend/layouts/modal.topup_coins_message_2') }}';

        Swal.fire({
            title: '<strong>'+topup_coins_success+'</u></strong>',
            type: 'success',
            imageHeight: 100,
            html:
                topup_coins_message_1+'<br>'+topup_coins_message_2,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @elseif(session('coinsrevoke')=='success')
        var withdraw_coins_success = '{{ trans('frontend/layouts/modal.withdraw_coins_success') }}';
        var withdraw_coins_message_1 = '{{ trans('frontend/layouts/modal.withdraw_coins_message_1') }}';
        var withdraw_coins_message_2 = '{{ trans('frontend/layouts/modal.withdraw_coins_message_2') }}';

        Swal.fire({
            title: '<strong>'+withdraw_coins_success+'</u></strong>',
            type: 'success',
            imageHeight: 100,
            html:
                withdraw_coins_message_1+'<br>'+withdraw_coins_message_2,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @elseif(session('add_member_bank')=='fales')
        var add_member_bank = '{{ trans('frontend/layouts/modal.add_member_bank') }}';
        var add_member_bank_message_1 = '{{ trans('frontend/layouts/modal.add_member_bank_message_1') }}';
        var add_member_bank_message_2 = '{{ trans('frontend/layouts/modal.add_member_bank_message_2') }}';

        Swal.fire({
            title: '<strong>'+add_member_bank+'</u></strong>',
            type: 'error',
            imageHeight: 100,
            html:
                add_member_bank_message_1+'<br>'+add_member_bank_message_2,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @elseif($message = Session::get('alertsuccess'))
        Swal.fire({
            title: '<strong>{{$message}}</u></strong>',
            type: 'success',
            imageHeight: 100,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @elseif($message = Session::get('alerterror'))
        Swal.fire({
            title: '<strong>{{$message}}</u></strong>',
            type: 'error',
            imageHeight: 100,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
        @elseif($message = Session::get('alerteditcourse'))
        Swal.fire({
            title: '<strong>{{$message}}</u></strong>',
            type: 'success',
            imageHeight: 100,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @elseif($message = Session::get('classroom_closed'))
        Swal.fire({
            title: '<strong>{{$message}}</u></strong>',
            type: 'error',
            imageHeight: 100,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @elseif($message = Session::get('please_login'))
        Swal.fire({
            title: '<strong>{{$message}}</u></strong>',
            type: 'info',
            imageHeight: 100,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @elseif($message = Session::get('create_meeting_room_error'))
        Swal.fire({
            title: '<strong>{{$message}}</u></strong>',
            type: 'error',
            imageHeight: 100,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @elseif(session('teaching_rating')=='success')
        var sent_teacher_rating_success = '{{ trans('frontend/layouts/modal.sent_teacher_rating_success') }}';

        Swal.fire({
            title: '<strong>'+sent_teacher_rating_success+'</u></strong>',
            type: 'success',
            imageHeight: 100,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @elseif(session('learning_rating')=='success')
        var sent_student_rating_success = '{{ trans('frontend/layouts/modal.sent_student_rating_success') }}';

        Swal.fire({
            title: '<strong>'+sent_student_rating_success+'</u></strong>',
            type: 'success',
            imageHeight: 100,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @elseif(session('refund')=='success')
        var refund_success = '{{ trans('frontend/layouts/modal.refund_success') }}';
        var refund_message_1 = '{{ trans('frontend/layouts/modal.refund_message_1') }}';
        var refund_message_2 = '{{ trans('frontend/layouts/modal.refund_message_2') }}';

        Swal.fire({
            title: '<strong>'+refund_success+'</u></strong>',
            type: 'success',
            imageHeight: 100,
            html:
                refund_message_1+'<br>'+refund_message_2,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @elseif(session('already_refund')=='false')
        var already_refund = '{{ trans('frontend/layouts/modal.already_refund') }}';
        var already_refund_message_1 = '{{ trans('frontend/layouts/modal.already_refund_message_1') }}';
        var already_refund_message_2 = '{{ trans('frontend/layouts/modal.already_refund_message_2') }}';

        Swal.fire({
            title: '<strong>'+already_refund+'</u></strong>',
            type: 'error',
            imageHeight: 100,
            html:
                already_refund_message_1+'<br>'+already_refund_message_2,
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: close_window,
        });
    @endif
</script>
