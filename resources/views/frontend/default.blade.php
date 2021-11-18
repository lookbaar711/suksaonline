<!doctype html>
<?php
    if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
        App::setLocale('en');
    }
    else{
        App::setLocale('th');
    }


    /*
    if(isset(Auth::guard('members')->user()->member_lang)){
        if(Auth::guard('members')->user()->member_lang=='en'){
            App::setLocale('en');
        }
        else{
            App::setLocale('th');
        }
    }
    else{
        if(session('lang')=='en'){
            App::setLocale('en');
        }
        else{
            App::setLocale('th');
        }
    }
    */

    $url_alerts_profile = "";

    //echo 'config : '.Config::get('app.locale').' -- session : '.session('lang');

    if(Auth::guard('members')->user()){
        $member_noti = get_member_noti();
        $count_noti = count_member_noti();
        $count_badge_noti = count_badge_member_noti();
        $show_noti = 1;
        $member_role = Auth::guard('members')->user()->member_role;

        if(Auth::guard('members')->user()->member_type =='student'){
            $approve_topup_coins_url = url('users/profile/');
            $not_approve_topup_coins_url = url('users/profile/');
            $approve_withdraw_coins_url = url('users/profile/');
            $not_approve_withdraw_coins_url = url('users/profile/');
            $url_alerts_profile = url('users/profile/');
        }
        else{
            if(Auth::guard('members')->user()->member_role =='teacher'){
                $approve_topup_coins_url = url('members/profile/');
                $not_approve_topup_coins_url = url('members/profile/');
                $approve_withdraw_coins_url = url('members/profile/');
                $not_approve_withdraw_coins_url = url('members/profile/');
                $url_alerts_profile = url('members/profile/');
            }
            else{
                $approve_topup_coins_url = url('users/profile/');
                $not_approve_topup_coins_url = url('users/profile/');
                $approve_withdraw_coins_url = url('users/profile/');
                $not_approve_withdraw_coins_url = url('users/profile/');
                $url_alerts_profile = url('users/profile/');
            }
        }
    }
    else{
        $count_noti = 0;
        $count_badge_noti = 0;
        $member_role = '';

        $approve_topup_coins_url = '#';
        $not_approve_topup_coins_url = '#';
        $approve_withdraw_coins_url = '#';
        $not_approve_withdraw_coins_url = '#';
    }
    if (!empty(Auth::guard('members')->user()->online_status)) {
      if (Auth::guard('members')->user()->online_status == "0") {
          Auth::guard('members')->logout();
          return view('/');
      }
    }
    // dd($check_bbb_email);
    // dd(Auth::guard('members')->user()->online_status);
    //echo 'lang = '.session('lang').' -- member_lang = '.Auth::guard('members')->user()->member_lang;
    //echo $_SERVER['HTTP_HOST'];
?>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}">
        <title>
            @if($count_badge_noti > 0)
                ({{ $count_badge_noti }})
            @endif
            Suksa Online
        </title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

        <!-- fonts kanit -->
        <link href="https://fonts.googleapis.com/css?family=Kanit&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/fonts/font-awesome-4.7.0/css/font-awesome.min.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/fonts/themify/themify-icons.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/fonts/Linearicons-Free-v1.0.0/icon-font.min.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/fonts/elegant-font/html-css/style.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/vendor/bootstrap/css/bootstrap.min.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/vendor/animate/animate.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/vendor/css-hamburgers/hamburgers.min.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/vendor/animsition/css/animsition.min.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/vendor/select2/select2.min.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/vendor/daterangepicker/daterangepicker.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/vendor/slick/slick.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/vendor/lightbox2/css/lightbox.min.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/css/util.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/css/main.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/css/modal.css') !!}">
        <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/css/bootstrap-datetimepicker.css') !!}" >
        <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/css/bootstrap-datetimepicker.min.css') !!}" >
        @yield('css')

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

        <!-- 1. Addchat css -->
        <link href="{{ asset('assets/addchat/css/addchat.min.css') }}" rel="stylesheet">

    <style>
        div.card-shodow {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
        .text-green{
            font-size: 16px;
            color: rgb(10, 10, 10);
        }
        .overflow_course {
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
          width: 200px;
          display: block;
          height: 34px;
        }
        .overflow_index {
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
          /* width: 200px; */
          display: block;
          /* height: 34px; */
        }
        .img-thumbnail {
            padding: .25rem;
            background-color: #fff;
            border: unset;
            border-radius: .25rem;
            transition: all .2s ease-in-out;
            max-width: 100%;
            height: auto;
        }
    </style>
  </head>

  <body>
    <body>
        <div class="animsition">
            <!-- 2. AddChat widget -->
            @if(Auth::guard('members')->user())
            {{-- <div id="addchat_app"
                data-baseurl="{{ url('') }}"
                data-csrfname="{{ 'X-CSRF-Token' }}"
                data-csrftoken="{{ csrf_token() }}"
            ></div> --}}
            @endif

            @if (!empty($check_bbb_email))
              <script type="text/javascript">
                  var please_login = '{{ trans('frontend/layouts/modal.please_login') }}';
                  var please_login_for_topup_coins = '{{ trans('frontend/layouts/modal.please_login_for_topup_coins') }}';
                  var close_window = '{{ trans('frontend/layouts/modal.close_window') }}';

                  function login(){
                      Swal.fire({
                          title: '<strong>'+please_login+'</u></strong>',
                          type: 'info',
                          imageHeight: 100,
                          html:
                              please_login_for_topup_coins,
                          showCloseButton: true,
                          showCancelButton: false,
                          focusConfirm: false,
                          confirmButtonColor: '#28a745',
                          confirmButtonText: close_window,
                      });
                  }
              </script>
            @endif
            <!-- Header -->
            <header class="header2">
                <!-- Header desktop -->
                <div class="container-menu-header-v2 p-t-2">
                    <div class="topbar2">
                        <a href="{{ url('/') }}" class="logo2">
                            <img src="{{ asset ('suksa/frontend/template/images/logo1.png') }}">
                        </a>
                        @if(!Auth::guard('members')->user())
                        <div class="topbar-child2 m-l-30">
                            <i class="fa fa-user-plus" aria-hidden="true" style="color: #569c37;"></i>&nbsp;
                            <a class="topbar-email" href="{{ route('users.create') }}">
                                @lang('frontend/layouts/title.member_register')
                            </a>
                            <span class="linedivide1"></span>
                            <i class="fa fa-graduation-cap" aria-hidden="true" style="color: #569c37;"></i>&nbsp;
                            <a class="topbar-email" href="{{ route('members.create') }}">
                                @lang('frontend/layouts/title.teacher_register')
                            </a>
                            <span class="linedivide1"></span>
                            <i class="fa fa-lock" aria-hidden="true" style="color: #569c37;"></i>&nbsp;
                            <a class="topbar-email" href="#" data-toggle="modal" data-target="#myModal">
                                @lang('frontend/layouts/title.login')
                            </a>

                            <span class="linedivide1"></span>
                            <a class="dropdown-toggle" href="#"  data-toggle="dropdown" role="button" aria-expanded="false">
                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                    <img src="{{ asset ('suksa/frontend/template/images/icons/flag_english.png') }}" style="width: 25px;">
                                @else
                                    <img src="{{ asset ('suksa/frontend/template/images/icons/flag_th.png') }}" style="width: 25px;">
                                @endif

                                <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ url('/languageTH') }}">&nbsp;
                                    <img src="{{ asset ('suksa/frontend/template/images/icons/flag_th.png') }}" style="width: 25px;">&nbsp;
                                    <label>Thai</label>
                                </a>
                                <a class="dropdown-item" href="{{ url('/languageEN') }}" style="border-top: 1px solid #eee;">&nbsp;
                                    <img src="{{ asset ('suksa/frontend/template/images/icons/flag_english.png') }}" style="width: 25px;">&nbsp;
                                    <label>English</label>
                                </a>
                            </div>
                        </div>
                        @else

                        <div class="topbar-child3 m-l-30">
                            <div class="col text-green" >
                                @lang('frontend/layouts/title.your_coins')
                                <label style="font-size: 20px; color: #e4c200;" class="set_coins">
                                    @if (Auth::guard('members')->user()->member_coins != null)
                                        {{ Auth::guard('members')->user()->member_coins }}
                                    @else
                                        {{0}}
                                    @endif
                                    <img src="{{ asset ('suksa/frontend/template/images/icons/Coins.png') }}" style="width: 20px;" >
                                </label>
                            </div>
                        </div>

                        <div class="topbar-child2 m-l-30" style="padding-top: 20px;">


                            <div class="btn-group">

                                <a class="dropdown-toggle " href="#"  data-toggle="dropdown" role="button" aria-expanded="false"><label class="text-green">@lang('frontend/layouts/title.hello')
                                @if(Auth::guard('members')->user()->member_role=="teacher")
                                    @lang('frontend/layouts/title.teacher')
                                @else
                                    @lang('frontend/layouts/title.you')
                                @endif
                                    {{ Auth::guard('members')->user()->member_fname }} {{ Auth::guard('members')->user()->member_lname }}</label></a>
                                <span class="caret"></span>
                                <div class="dropdown-menu dropdown-menu-right">
                                @if(Auth::guard('members')->user()->member_role =='teacher')
                                    <a class="dropdown-item" href="{{ url('members/profile/') }}" >
                                @else
                                    <a class="dropdown-item" href="{{ url('users/profile/') }}" >
                                @endif
                                    <img src="{{ asset ('suksa/frontend/template/images/icons/145671.jpg') }}" class="imgsize-s" >
                                    <label style="color:black;">@lang('frontend/layouts/title.my_profile')</label>
                                    </a>
                                    @if(Auth::guard('members')->user()->member_role =='teacher')
                                    <a class="dropdown-item" href="{{ url('/calendar/') }}" >
                                    <i class="fa" style="font-size:24px">&#xf073;</i> <label for="">@lang('frontend/members/title.my_schedul')</label>
                                </a>
                                @endif


                                @if(Auth::guard('members')->user()->member_role =='teacher')
                                    <a class="dropdown-item" href="{{ url('changeaccount') }}" >
                                      <i class="fa fa-retweet" aria-hidden="true" style="font-size:24px"></i>
                                      <label style="color:black;"> @lang('frontend/layouts/title.switch_to_student') </label>
                                    </a>
                                @elseif(Auth::guard('members')->user()->member_role =='student')
                                    <a class="dropdown-item" href="{{ url('changeaccount') }}">
                                      <i class="fa fa-retweet" aria-hidden="true" style="font-size:24px"></i>
                                      <label style="color:black;"> @lang('frontend/layouts/title.switch_to_teacher') </label>
                                    </a>
                                @endif

                                  <a class="dropdown-item btn_modal_change_password" href="#" id="btn_modal_change_password">
                                    <i class="fas fa-lock" aria-hidden="true" style="font-size:24px"></i>
                                    <label style="color:black;"> @lang('frontend/layouts/title.Change_password') </label>
                                  </a>

                                  <a class="dropdown-item" href="{{ url('logout/frontend') }}"onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <img src="{{ asset ('suksa/frontend/template/images/icons/145670.jpg') }}" class="imgsize-s" >
                                    <label style="color:black;"> @lang('frontend/layouts/title.logout') </label>
                                    <form id="logout-form" action="{{ url('logout') }}" method="GET">
                                        {{ csrf_field() }}
                                    </form>
                                  </a>
                                </div>
                                <input type="hidden" name="member_id" id="member_id" value="{{ Auth::guard('members')->user()->member_id }}">
                            </div>
                            <span class="linedivide1" style="margin-top: 0px; margin-left: 15px; margin-right: 15px;"></span>

                            <div class="header-wrapicon2" style="padding-right: 20px; padding-top: 3px;">

                                <input type="hidden" name="badge" id="badge" value="{{ $count_badge_noti }}">
                                <a href="#test" class="js-show-header-dropdown clear-badge" style=""> <img src="{{ asset ('suksa/frontend/template/images/icons/img_noti1.png') }}" style="height: 24px; width: 24px;"></a>
                                <div id="show_noti">
                                    @if($count_badge_noti > 0)
                                        <span class="header-icons-noti">{{ $count_badge_noti }}</span>
                                    @endif
                                </div>
                                <!-- Header noti -->
                                <div class="header-cart header-dropdown">
                                    <div class="header-cart-item-txt">
                                        <a href="#" class="header-cart-item-name">
                                            <h5 class="header-noti" style="padding-left: 20px;"><b>@lang('frontend/layouts/title.notification')</b></h5>
                                        </a>
                                    </div>
                                    <ul class="header-cart-wrapitem" id="header_noti">
                                        @if(isset($member_noti) && ($count_noti > 0))
                                            @foreach($member_noti as $noti)
                                                @if($show_noti > 1)

                                                @endif

                                                @if($noti->noti_type == 'open_course_teacher')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Close to the time to teach</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->classroom_name }} of {{ $noti->teacher_fullname }} </p>
                                                                <p class="fs-13 header-noti">Teaching date {{ date('d/m/Y', strtotime($noti->classroom_date)) }} Time {{ $noti->classroom_time_start.'-'.$noti->classroom_time_end }}</p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                    <a href="{{ URL::to('classroom/check/'.$noti->_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">Join ></object></a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>ใกล้ถึงเวลาสอน</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->classroom_name }}  ของคุณ {{ $noti->teacher_fullname }} </p>
                                                                <p class="fs-13 header-noti">วันที่สอน {{ date('d/m/Y', strtotime($noti->classroom_date)) }} เวลา {{ $noti->classroom_time_start.'-'.$noti->classroom_time_end }}</p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                    <a href="{{ URL::to('classroom/check/'.$noti->_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">เข้าร่วม ></object></a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'open_course_student')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Close to the time to study</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->classroom_name }} with teacher {{ $noti->teacher_fullname }} </p>
                                                                <p class="fs-13 header-noti">Teaching date {{ date('d/m/Y', strtotime($noti->classroom_date)) }} Time {{ $noti->classroom_time_start.'-'.$noti->classroom_time_end }}</p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                    <a href="{{ URL::to('classroom/check/'.$noti->_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">Join ></object></a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>ใกล้ถึงเวลาเข้าเรียน</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->classroom_name }} กับผู้สอน {{ $noti->teacher_fullname }} </p>
                                                                <p class="fs-13 header-noti">วันที่นัด {{ date('d/m/Y', strtotime($noti->classroom_date)) }} เวลา {{ $noti->classroom_time_start.'-'.$noti->classroom_time_end }}</p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                    <a href="{{ URL::to('classroom/check/'.$noti->_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">เข้าร่วม ></object></a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'approve_topup_coins')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Top up coins successfully</b></p>
                                                                <p class="fs-13 header-noti">You top up {{ $noti->coins }} coins successfully</p>
                                                                <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>เติม Coins สำเร็จ</b></p>
                                                                <p class="fs-13 header-noti">คุณเติม Coins จำนวน {{ $noti->coins }} Coins สำเร็จ</p>
                                                                <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'not_approve_topup_coins')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Top up coins unsuccessful</b></p>
                                                                <p class="fs-13 header-noti">You top up {{ $noti->coins }} coins unsuccessful</p>
                                                                <p class="fs-13 header-noti">Because {{ $noti->coins_description }}</p>
                                                                <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>เติม Coins ไม่สำเร็จ</b></p>
                                                                <p class="fs-13 header-noti">คุณเติม Coins จำนวน {{ $noti->coins }} Coins ไม่สำเร็จ</p>
                                                                <p class="fs-13 header-noti">เนื่องจาก {{ $noti->coins_description }}</p>
                                                                <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'approve_withdraw_coins')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Withdraw coins successfully</b></p>
                                                                <p class="fs-13 header-noti">You withdraw {{ $noti->coins }} coins successfully</p>
                                                                <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>ถอน Coins สำเร็จ</b></p>
                                                                <p class="fs-13 header-noti">คุณถอน Coins จำนวน {{ $noti->coins }} Coins สำเร็จ</p>
                                                                <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'not_approve_withdraw_coins')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Withdraw coins unsuccessful</b></p>
                                                                <p class="fs-13 header-noti">You withdraw {{ $noti->coins }} coins unsuccessful</p>
                                                                <p class="fs-13 header-noti">Because {{ $noti->coins_description }}</p>
                                                                <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>ถอน Coins ไม่สำเร็จ</b></p>
                                                                <p class="fs-13 header-noti">คุณถอน Coins จำนวน {{ $noti->coins }} Coins ไม่สำเร็จ</p>
                                                                <p class="fs-13 header-noti">เนื่องจาก {{ $noti->coins_description }}</p>
                                                                <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'register_course_teacher')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <a href="#">
                                                                    <p class="text-head-noti"><b>Someone register for your course</b></p>
                                                                    <p class="fs-13 header-noti">{{ $noti->student_fullname }} register for  {{ $noti->classroom_name }}</p>
                                                                    <p class="fs-13 header-noti">Teaching date {{ ($noti->classroom_start_date==$noti->classroom_end_date)?date('d/m/Y', strtotime($noti->classroom_start_date)) : date('d/m/Y', strtotime($noti->classroom_start_date)).' to '.date('d/m/Y', strtotime($noti->classroom_end_date)) }}

                                                                        {{ date('d/m/Y', strtotime($noti->classroom_start_date)) }}</p>
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </a>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <a href="#">
                                                                    <p class="text-head-noti"><b>มีคนสมัครเรียนคอร์สของคุณ</b></p>
                                                                    <p class="fs-13 header-noti">{{ $noti->student_fullname }} สมัครเรียน {{ $noti->classroom_name }}</p>
                                                                    <p class="fs-13 header-noti">วันที่สอน {{ ($noti->classroom_start_date==$noti->classroom_end_date)?date('d/m/Y', strtotime($noti->classroom_start_date)) : date('d/m/Y', strtotime($noti->classroom_start_date)).' ถึง '.date('d/m/Y', strtotime($noti->classroom_end_date)) }}</p>
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </a>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'register_course_private_teacher')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <a href="#">
                                                                    <p class="text-head-noti"><b>Someone register for your course</b></p>
                                                                    <p class="fs-13 header-noti">{{ $noti->student_fullname }} register for  {{ $noti->classroom_name }}</p>
                                                                    <p class="fs-13 header-noti">Teaching date {{ ($noti->classroom_start_date==$noti->classroom_end_date)?date('d/m/Y', strtotime($noti->classroom_start_date)) : date('d/m/Y', strtotime($noti->classroom_start_date)).' to '.date('d/m/Y', strtotime($noti->classroom_end_date)) }}</p>
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </a>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <a href="#">
                                                                    <p class="text-head-noti"><b>ชำระเงินสำเร็จ</b></p>
                                                                    <p class="fs-13 header-noti">{{ $noti->student_fullname }} ชำระค่าเรียน {{ $noti->classroom_name }}</p>
                                                                    <p class="fs-13 header-noti">วันที่สอน {{ ($noti->classroom_start_date==$noti->classroom_end_date)?date('d/m/Y', strtotime($noti->classroom_start_date)) : date('d/m/Y', strtotime($noti->classroom_start_date)).' ถึง '.date('d/m/Y', strtotime($noti->classroom_end_date)) }}</p>
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </a>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'register_course_student')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                            <a href="#">
                                                                <p class="text-head-noti"><b>Registration completed</b></p>
                                                                <p class="fs-13 header-noti">You register for {{ $noti->classroom_name }} with teacher {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">Teaching date {{ ($noti->classroom_start_date==$noti->classroom_end_date)?date('d/m/Y', strtotime($noti->classroom_start_date)) : date('d/m/Y', strtotime($noti->classroom_start_date)).' to '.date('d/m/Y', strtotime($noti->classroom_end_date)) }}</p>
                                                                <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </a>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                            <a href="#">
                                                                <p class="text-head-noti"><b>สมัครเรียนสำเร็จ</b></p>
                                                                <p class="fs-13 header-noti">คุณสมัครเรียน {{ $noti->classroom_name }} กับผู้สอน {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">วันที่สอน {{ ($noti->classroom_start_date==$noti->classroom_end_date)?date('d/m/Y', strtotime($noti->classroom_start_date)) : date('d/m/Y', strtotime($noti->classroom_start_date)).' ถึง '.date('d/m/Y', strtotime($noti->classroom_end_date)) }}</p>
                                                                <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </a>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'register_course_private_student')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                            <a href="#">
                                                                <p class="text-head-noti"><b>Registration completed</b></p>
                                                                <p class="fs-13 header-noti">You register for {{ $noti->classroom_name }} with teacher {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">Teaching date {{ ($noti->classroom_start_date==$noti->classroom_end_date)?date('d/m/Y', strtotime($noti->classroom_start_date)) : date('d/m/Y', strtotime($noti->classroom_start_date)).' to '.date('d/m/Y', strtotime($noti->classroom_end_date)) }}</p>
                                                                <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </a>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                            <a href="#">
                                                                <p class="text-head-noti"><b>ชำระเงินสำเร็จ</b></p>
                                                                <p class="fs-13 header-noti">ชำระค่าเรียน {{ $noti->classroom_name }} กับผู้สอน {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">วันที่สอน {{ ($noti->classroom_start_date==$noti->classroom_end_date)?date('d/m/Y', strtotime($noti->classroom_start_date)) : date('d/m/Y', strtotime($noti->classroom_start_date)).' ถึง '.date('d/m/Y', strtotime($noti->classroom_end_date)) }}</p>
                                                                <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </a>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'invite_course_student')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Private course created, waiting for payment</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->course_name }} with teacher {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">Teaching date
                                                                    {{ date('d/m/Y ', strtotime($noti->course_start_date)) }}
                                                                @if(count($noti->course_datetime)>1)
                                                                to {{ date('d/m/Y ', strtotime($noti->course_end_date)) }}
                                                                @endif
                                                                </p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                                    <a href="{{ URL::to('courses/'.$noti->course_id) }}" class="btn-noti-invate size-noti"><object class="color-noti fs-14">Pay</object></a>
                                                            </div>
                                                            </!doctype>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>คอร์ส Private ถูกสร้างแล้ว รอการชำระ</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->course_name }} กับผู้สอน {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">วันที่สอน
                                                                    {{ date('d/m/Y ', strtotime($noti->course_start_date)) }}
                                                                @if(count($noti->course_datetime)>1)
                                                                ถึง {{ date('d/m/Y ', strtotime($noti->course_end_date)) }}
                                                                @endif
                                                                </p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                                    <a href="{{ URL::to('courses/'.$noti->course_id) }}" class="btn-noti-invate size-noti "><object class="color-noti fs-14">ไปชำระเงิน</object></a>
                                                            </div>
                                                            </!doctype>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'invite_course_teacher')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Create course {{ $noti->noti_course_type }} successfully</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->course_name }} with teacher {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">Teaching date
                                                                    {{ date('d/m/Y ', strtotime($noti->course_start_date)) }}
                                                                @if(count($noti->course_datetime)>1)
                                                                to {{ date('d/m/Y ', strtotime($noti->course_end_date)) }}
                                                                @endif
                                                                </p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                            </div>
                                                            </!doctype>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>สร้างคอร์ส {{ $noti->noti_course_type }} สำเร็จ</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->course_name }} กับผู้สอน {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">วันที่สอน
                                                                    {{ date('d/m/Y ', strtotime($noti->course_start_date)) }}
                                                                @if(count($noti->course_datetime)>1)
                                                                ถึง {{ date('d/m/Y ', strtotime($noti->course_end_date)) }}
                                                                @endif
                                                                </p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                            </div>
                                                            </!doctype>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'invite_course_student_school')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>School course created, waiting for register</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->course_name }} with teacher {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">Teaching date
                                                                    {{ date('d/m/Y ', strtotime($noti->course_start_date)) }}
                                                                @if(count($noti->course_datetime)>1)
                                                                to {{ date('d/m/Y ', strtotime($noti->course_end_date)) }}
                                                                @endif
                                                                </p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                                    <a href="{{ URL::to('courses/'.$noti->course_id) }}" class="btn-noti-invate size-noti"><object class="color-noti fs-14">Register</object></a>
                                                            </div>
                                                            </!doctype>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>คอร์ส School ถูกสร้างแล้ว รอการลงทะเบียน</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->course_name }} กับผู้สอน {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">วันที่สอน
                                                                    {{ date('d/m/Y ', strtotime($noti->course_start_date)) }}
                                                                @if(count($noti->course_datetime)>1)
                                                                ถึง {{ date('d/m/Y ', strtotime($noti->course_end_date)) }}
                                                                @endif
                                                                </p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                                    <a href="{{ URL::to('courses/'.$noti->course_id) }}" class="btn-noti-invate size-noti "><object class="color-noti fs-14">ลงทะเบียน</object></a>
                                                            </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'cancel_course_teacher')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Open course {{$noti->classroom_name }} (Private) unsuccessful</b></p>
                                                                <p class="fs-13 header-noti">Because students haven't paid all the study fee.</p>
                                                                <p class="fs-13 header-noti">Contact students to re-open the course.</p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>เปิดคอร์สเรียน{{$noti->classroom_name }} (Private)  ไม่สำเร็จ</b></p>
                                                                <p class="fs-13 header-noti">เนื่องจากนักเรียนชำระเงินค่าเรียนไม่ครบทุกคน</p>
                                                                <p class="fs-13 header-noti">ติดต่อนักเรียนเพื่อเปิดคอร์สเรียนใหม่อีกครั้ง</p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'cancel_course_teacher_not')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Open course {{$noti->classroom_name }} (Private) unsuccessful</b></p>
                                                                <p class="fs-13 header-noti">Because no one pays study fee.</p>
                                                                <p class="fs-13 header-noti">Contact students to re-open the course.</p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>เปิดคอร์สเรียน{{$noti->classroom_name }} (Private)  ไม่สำเร็จ</b></p>
                                                                <p class="fs-13 header-noti">เนื่องจากไม่มีผู้ชำระเงินค่าเรียน</p>
                                                                <p class="fs-13 header-noti">ติดต่อนักเรียนเพื่อเปิดคอร์สเรียนใหม่อีกครั้ง</p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'cancel_course_student')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>The {{ $noti->classroom_name }} course (Private) is canceled</b></p>
                                                                <p class="fs-13 header-noti">Because someone didn't pay the study fee.</p>
                                                                <p class="fs-13 header-noti">You have refunded {{ number_format($noti->course_price) }} Coins</p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>คอร์ส {{ $noti->classroom_name }} (Private) ถูกยกเลิก</b></p>
                                                                <p class="fs-13 header-noti">เนื่องจากมีผู้ไม่ชำระเงินค่าเรียน</p>
                                                                <p class="fs-13 header-noti">คุณได้รับคืน Coins จำนวน {{ number_format($noti->course_price) }} Coins</p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'request_to_teacher')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>There is a request that matches you</b></p>
                                                                <p class="fs-13 header-noti">students {{ $noti->student_fullname }} </p>
                                                                <p class="fs-13 header-noti">Request education group {{ $noti->request_group_name_en }} subjects {{ $noti->request_subject_name_en }}</p>
                                                                <p class="fs-13 header-noti">Preferred date {{ $noti->request_date }} เวลา {{ $noti->request_time }}</p>
                                                            <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                    <a href="{{ URL::to('/request/user_profile/'.$noti->student_id) }}" class="btn-noti-invate size-noti "><object class="color-noti fs-14">Detail</object></a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>มี Request ที่ตรงกับคุณ</b></p>
                                                                <p class="fs-13 header-noti">ผู้เรียน {{ $noti->student_fullname }} </p>
                                                                <p class="fs-13 header-noti">ได้ Request กลุ่มการศึกษา {{ $noti->request_group_name_th }} รายวิชา {{ $noti->request_subject_name_th }}</p>
                                                                <p class="fs-13 header-noti">วันที่ต้องการเรียน {{ date('d/m/Y', strtotime($noti->request_date)) }} เวลา {{ $noti->request_time }}</p>
                                                            <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                    <a href="{{ URL::to('/request/user_profile/'.$noti->student_id) }}" class="btn-noti-invate size-noti "><object class="color-noti fs-14">รายละเอียด</object></a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'get_coins_course')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>You have received coins</b></p>
                                                                <p class="fs-13 header-noti">You have received {{ $noti->coins }} coins</p>
                                                                <p class="fs-13 header-noti">From the course {{ $noti->course_name }}</p>
                                                                <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>คุณได้รับ Coins</b></p>
                                                                <p class="fs-13 header-noti">คุณได้รับ {{ $noti->coins }} Coins</p>
                                                                <p class="fs-13 header-noti">จากการสอนคอร์ส {{ $noti->course_name }}</p>
                                                                <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'sent_student_rating')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Learning assessment</b></p>
                                                                <p class="fs-13 header-noti">You have teached {{ $noti->classroom_name }} successfully</p>

                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                    <a href="{{ URL::to('rating/student_rating/'.$noti->course_id) }}" class="btn-noti size-noti "><object class="color-noti fs-14">Assess</object></a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>ให้คะแนนการเรียน</b></p>
                                                                <p class="fs-13 header-noti">คุณได้ทำการสอน {{ $noti->classroom_name }} เรียบร้อยแล้ว</p>

                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                    <a href="{{ URL::to('rating/student_rating/'.$noti->course_id) }}" class="btn-noti size-noti "><object class="color-noti fs-14">ให้คะแนน</object></a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'sent_teacher_rating')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Teaching assessment</b></p>
                                                                <p class="fs-13 header-noti">You have studied {{ $noti->classroom_name }}</p>
                                                                <p class="fs-13 header-noti">With teacher {{ $noti->teacher_fullname }} successfully</p>

                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                    <a href="{{ URL::to('rating/teacher_rating/'.$noti->course_id) }}" class="btn-noti size-noti "><object class="color-noti fs-14">Assess</object></a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>ให้คะแนนการสอน</b></p>
                                                                <p class="fs-13 header-noti">คุณได้ทำการเรียน {{ $noti->classroom_name }}</p>
                                                                <p class="fs-13 header-noti">กับผู้สอน {{ $noti->teacher_fullname }} เรียบร้อยแล้ว</p>

                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                    <a href="{{ URL::to('rating/teacher_rating/'.$noti->course_id) }}" class="btn-noti size-noti "><object class="color-noti fs-14">ให้คะแนน</object></a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'approve_refund_teacher')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Refund successfully</b></p>
                                                                <p class="fs-13 header-noti">You don't receive {{ $noti->coins }} coins of {{ $noti->student_fullname }}</p>
                                                                <p class="fs-13 header-noti">From the course {{ $noti->course_name }}</p>
                                                                <p class="fs-13 header-noti">Because {{ $noti->refund_description }} </p>

                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>ขอคืนเงินสำเร็จ</b></p>
                                                                <p class="fs-13 header-noti">คุณไม่ได้รับ {{ $noti->coins }} Coins ของ {{ $noti->student_fullname }}</p>
                                                                <p class="fs-13 header-noti">จากคอร์สเรียน {{ $noti->course_name }} </p>
                                                                <p class="fs-13 header-noti">เนื่องจาก {{ $noti->refund_description }} </p>

                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'approve_refund_student')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Refund successfully</b></p>
                                                                <p class="fs-13 header-noti">You have received {{ $noti->coins }} coins refund from the course {{ $noti->course_name }}</p>
                                                                <p class="fs-13 header-noti">Because {{ $noti->refund_description }} </p>

                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>ขอคืนเงินสำเร็จ</b></p>
                                                                <p class="fs-13 header-noti">คุณได้รับ {{ $noti->coins }} Coins คืนจากคอร์สเรียน {{ $noti->course_name }}</p>
                                                                <p class="fs-13 header-noti">เนื่องจาก {{ $noti->refund_description }} </p>

                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'not_approve_refund_teacher')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Refund unsuccessfully</b></p>
                                                                <p class="fs-13 header-noti">You have receive {{ $noti->coins }} coins of {{ $noti->student_fullname }}</p>
                                                                <p class="fs-13 header-noti">From the course {{ $noti->course_name }}</p>
                                                                <p class="fs-13 header-noti">Because {{ $noti->refund_description }} </p>

                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>ขอคืนเงินไม่สำเร็จ</b></p>
                                                                <p class="fs-13 header-noti">คุณได้รับ {{ $noti->coins }} Coins ของ {{ $noti->student_fullname }}</p>
                                                                <p class="fs-13 header-noti">จากคอร์สเรียน {{ $noti->course_name }} </p>
                                                                <p class="fs-13 header-noti">เนื่องจาก {{ $noti->refund_description }} </p>

                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'not_approve_refund_student')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Refund unsuccessfully</b></p>
                                                                <p class="fs-13 header-noti">You don't received {{ $noti->coins }} coins refund from the course {{ $noti->course_name }}</p>
                                                                <p class="fs-13 header-noti">Because {{ $noti->refund_description }} </p>

                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>ขอคืนเงินไม่สำเร็จ</b></p>
                                                                <p class="fs-13 header-noti">คุณไม่ได้รับ {{ $noti->coins }} Coins คืนจากคอร์สเรียน {{ $noti->course_name }}</p>
                                                                <p class="fs-13 header-noti">เนื่องจาก {{ $noti->refund_description }} </p>

                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'post_test_course')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Take the test after studying</b></p>
                                                                <p class="fs-13 header-noti">You have studied {{ $noti->classroom_name }} successfully</p>
                                                                <p class="fs-13 header-noti">Please take the test after studying to measure the results</p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                                    <a href="{{ URL::to('examination/posttest/'.$noti->course_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">Take the test ></object></a>
                                                            </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>ทำเเบบทดสอบหลังเรียน</b></p>
                                                                <p class="fs-13 header-noti">คุณได้ทำการเรียน {{ $noti->classroom_name }} เรียบร้อยแล้ว</p>
                                                                <p class="fs-13 header-noti">กรุณาทำแบบทดสอบหลังเรียน เพื่อวัดผลการเรียน</p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                                    <a href="{{ URL::to('examination/posttest/'.$noti->course_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">ทำแบบทดสอบ ></object></a>
                                                            </div>
                                                            </div>
                                                        </li>
                                                    @endif

                                              @elseif($noti->noti_type == 'cancel_course_school')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Cancel Classes</b></p>
                                                                <p class="fs-13 header-noti">You have studied {{ $noti->course_name }} Has been canceled</p>
                                                                <p class="fs-13 header-noti">Of instructor {{ $noti->teacher_fullname }}</p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                                    {{-- <a href="{{ URL::to('examination/posttest/'.$noti->course_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">Take the test ></object></a> --}}
                                                            </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>ยกเลิกคอร์สเรียน</b></p>
                                                                <p class="fs-13 header-noti">คอร์ส {{ $noti->course_name }} ถูกยกเลิกแล้ว</p>
                                                                <p class="fs-13 header-noti">ของผู้สอน {{ $noti->teacher_fullname }}</p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                                    {{-- <a href="{{ URL::to('examination/posttest/'.$noti->course_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">ทำแบบทดสอบ ></object></a> --}}
                                                            </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                              @elseif($noti->noti_type == 'checkend_homework')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Checked Homework</b></p>
                                                                <p class="fs-13 header-noti">It's time Check homework {{ $noti->assignment_name }}</p>
                                                                <p class="fs-13 header-noti">Of instructor {{ $noti->member_fullname }}</p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                                    <a href="{{ URL::to('homework/teacher/assignment/'.$noti->assignment_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">Check Homework ></object></a>
                                                            </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>ตรวจการบ้าน</b></p>
                                                                <p class="fs-13 header-noti">ถึงเวลาตรวจการบ้าน {{ $noti->assignment_name }}</p>
                                                                <p class="fs-13 header-noti">ของผู้สอน {{ $noti->member_fullname }}</p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                                    <a href="{{ URL::to('homework/teacher/assignment/'.$noti->assignment_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">ตรวจการบ้าน ></object></a>
                                                            </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @elseif($noti->noti_type == 'homework_studen')
                                                    @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Homework</b></p>
                                                                <p class="fs-13 header-noti">Homework {{ $noti->assignment_name }} of {{ $noti->member_fullname }} </p>
                                                                <p class="fs-13 header-noti">By the teacher {{ $noti->teacher_fullname }} </p>
                                                                <p class="fs-13 header-noti">Start date {{ date('d/m/Y H:i', strtotime($noti->assignment_date_start.' '.$noti->assignment_time_start)) }}</p>
                                                                <p class="fs-13 header-noti">End date {{ date('d/m/Y H:i', strtotime($noti->assignment_date_end.' '.$noti->assignment_time_end)) }}</p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                    {{-- <a href="{{ URL::to('assignment/list/'.$noti->assignment_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">Make Homework ></object></a> --}}
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                            <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>การบ้าน</b></p>
                                                                <p class="fs-13 header-noti">การบ้าน {{ $noti->assignment_name }}  ของคุณ {{ $noti->member_fullname }} </p>
                                                                <p class="fs-13 header-noti">โดยอาจารย์ {{ $noti->teacher_fullname }} </p>
                                                                <p class="fs-13 header-noti">วันที่เริ่มต้น {{date('d/m/Y H:i', strtotime($noti->assignment_date_start.' '.$noti->assignment_time_start))}}</p>
                                                                <p class="fs-13 header-noti">วันที่สิ้นสุด {{date('d/m/Y H:i', strtotime($noti->assignment_date_end.' '.$noti->assignment_time_end))}}</p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                    {{-- <a href="{{ URL::to('assignment/list/'.$noti->assignment_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">ทำการบ้าน ></object></a> --}}
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                              @endif
                                                <?php $show_noti++; ?>
                                            @endforeach
                                        @else
                                        <li class="header-cart-item">
                                            <div class="header-cart-item-txt">
                                                <img src="{{ asset ('suksa/frontend/template/images/icons/img_noti2.png') }}"  style="width: 30px; height: 30px;">
                                                <span class="linedivide3"></span>
                                                @lang('frontend/layouts/title.noti_not_found')
                                            </div>
                                        </li>
                                        @endif
                                    </ul>
                                    <div class="header-cart-item-txt" style="padding-top: 10px; text-align: center;">
                                        <input type="text" name="url_alerts_profile" id="url_alerts_profile_1" value="{{$url_alerts_profile}}" style="display:none;">
                                        {{-- <a href="#" class="header-noti"> --}}
                                            <button class="btn-outline default btn_url_alerts_profile" >@lang('frontend/layouts/title.see_all_noti')</button>
                                        {{-- </a> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="btn-group" style="margin-top: -5px;">
                            <a class="dropdown-toggle" href="#"  data-toggle="dropdown" role="button" aria-expanded="false">
                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                    <img src="{{ asset ('suksa/frontend/template/images/icons/flag_english.png') }}" style="width: 25px;">
                                @else
                                    <img src="{{ asset ('suksa/frontend/template/images/icons/flag_th.png') }}" style="width: 25px;">
                                @endif

                                <span class="caret"></span>
                            </a>
                                <div class="dropdown-menu dropdown-menu-right" >
                                    <a class="dropdown-item" href="{{ url('/languageTH') }}">&nbsp;
                                        <img src="{{ asset ('suksa/frontend/template/images/icons/flag_th.png') }}" style="width: 25px;">&nbsp;
                                        <label>Thai</label>
                                    </a>
                                    <a class="dropdown-item" href="{{ url('/languageEN') }}" style="border-top: 1px solid #eee;">&nbsp;
                                        <img src="{{ asset ('suksa/frontend/template/images/icons/flag_english.png') }}" style="width: 25px;">&nbsp;
                                        <label>English</label>
                                    </a>
                                </div>
                            </div>

                        </div>

                        @endif

                    </div>

                    <div class="wrap_header">
                        <div class="wrap_menu">
                            <nav class="menu">
                                <ul class="main_menu">
                                    <li {!! (Request::is('/') ? 'class="main-menu-active"' : '') !!} >
                                        <a href="{{URL::to('/')}}">@lang('frontend/layouts/title.main')</a>
                                    </li>
                                    <li {!! (Request::is('teacher') || Request::is('members/detail/*') ? 'class="main-menu-active"' : '') !!}>
                                        <a href="{{ URL::to('teacher/') }}">@lang('frontend/layouts/title.all_teacher')</a>
                                    </li>
                                    {{-- <!-- <li {!! (Request::is('courses') || Request::is('courses/*') ? 'class="main-menu-active"' : '') !!}>
                                        <a href="{{ URL::to('courses/all/') }}">@lang('frontend/layouts/title.course_online')</a>
                                    </li> --> --}}
                                    <li {!! (Request::is('courses') || Request::is('courses/*') ? 'class="main-menu-active"' : '') !!}>
                                        <a class="dropdown-toggle" href="#"  data-toggle="dropdown" role="button" aria-expanded="false">@lang('frontend/layouts/title.course_online')</a>
                                        <span class="caret"></span>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ url('courses/all/') }}">
                                                @lang('frontend/layouts/title.all_course')
                                            </a>
                                            @if(Auth::guard('members')->user())
                                                @if(Auth::guard('members')->user()->member_role =='teacher')
                                                <a class="dropdown-item" href="{{ URL::to('members/active_course') }}" style="border-top: 1px solid #eee;">
                                                    @lang('frontend/layouts/title.my_course_teacher')
                                                </a>
                                                @else
                                                <a class="dropdown-item" href="{{ URL::to('users/active_course') }}" style="border-top: 1px solid #eee;">
                                                    @lang('frontend/layouts/title.my_course_student')
                                                </a>
                                                @endif
                                            @endif
                                        </div>
                                    </li>
                                    @if(Auth::guard('members')->user())
                                      <li>
                                          <a class="dropdown-toggle" href="#"  data-toggle="dropdown" role="button" aria-expanded="false">
                                          @lang('frontend/layouts/title.homework')</a>
                                          <span class="caret"></span>
                                          <div class="dropdown-menu">
                                            @if (Auth::guard('members')->user()->member_role == "teacher" )
                                              <a class="dropdown-item" href="{{ route('homework.list') }}" style="border-top: 1px solid #eee;">
                                                  @lang('frontend/layouts/title.build_homework')
                                              </a>
                                            <a class="dropdown-item" href="{{url('homework/teacher')}}" style="border-top: 1px solid #eee;">
                                                @lang('frontend/layouts/title.check_homework')
                                            </a>
                                            <a class="dropdown-item" href="{{ url('suksa/frontend/template/ex_course/คู่มือสร้างการบ้าน.pdf') }}" style="border-top: 1px solid #eee;">
                                                @lang('frontend/homework/title.homework_guide')
                                            </a>
                                            @else
                                              <a class="dropdown-item" href="{{url('homework/assignment')}}" style="border-top: 1px solid #eee;">
                                                  @lang('frontend/homework/title.makehomework')
                                              </a>
                                              <a class="dropdown-item" href="{{ url('suksa/frontend/template/ex_course/คู่มือการบ้านนักเรียน.pdf') }}" style="border-top: 1px solid #eee;">
                                                    @lang('frontend/homework/title.student_homework_guide')
                                              </a>
                                            @endif
                                          </div>
                                      </li>

                                        <li>
                                            <a class="dropdown-toggle" href="#"  data-toggle="dropdown" role="button" aria-expanded="false">@lang('frontend/layouts/title.coins')</a>
                                            <span class="caret"></span>
                                            <div class="dropdown-menu">
                                                @if((Auth::guard('members')->user()->member_type =='student') || (Auth::guard('members')->user()->member_role =='student'))
                                                <a class="dropdown-item" href="{{ route('coins.add') }}" >
                                                    <label style="color:black;">@lang('frontend/layouts/title.topup_coins')</label>
                                                </a>
                                                @endif
                                                <a class="dropdown-item" href="{{ route('coins.revoke') }}" >
                                                    <label style="color:black;">@lang('frontend/layouts/title.withdraw_coins')</label>
                                                </a>
                                            </div>
                                        </li>
                                    @else

                                        <li>
                                            <a href="#" onclick="login()">Coins</a>
                                        </li>
                                        <script type="text/javascript">
                                            var please_login = '{{ trans('frontend/layouts/modal.please_login') }}';
                                            var please_login_for_topup_coins = '{{ trans('frontend/layouts/modal.please_login_for_topup_coins') }}';
                                            var close_window = '{{ trans('frontend/layouts/modal.close_window') }}';

                                            function login(){
                                                Swal.fire({
                                                    title: '<strong>'+please_login+'</u></strong>',
                                                    type: 'info',
                                                    imageHeight: 100,
                                                    html:
                                                        please_login_for_topup_coins,
                                                    showCloseButton: true,
                                                    showCancelButton: false,
                                                    focusConfirm: false,
                                                    confirmButtonColor: '#28a745',
                                                    confirmButtonText: close_window,
                                                });
                                            }

                                            function login2(){
                                                Swal.fire({
                                                    title: '<strong>'+please_login+'</u></strong>',
                                                    type: 'info',
                                                    imageHeight: 100,
                                                    showCloseButton: true,
                                                    showCancelButton: false,
                                                    focusConfirm: false,
                                                    confirmButtonColor: '#28a745',
                                                    confirmButtonText: close_window,
                                                });
                                            }
                                        </script>
                                    @endif



                                    @if(Auth::guard('members')->user() == null)
                                      <li class='disabled_request'>
                                          <a href="#" onclick="login2()">@lang('frontend/members/title.build')</a>
                                      </li>
                                    @else
                                      @if((Auth::guard('members')->user()->member_type =='student') || (Auth::guard('members')->user()->member_role =='student'))
                                        <li class='request_subjects'>
                                            <a href="#">@lang('frontend/members/title.build')</a>
                                        </li>
                                      @endif
                                    @endif



                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Header Mobile -->
                <div class="wrap_header_mobile">
                    <!-- Logo moblie -->
                    <a href="{{ url('/') }}" class="logo-mobile">
                            <img src="{{ asset ('suksa/frontend/template/images/logo1.png') }}">
                    </a>
                    <!-- Button show menu -->
                    <div class="btn-show-menu" >
                        <!-- Header Icon mobile -->
                        <div class="header-wrapicon2" style="width: 20px; margin: auto">
                            <input type="hidden" name="badge_mobile" id="badge_mobile" value="{{ $count_badge_noti }}">
                            <a href="#test" class="js-show-header-dropdown clear-badge"> <img src="{{ asset ('suksa/frontend/template/images/icons/img_noti1.png') }}" style="height: 24px; width: 24px;"></a>
                            <div id="show_noti_mobile">
                                @if($count_badge_noti > 0)
                                    <span class="header-icons-noti header-icons-noti-mobile" style="margin-right: -23px; margin-top: -3px;">{{ $count_badge_noti }}</span>
                                @endif
                            </div>
                            <!-- Header noti -->
                            <div class="header-cart2 header-dropdown" >
                                <div class="header-cart-item-txt">
                                    <a href="#" class="header-cart-item-name">
                                        <p class="header-noti" style="padding-left: 20px;"><b>@lang('frontend/layouts/title.notification')</b></p>
                                    </a>
                                </div>
                                <ul class="header-cart-wrapitem" id="header_noti_mobile">
                                    @if(isset($member_noti) && ($count_noti > 0))
                                        @foreach($member_noti as $noti)
                                            {{$noti->noti_type}}
                                            @if($noti->noti_type == 'open_course_teacher')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>Close to the time to teach</b></p>
                                                            <p class="fs-13 header-noti">{{ $noti->classroom_name }}  of {{ $noti->teacher_fullname }} </p>
                                                            <p class="fs-13 header-noti">Teaching date {{ date('d/m/Y', strtotime($noti->classroom_date)) }} Time {{ $noti->classroom_time_start.'-'.$noti->classroom_time_end }}</p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                                <div class="col-sm-7" style="margin-top: -22px; text-align:right;">
                                                                    <a href="{{ URL::to('classroom/check/'.$noti->_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Join >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </object></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>ใกล้ถึงเวลาสอน</b></p>
                                                            <p class="fs-13 header-noti">{{ $noti->classroom_name }}  ของคุณ {{ $noti->teacher_fullname }} </p>
                                                            <p class="fs-13 header-noti">วันที่สอน {{ date('d/m/Y', strtotime($noti->classroom_date)) }} เวลา {{ $noti->classroom_time_start.'-'.$noti->classroom_time_end }}</p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                                <div class="col-sm-7" style="margin-top: -22px; text-align:right;">
                                                                    <a href="{{ URL::to('classroom/check/'.$noti->_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;เข้าร่วม >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </object></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'open_course_student')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>Close to the time to study</b></p>
                                                            <p class="fs-13 header-noti">{{ $noti->classroom_name }} with teacher {{ $noti->teacher_fullname }} </p>
                                                            <p class="fs-13 header-noti">Teaching date {{ date('d/m/Y', strtotime($noti->classroom_date)) }} Time {{ $noti->classroom_time_start.'-'.$noti->classroom_time_end }}</p>
                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                                <div class="col-sm-7" style="margin-top: -22px; text-align:right;">
                                                                    <a href="{{ URL::to('classroom/check/'.$noti->_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Join >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</object></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>ใกล้ถึงเวลาเข้าเรียน</b></p>
                                                            <p class="fs-13 header-noti">{{ $noti->classroom_name }}  กับผู้สอน {{ $noti->teacher_fullname }} </p>
                                                            <p class="fs-13 header-noti">วันที่นัด {{ date('d/m/Y', strtotime($noti->classroom_date)) }} เวลา {{ $noti->classroom_time_start.'-'.$noti->classroom_time_end }}</p>
                                                            <div class="row">
                                                                <div class="col-sm-7">
                                                                    <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-sm-7" style="margin-top: -22px; text-align:right;">
                                                                    <a href="{{ URL::to('classroom/check/'.$noti->_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;เข้าร่วม >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </object></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'approve_topup_coins')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>Top up coins successfully</b></p>
                                                            <p class="fs-13 header-noti">You top up {{ $noti->coins }} coins successfully</p>
                                                            <p class="fs-12 header-noti">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>เติม Coins สำเร็จ</b></p>
                                                            <p class="fs-13 header-noti">คุณเติม Coins จำนวน {{ $noti->coins }} Coins สำเร็จ</p>
                                                            <p class="fs-12 header-noti">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'not_approve_topup_coins')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>Top up coins unsuccessful</b></p>
                                                            <p class="fs-13 header-noti">You top up {{ $noti->coins }} coins unsuccessful</p>
                                                            <p class="fs-13 header-noti">Because {{ $noti->coins_description }}</p>
                                                            <p class="fs-12 header-noti">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>เติม Coins ไม่สำเร็จ</b></p>
                                                            <p class="fs-13 header-noti">คุณเติม Coins จำนวน {{ $noti->coins }} Coins ไม่สำเร็จ</p>
                                                            <p class="fs-13 header-noti">เนื่องจาก {{ $noti->coins_description }}</p>
                                                            <p class="fs-12 header-noti">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'approve_withdraw_coins')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>Withdraw coins successfully</b></p>
                                                            <p class="fs-13 header-noti">You withdraw {{ $noti->coins }} coins successfully</p>
                                                            <p class="fs-12 header-noti">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>ถอน Coins สำเร็จ</b></p>
                                                            <p class="fs-13 header-noti">คุณถอน Coins จำนวน {{ $noti->coins }} Coins สำเร็จ</p>
                                                            <p class="fs-12 header-noti">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'not_approve_withdraw_coins')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>Withdraw coins unsuccessful</b></p>
                                                            <p class="fs-13 header-noti">You withdraw {{ $noti->coins }} coins unsuccessful</p>
                                                            <p class="fs-13 header-noti">Because {{ $noti->coins_description }}</p>
                                                            <p class="fs-12 header-noti">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>ถอน Coins ไม่สำเร็จ</b></p>
                                                            <p class="fs-13 header-noti">คุณถอน Coins จำนวน {{ $noti->coins }} Coins ไม่สำเร็จ</p>
                                                            <p class="fs-13 header-noti">เนื่องจาก {{ $noti->coins_description }}</p>
                                                            <p class="fs-12 header-noti">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'register_course_teacher')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <a href="#">
                                                                <p class="text-head-noti"><b>Someone register for your course</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->student_fullname }} register for {{ $noti->classroom_name }}</p>
                                                                <p class="fs-13 header-noti">Teaching date {{ ($noti->classroom_start_date==$noti->classroom_end_date)?date('d/m/Y', strtotime($noti->classroom_start_date)) : date('d/m/Y', strtotime($noti->classroom_start_date)).' to '.date('d/m/Y', strtotime($noti->classroom_end_date)) }} </p>
                                                                <p class="fs-12 header-noti">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </a>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <a href="#">
                                                                <p class="text-head-noti"><b>มีคนสมัครเรียนคอร์สของคุณ</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->student_fullname }} สมัครเรียน {{ $noti->classroom_name }}</p>
                                                                <p class="fs-13 header-noti">วันที่สอน {{ ($noti->classroom_start_date==$noti->classroom_end_date)?date('d/m/Y', strtotime($noti->classroom_start_date)) : date('d/m/Y', strtotime($noti->classroom_start_date)).' ถึง '.date('d/m/Y', strtotime($noti->classroom_end_date)) }}</p>
                                                                <p class="fs-12 header-noti">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </a>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'register_course_private_teacher')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <a href="#">
                                                                <p class="text-head-noti"><b>Someone register for your course</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->student_fullname }} register for {{ $noti->classroom_name }}</p>
                                                                <p class="fs-13 header-noti">Teaching date {{ ($noti->classroom_start_date==$noti->classroom_end_date)?date('d/m/Y', strtotime($noti->classroom_start_date)) : date('d/m/Y', strtotime($noti->classroom_start_date)).' to '.date('d/m/Y', strtotime($noti->classroom_end_date)) }} </p>
                                                                <p class="fs-12 header-noti">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </a>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <a href="#">
                                                                <p class="text-head-noti"><b>ชำระเงินสำเร็จ</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->student_fullname }} ชำระค่าเรียน {{ $noti->classroom_name }}</p>
                                                                <p class="fs-13 header-noti">วันที่สอน {{ ($noti->classroom_start_date==$noti->classroom_end_date)?date('d/m/Y', strtotime($noti->classroom_start_date)) : date('d/m/Y', strtotime($noti->classroom_start_date)).' ถึง '.date('d/m/Y', strtotime($noti->classroom_end_date)) }}</p>
                                                                <p class="fs-12 header-noti">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </a>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'register_course_student')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <a href="#">
                                                                <p class="text-head-noti"><b>Registration completed</b></p>
                                                                <p class="fs-13 header-noti">You register for {{ $noti->classroom_name }} with teacher {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">Teaching date {{ ($noti->classroom_start_date==$noti->classroom_end_date)?date('d/m/Y', strtotime($noti->classroom_start_date)) : date('d/m/Y', strtotime($noti->classroom_start_date)).' to '.date('d/m/Y', strtotime($noti->classroom_end_date)) }}</p>
                                                                <p class="fs-12 header-noti">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </a>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>สมัครเรียนสำเร็จ</b></p>
                                                                <p class="fs-13 header-noti">คุณสมัครเรียน {{ $noti->classroom_name }} กับผู้สอน {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">วันที่สอน {{ ($noti->classroom_start_date==$noti->classroom_end_date)?date('d/m/Y', strtotime($noti->classroom_start_date)) : date('d/m/Y', strtotime($noti->classroom_start_date)).' ถึง '.date('d/m/Y', strtotime($noti->classroom_end_date)) }}</p>
                                                                <p class="fs-12 header-noti">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'register_course_private_student')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <a href="#">
                                                                <p class="text-head-noti"><b>Registration completed</b></p>
                                                                <p class="fs-13 header-noti">You register for {{ $noti->classroom_name }} with teacher {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">Teaching date {{ ($noti->classroom_start_date==$noti->classroom_end_date)?date('d/m/Y', strtotime($noti->classroom_start_date)) : date('d/m/Y', strtotime($noti->classroom_start_date)).' to '.date('d/m/Y', strtotime($noti->classroom_end_date)) }}</p>
                                                                <p class="fs-12 header-noti">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </a>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>ชำระเงินสำเร็จ</b></p>
                                                                <p class="fs-13 header-noti">ชำระค่าเรียน {{ $noti->classroom_name }} กับผู้สอน {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">วันที่สอน {{ ($noti->classroom_start_date==$noti->classroom_end_date)?date('d/m/Y', strtotime($noti->classroom_start_date)) : date('d/m/Y', strtotime($noti->classroom_start_date)).' ถึง '.date('d/m/Y', strtotime($noti->classroom_end_date)) }}</p>
                                                                <p class="fs-12 header-noti">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'invite_course_student')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Private course created, waiting for payment</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->course_name }} with teacher {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">Teaching date {{ date('d/m/Y ', strtotime($noti->course_start_date)) }}
                                                                @if(count($noti->course_datetime)>1)
                                                                to {{ date('d/m/Y ', strtotime($noti->course_end_date)) }}
                                                                @endif
                                                                </p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                    <div class="col-sm-7" style="margin-top: -22px; text-align:right;">
                                                                        <a href="{{ URL::to('courses/'.$noti->course_id) }}" class="btn-noti-invate size-noti "><object class="color-noti fs-14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pay&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</object></a>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>คอร์ส Private ถูกสร้างแล้ว รอการชำระ</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->course_name }} กับผู้สอน {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">วันที่สอน {{ date('d/m/Y ', strtotime($noti->course_start_date)) }}
                                                                @if(count($noti->course_datetime)>1)
                                                                ถึง {{ date('d/m/Y ', strtotime($noti->course_end_date)) }}
                                                                @endif
                                                                </p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                    <div class="col-sm-7" style="margin-top: -22px; text-align:right;">
                                                                        <a href="{{ URL::to('courses/'.$noti->course_id) }}" class="btn-noti-invate size-noti "><object class="color-noti fs-14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ไปชำระเงิน&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</object></a>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'invite_course_teacher')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Create course {{ $noti->noti_course_type }} successfully</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->course_name }} with teacher {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">Teaching date {{ date('d/m/Y ', strtotime($noti->course_start_date)) }}
                                                                @if(count($noti->course_datetime)>1)
                                                                ถึง {{ date('d/m/Y ', strtotime($noti->course_end_date)) }}
                                                                @endif
                                                                </p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>สร้างคอร์ส {{ $noti->noti_course_type }} สำเร็จ</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->course_name }} กับผู้สอน {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">วันที่สอน {{ date('d/m/Y ', strtotime($noti->course_start_date)) }}
                                                                @if(count($noti->course_datetime)>1)
                                                                ถึง {{ date('d/m/Y ', strtotime($noti->course_end_date)) }}
                                                                @endif
                                                                </p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'invite_course_student_school')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>School course created, waiting for register</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->course_name }} with teacher {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">Teaching date {{ date('d/m/Y ', strtotime($noti->course_start_date)) }}
                                                                @if(count($noti->course_datetime)>1)
                                                                to {{ date('d/m/Y ', strtotime($noti->course_end_date)) }}
                                                                @endif
                                                                </p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                    <div class="col-sm-7" style="margin-top: -22px; text-align:right;">
                                                                        <a href="{{ URL::to('courses/'.$noti->course_id) }}" class="btn-noti-invate size-noti "><object class="color-noti fs-14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Register&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</object></a>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>คอร์ส School ถูกสร้างแล้ว รอการลงทะเบียน</b></p>
                                                                <p class="fs-13 header-noti">{{ $noti->course_name }} กับผู้สอน {{ $noti->teacher_fullname }}</p>
                                                                <p class="fs-13 header-noti">วันที่สอน {{ date('d/m/Y ', strtotime($noti->course_start_date)) }}
                                                                @if(count($noti->course_datetime)>1)
                                                                ถึง {{ date('d/m/Y ', strtotime($noti->course_end_date)) }}
                                                                @endif
                                                                </p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                    <div class="col-sm-7" style="margin-top: -22px; text-align:right;">
                                                                        <a href="{{ URL::to('courses/'.$noti->course_id) }}" class="btn-noti-invate size-noti "><object class="color-noti fs-14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ลงทะเบียน&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</object></a>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'cancel_course_teacher')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Open course {{ $noti->classroom_name }} (Private) unsuccessful</b></p>
                                                                <p class="fs-13 header-noti">Because students haven't paid all the study fee.</p>
                                                                <p class="fs-13 header-noti">Contact students to re-open the course.</p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>เปิดคอร์สเรียน {{ $noti->classroom_name }} (Private) ไม่สำเร็จ</b></p>
                                                                <p class="fs-13 header-noti">เนื่องจากนักเรียนชำระเงินค่าเรียนไม่ครบทุกคน</p>
                                                                <p class="fs-13 header-noti">ติดต่อนักเรียนเพื่อเปิดคอร์สเรียนใหม่อีกครั้ง</p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'cancel_course_teacher_not')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>Open course {{ $noti->classroom_name }} (Private) unsuccessful</b></p>
                                                                <p class="fs-13 header-noti">Because no one pays study fee.</p>
                                                                <p class="fs-13 header-noti">Contact students to re-open the course.</p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>เปิดคอร์สเรียน {{ $noti->classroom_name }} (Private) ไม่สำเร็จ </b></p>
                                                                <p class="fs-13 header-noti">เนื่องจากไม่มีผู้ชำระเงินค่าเรียน</p>
                                                                <p class="fs-13 header-noti">ติดต่อนักเรียนเพื่อเปิดคอร์สเรียนใหม่อีกครั้ง</p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'cancel_course_student')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>The {{ $noti->classroom_name }} course (Private) is canceled</b></p>
                                                                <p class="fs-13 header-noti">Because someone didn't pay the study fee.</p>
                                                                <p class="fs-13 header-noti">You have refunded {{ number_format($noti->course_price) }} Coins</p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>คอร์สเรียน {{ $noti->classroom_name }} (Private) ถูกยกเลิก</b></p>
                                                                <p class="fs-13 header-noti">เนื่องจากมีผู้ไม่ชำระเงินค่าเรียน</p>
                                                                <p class="fs-13 header-noti">คุณได้รับคืน Coins จำนวน {{ number_format($noti->course_price) }} Coins</p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'request_to_teacher')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>There is a request that matches you</b></p>
                                                                <p class="fs-13 header-noti">students {{ $noti->student_fullname }} </p>
                                                                <p class="fs-13 header-noti">Request education group {{ $noti->request_group_name_en }} subjects {{ $noti->request_subject_name_en }}</p>
                                                                <p class="fs-13 header-noti">Preferred date {{ date('d/m/Y', strtotime($noti->request_date)) }} เวลา {{ $noti->request_time }}</p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                    <div class="col-sm-7" style="margin-top: -22px; text-align:right;">
                                                                        <a href="{{ URL::to('/request/user_profile/'.$noti->student_id) }}" class="btn-noti-invate size-noti "><object class="color-noti fs-14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Detail&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</object></a>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                                <p class="text-head-noti"><b>มี Request ที่ตรงกับคุณ</b></p>
                                                                <p class="fs-13 header-noti">ผู้เรียน {{ $noti->student_fullname }} </p>
                                                                <p class="fs-13 header-noti">ได้ Request กลุ่มการศึกษา {{ $noti->request_group_name_th }} รายวิชา {{ $noti->request_subject_name_th }}</p>
                                                                <p class="fs-13 header-noti">วันที่ต้องการเรียน {{ date('d/m/Y', strtotime($noti->request_date)) }} เวลา {{ $noti->request_time }}</p>
                                                                <div class="row">
                                                                    <div class="col-sm-7" >
                                                                        <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                    </div>
                                                                    <div class="col-sm-7" style="margin-top: -22px; text-align:right;">
                                                                        <a href="{{ URL::to('/request/user_profile/'.$noti->student_id) }}" class="btn-noti-invate size-noti "><object class="color-noti fs-14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;รายละเอียด&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</object></a>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'get_coins_course')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>You have received coins</b></p>
                                                            <p class="fs-13 header-noti">You have received {{ $noti->coins }} coins</p>
                                                            <p class="fs-13 header-noti">From the course {{ $noti->course_name }}</p>
                                                            <p class="fs-12 header-noti">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>คุณได้รับ Coins</b></p>
                                                            <p class="fs-13 header-noti">คุณได้รับ {{ $noti->coins }} Coins</p>
                                                            <p class="fs-13 header-noti">จากการสอนคอร์ส {{ $noti->course_name }}</p>
                                                            <p class="fs-12 header-noti">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'sent_student_rating')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>Learning assessment</b></p>
                                                            <p class="fs-13 header-noti">You have teached {{ $noti->classroom_name }} successfully</p>

                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                                <div class="col-sm-7" style="margin-top: -22px; text-align:right;">
                                                                    <a href="{{ URL::to('rating/student_rating/'.$noti->course_id) }}" class="btn-noti size-noti "><object class="color-noti fs-14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assess&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</object></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>ให้คะแนนการสอน</b></p>
                                                            <p class="fs-13 header-noti">คุณได้ทำการสอน {{ $noti->classroom_name }} เรียบร้อยแล้ว</p>

                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                                <div class="col-sm-7" style="margin-top: -22px; text-align:right;">
                                                                    <a href="{{ URL::to('rating/student_rating/'.$noti->course_id) }}" class="btn-noti size-noti "><object class="color-noti fs-14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ให้คะแนน&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</object></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'sent_teacher_rating')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>Teaching assessment</b></p>
                                                            <p class="fs-13 header-noti">You have studied {{ $noti->classroom_name }}</p>
                                                            <p class="fs-13 header-noti">With teacher {{ $noti->teacher_fullname }} successfully</p>

                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                                <div class="col-sm-7" style="margin-top: -22px; text-align:right;">
                                                                    <a href="{{ URL::to('rating/teacher_rating/'.$noti->course_id) }}" class="btn-noti size-noti "><object class="color-noti fs-14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assess&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</object></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>ให้คะแนนการสอน</b></p>
                                                            <p class="fs-13 header-noti">คุณได้ทำการเรียน {{ $noti->classroom_name }}</p>
                                                            <p class="fs-13 header-noti">กับผู้สอน {{ $noti->teacher_fullname }} เรียบร้อยแล้ว</p>

                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                                <div class="col-sm-7" style="margin-top: -22px; text-align:right;">
                                                                    <a href="{{ URL::to('rating/teacher_rating/'.$noti->course_id) }}" class="btn-noti size-noti "><object class="color-noti fs-14">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ให้คะแนน&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</object></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'approve_refund_teacher')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>Refund successfully</b></p>
                                                            <p class="fs-13 header-noti">You don't receive {{ $noti->coins }} coins of {{ $noti->student_fullname }}</p>
                                                            <p class="fs-13 header-noti">From the course {{ $noti->course_name }}</p>
                                                            <p class="fs-13 header-noti">Because {{ $noti->refund_description }} </p>

                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>ขอคืนเงินสำเร็จ</b></p>
                                                            <p class="fs-13 header-noti">คุณไม่ได้รับ {{ $noti->coins }} Coins ของ {{ $noti->student_fullname }}</p>
                                                            <p class="fs-13 header-noti">จากคอร์สเรียน {{ $noti->course_name }}</p>
                                                            <p class="fs-13 header-noti">เนื่องจาก {{ $noti->refund_description }} </p>

                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'approve_refund_student')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>Refund successfull</b></p>
                                                            <p class="fs-13 header-noti">You have received {{ $noti->coins }} coins refund from the course {{ $noti->course_name }}</p>
                                                            <p class="fs-13 header-noti">Because {{ $noti->refund_description }} </p>

                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>ขอคืนเงินสำเร็จ</b></p>
                                                            <p class="fs-13 header-noti">คุณได้รับ {{ $noti->coins }} Coins คืนจากคอร์สเรียน {{ $noti->course_name }}</p>
                                                            <p class="fs-13 header-noti">เนื่องจาก {{ $noti->refund_description }} </p>

                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'not_approve_refund_teacher')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>Refund unsuccessfully</b></p>
                                                            <p class="fs-13 header-noti">You have receive {{ $noti->coins }} coins of {{ $noti->student_fullname }}</p>
                                                            <p class="fs-13 header-noti">From the course {{ $noti->course_name }}</p>
                                                            <p class="fs-13 header-noti">Because {{ $noti->refund_description }} </p>

                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>ขอคืนเงินไม่สำเร็จ</b></p>
                                                            <p class="fs-13 header-noti">คุณได้รับ {{ $noti->coins }} Coins ของ {{ $noti->student_fullname }}</p>
                                                            <p class="fs-13 header-noti">จากคอร์สเรียน {{ $noti->course_name }}</p>
                                                            <p class="fs-13 header-noti">เนื่องจาก {{ $noti->refund_description }} </p>

                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'not_approve_refund_student')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>Refund unsuccessfull</b></p>
                                                            <p class="fs-13 header-noti">You don't received {{ $noti->coins }} coins refund from the course {{ $noti->course_name }}</p>
                                                            <p class="fs-13 header-noti">Because {{ $noti->refund_description }} </p>

                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>ขอคืนเงินไม่สำเร็จ</b></p>
                                                            <p class="fs-13 header-noti">คุณไม่ได้รับ {{ $noti->coins }} Coins คืนจากคอร์สเรียน {{ $noti->course_name }}</p>
                                                            <p class="fs-13 header-noti">เนื่องจาก {{ $noti->refund_description }} </p>

                                                            <div class="row">
                                                                <div class="col-sm-7" >
                                                                    <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @elseif($noti->noti_type == 'post_test_course')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>Take the test after studying</b></p>
                                                            <p class="fs-13 header-noti">You have studied {{ $noti->classroom_name }} successfully</p>
                                                            <p class="fs-13 header-noti">Please take the test after studying to measure the results</p>
                                                        <div class="row">
                                                            <div class="col-sm-7" >
                                                                <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </div>
                                                                <a href="{{ URL::to('examination/posttest/'.$noti->course_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">Take the test ></object></a>
                                                        </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>ทำเเบบทดสอบหลังเรียน</b></p>
                                                            <p class="fs-13 header-noti">คุณได้ทำการเรียน {{ $noti->classroom_name }} เรียบร้อยแล้ว</p>
                                                            <p class="fs-13 header-noti">กรุณาทำแบบทดสอบหลังเรียน เพื่อวัดผลการเรียน</p>
                                                        <div class="row">
                                                            <div class="col-sm-7" >
                                                                <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </div>
                                                                <a href="{{ URL::to('examination/posttest/'.$noti->course_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">ทำแบบทดสอบ ></object></a>
                                                        </div>
                                                        </div>
                                                    </li>
                                                @endif
                                        @elseif($noti->noti_type == 'cancel_course_school')
                                              @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                  <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                      <div class="header-cart-item-txt">
                                                          <p class="text-head-noti"><b>Cancel Classes</b></p>
                                                          <p class="fs-13 header-noti">You have studied {{ $noti->course_name }} Has been canceled</p>
                                                          <p class="fs-13 header-noti">Of instructor {{ $noti->teacher_fullname }}</p>
                                                      <div class="row">
                                                          <div class="col-sm-7" >
                                                              <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                          </div>
                                                              {{-- <a href="{{ URL::to('examination/posttest/'.$noti->course_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">Take the test ></object></a> --}}
                                                      </div>
                                                      </div>
                                                  </li>
                                              @else
                                                  <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                      <div class="header-cart-item-txt">
                                                          <p class="text-head-noti"><b>ยกเลิกคอร์สเรียน</b></p>
                                                          <p class="fs-13 header-noti">Course {{ $noti->course_name }} ถูกยกเลิกแล้ว</p>
                                                          <p class="fs-13 header-noti">ของผู้สอน {{ $noti->teacher_fullname }}</p>
                                                      <div class="row">
                                                          <div class="col-sm-7" >
                                                              <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                          </div>
                                                              {{-- <a href="{{ URL::to('examination/posttest/'.$noti->course_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">ทำแบบทดสอบ ></object></a> --}}
                                                      </div>
                                                      </div>
                                                  </li>
                                              @endif
                                        @elseif($noti->noti_type == 'checkend_homework')
                                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>Checked Homework</b></p>
                                                            <p class="fs-13 header-noti">It's time Check homework {{ $noti->assignment_name }}</p>
                                                            <p class="fs-13 header-noti">Of instructor {{ $noti->member_fullname }}</p>
                                                        <div class="row">
                                                            <div class="col-sm-7" >
                                                                <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </div>
                                                                <a href="{{ URL::to('homework/teacher/assignment/'.$noti->assignment_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">Check Homework ></object></a>
                                                        </div>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                        <div class="header-cart-item-txt">
                                                            <p class="text-head-noti"><b>ตรวจการบ้าน</b></p>
                                                            <p class="fs-13 header-noti">ถึงเวลาตรวจการบ้าน {{ $noti->assignment_name }}</p>
                                                            <p class="fs-13 header-noti">ของผู้สอน {{ $noti->member_fullname }}</p>
                                                        <div class="row">
                                                            <div class="col-sm-7" >
                                                                <p class="fs-12" style="margin-top: 2px;">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </div>
                                                                <a href="{{ URL::to('homework/teacher/assignment/'.$noti->assignment_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">ตรวจการบ้าน ></object></a>
                                                        </div>
                                                        </div>
                                                    </li>
                                                @endif
                                        @elseif($noti->noti_type == 'homework_studen')
                                            @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                    <div class="header-cart-item-txt">
                                                        <p class="text-head-noti"><b>Homework</b></p>
                                                        <p class="fs-13 header-noti">Homework {{ $noti->assignment_name }} of {{ $noti->member_fullname }} </p>
                                                        <p class="fs-13 header-noti">By the teacher {{ $noti->teacher_fullname }} </p>
                                                        <p class="fs-13 header-noti">Start date {{ date('d/m/Y H:i', strtotime($noti->assignment_date_start.' '.$noti->assignment_time_start)) }}</p>
                                                        <p class="fs-13 header-noti">End date {{ date('d/m/Y H:i', strtotime($noti->assignment_date_end.' '.$noti->assignment_time_end)) }}</p>
                                                        <div class="row">
                                                            <div class="col-sm-7" >
                                                                <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </div>
                                                            {{-- <a href="{{ URL::to('assignment/list/'.$noti->assignment_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">Make Homework ></object></a> --}}
                                                        </div>
                                                    </div>
                                                </li>
                                            @else
                                                <li class="header-cart-item" style="border-bottom: 1px solid #eee;">
                                                    <div class="header-cart-item-txt">
                                                        <p class="text-head-noti"><b>การบ้าน</b></p>
                                                        <p class="fs-13 header-noti">การบ้าน {{ $noti->assignment_name }}  ของคุณ {{ $noti->member_fullname }} </p>
                                                        <p class="fs-13 header-noti">โดยอาจารย์ {{ $noti->teacher_fullname }} </p>
                                                        <p class="fs-13 header-noti">วันที่เริ่มต้น {{date('d/m/Y H:i', strtotime($noti->assignment_date_start.' '.$noti->assignment_time_start))}}</p>
                                                        <p class="fs-13 header-noti">วันที่สิ้นสุด {{date('d/m/Y H:i', strtotime($noti->assignment_date_end.' '.$noti->assignment_time_end))}}</p>
                                                        <div class="row">
                                                            <div class="col-sm-7" >
                                                                <p class="fs-12">{{ date('d/m/Y H:i', strtotime($noti->created_at)) }}</p>
                                                            </div>
                                                            {{-- <a href="{{ URL::to('assignment/list/'.$noti->assignment_id) }}" target="_blank" class="btn-noti size-noti "><object class="color-noti fs-14">ทำการบ้าน ></object></a> --}}
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                        @endif

                                            <?php $show_noti++; ?>

                                        @endforeach
                                    @else
                                        <li class="header-cart-item">
                                            <div class="header-cart-item-txt">
                                                    <img src="{{ asset ('suksa/frontend/template/images/icons/img_noti2.png') }}"  style="width: 30px; height: 30px;">
                                                    <span class="linedivide3"></span>
                                                @lang('frontend/layouts/title.noti_not_found')
                                            </div>
                                        </li>
                                    @endif

                                </ul>
                                <div class="header-cart-item-txt" style="padding-top: 10px; text-align: center;">
                                    <input type="text" name="url_alerts_profile" id="url_alerts_profile_2" value="{{$url_alerts_profile}}" style="display:none;">
                                    {{-- <a href="#" class="header-noti"> --}}
                                        <button class="btn-outline default btn_url_alerts_profile" >@lang('frontend/layouts/title.see_all_noti')</button>
                                    {{-- </a> --}}
                                </div>
                                </div>
                            </div>
                        <div class="btn-show-menu-mobile hamburger hamburger--squeeze">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Menu Mobile -->
                <div class="wrap-side-menu">
                    <nav class="side-menu">
                        <ul class="main-menu">
                            @if(!Auth::guard('members')->user())
                            <a href="{{ route('users.create') }}">
                            <li class="item-topbar-mobile p-l-20 p-t-8 p-b-8">
                                <span class="topbar-child1 topbar-email">
                                @lang('frontend/layouts/title.member_register')
                                </span>
                            </li>
                            </a>
                            <a href="{{ route('members.create') }}">
                            <li class="item-topbar-mobile p-l-20 p-t-8 p-b-8">
                                <span class="topbar-child1 topbar-email" >
                                @lang('frontend/layouts/title.teacher_register')
                                </span>
                            </li>
                            </a>
                            <a href="#" data-toggle="modal" data-target="#myModal">
                                <li class="item-topbar-mobile p-l-20 p-t-8 p-b-8">
                                    <span class="topbar-child1 topbar-email">
                                    @lang('frontend/layouts/title.login')
                                    </span>
                                </li>
                            </a>

                            @else

                            <li class="item-menu-mobile" aria-hidden="true">
                                <a href="#" style="line-height: 1.5; padding-left:17px;">@lang('frontend/layouts/title.hello')
                                    @if(Auth::guard('members')->user()->member_role=="teacher")
                                        @lang('frontend/layouts/title.teacher')
                                    @else
                                        @lang('frontend/layouts/title.you')
                                    @endif
                                    {{ Auth::guard('members')->user()->member_fname }} {{ Auth::guard('members')->user()->member_lname }}
                                    <div class="" style="color: #fff; padding-left:17px; padding-bottom:4px;">
                                        <span>@lang('frontend/layouts/title.your_coins')</span>
                                        <span style="font-size: 14px; color: #e4c200;" class="set_coins">
                                            @if (Auth::guard('members')->user())
                                                {{ Auth::guard('members')->user()->member_coins > 0 ? Auth::guard('members')->user()->member_coins : 0 }}
                                            @endif
                                            <img src="{{ asset ('suksa/frontend/template/images/icons/Coins.png') }}" style="width: 14px;" >
                                        </span>
                                    </div>
                                </a>
                                <ul class="sub-menu">
                                    @if(Auth::guard('members')->user()->member_role =='teacher')
                                        <li class=" p-0 item-topbar-mobile">
                                            <a class="dropdown-item" style="padding: 4px!important; padding-left: 15px!important;" href="{{ url('members/profile/') }}">
                                                <i class="fa fa-user " aria-hidden="true" style="font-size:24px"></i>&nbsp;@lang('frontend/layouts/title.my_profile')
                                            </a>
                                        </li>
                                    @else
                                        <li class=" p-0 item-topbar-mobile">
                                            <a class="dropdown-item" style="padding: 4px!important; padding-left: 15px!important;" href="{{ url('users/profile/') }}" >
                                                <i class="fa fa-user" aria-hidden="true" style="font-size:24px"></i>&nbsp;@lang('frontend/layouts/title.my_profile')
                                            </a>
                                        </li>
                                    @endif

                                    @if(Auth::guard('members')->user()->member_role =='teacher')
                                        <li class=" p-0 item-topbar-mobile">
                                            <a class="dropdown-item" style="padding: 4px!important; padding-left: 15px!important;" href="{{ url('/calendar') }}" >
                                                <i class="fa" aria-hidden="true" style="font-size:24px">&#xf073;</i> @lang('frontend/members/title.my_schedul')
                                            </a>
                                        </li>
                                    @endif

                                    @if(Auth::guard('members')->user()->member_role =='teacher')
                                        <li class=" p-0 item-topbar-mobile">
                                            <a class="dropdown-item" style="padding: 4px!important; padding-left: 15px!important;" href="{{ url('changeaccount') }}" >
                                                <i class="fa fa-retweet" aria-hidden="true" style="font-size:24px"></i>&nbsp;@lang('frontend/layouts/title.switch_to_student')
                                            </a>
                                        </li>
                                    @elseif(Auth::guard('members')->user()->member_role =='student')
                                        <li class=" p-0 item-topbar-mobile">
                                            <a class="dropdown-item" style="padding: 4px!important; padding-left: 15px!important;" href="{{ url('changeaccount') }}">
                                                <i class="fa fa-retweet" aria-hidden="true" style="font-size:24px"></i>&nbsp;@lang('frontend/layouts/title.switch_to_teacher')
                                            </a>
                                        </li>
                                    @endif

                                    <li class=" p-0 item-topbar-mobile">
                                      <a class="dropdown-item btn_modal_change_password" style="padding: 4px!important; padding-left: 15px!important;" href="#" id="btn_modal_change_password">
                                        <i class="fas fa-lock" aria-hidden="true" style="font-size:24px"></i> &nbsp;@lang('frontend/layouts/title.Change_password')
                                      </a>
                                    </li>

                                    <li class=" p-0 item-topbar-mobile">
                                        <a class="dropdown-item" style="padding: 4px!important; padding-left: 15px!important;" href="{{ url('logout') }}">
                                            <i class="fas fa-sign-out-alt" style="font-size:25px"></i>&nbsp;@lang('frontend/layouts/title.logout')
                                        </a>
                                    </li>
                                </ul>

                                <i class="arrow-main-menu fa fa-angle-right" aria-hidden="true"></i>
                                <input type="hidden" name="member_id" id="member_id" value="{{ Auth::guard('members')->user()->_id }}">
                            </li>
                            @endif

                            <a href="{{ URL::to('/') }}">
                                <li class="item-menu-mobile" style="padding-left: 17px; padding-top: 7px; padding-bottom: 7px; color: #fff !important;">
                                    @lang('frontend/layouts/title.main')
                                </li>
                            </a>

                            <a href="{{ URL::to('teacher') }}">
                                <li class="item-menu-mobile" style="padding-left: 17px; padding-top: 7px; padding-bottom: 7px; color: #fff !important;">
                                    @lang('frontend/layouts/title.all_teacher')
                                </li>
                            </a>

                            {{-- <a href="{{ URL::to('courses/all/') }}">
                                <li class="item-menu-mobile" style="padding-left: 17px; padding-top: 7px; padding-bottom: 7px; color: #fff !important;">
                                    @lang('frontend/layouts/title.course_online')
                                </li>
                            </a> --}}

                            <li class="item-menu-mobile">
                                @if(Auth::guard('members')->user())
                                    <a href="#">@lang('frontend/layouts/title.course_online')</a>
                                    <ul class="sub-menu">
                                        <li class="p-0 item-topbar-mobile">
                                            <a class="dropdown-item p-4" href="{{ URL::to('courses/all/') }}" style="padding: 4px!important; padding-left: 15px!important;">
                                                @lang('frontend/layouts/title.all_course')
                                            </a>
                                        </li>

                                        @if(Auth::guard('members')->user()->member_role =='teacher')
                                            <li class="p-0 item-topbar-mobile">
                                                <a class="dropdown-item p-4" href="{{ URL::to('members/active_course') }}" style="padding: 4px!important; padding-left: 15px!important;">@lang('frontend/layouts/title.my_course_teacher')
                                                </a>
                                            </li>
                                        @else
                                            <li class="p-0 item-topbar-mobile">
                                                <a class="dropdown-item p-4" href="{{ URL::to('users/active_course') }}" style="padding: 4px!important; padding-left: 15px!important;">@lang('frontend/layouts/title.my_course_student')
                                                    </a>
                                            </li>
                                        @endif
                                    </ul>

                                    @if(Auth::guard('members')->user())
                                        <i class="arrow-main-menu fa fa-angle-right" aria-hidden="true"></i>
                                    @endif
                                @else
                                    <a href="{{ URL::to('courses/all/') }}">@lang('frontend/layouts/title.course_online')</a>
                                @endif
                            </li>
                            @if (Auth::guard('members')->user())
                              <li class="item-menu-mobile">
                                  <a href="#">@lang('frontend/layouts/title.homework')</a>
                                  <ul class="sub-menu">
                                    @if ((Auth::guard('members')->user()->member_type == 'teacher') || Auth::guard('members')->user()->member_role =='teacher')
                                      <li class="p-0 item-topbar-mobile">
                                        <a class="dropdown-item p-4" href="{{url('homework/list')}}" style="padding: 4px!important; padding-left: 15px!important;">
                                              @lang('frontend/layouts/title.build_homework')
                                          </a>
                                      </li>
                                      <li class="p-0 item-topbar-mobile">
                                          <a class="dropdown-item p-4" href="{{url('homework/teacher')}}" style="padding: 4px!important; padding-left: 15px!important;">
                                              @lang('frontend/layouts/title.check_homework')
                                          </a>
                                      </li>
                                      <li class="p-0 item-topbar-mobile">
                                          <a class="dropdown-item p-4" href="{{ url('suksa/frontend/template/ex_course/คู่มือสร้างการบ้าน.pdf') }}" style="padding: 4px!important; padding-left: 15px!important;">
                                              @lang('frontend/homework/title.homework_guide')
                                          </a>
                                      </li>
                                    @else
                                      <li class="p-0 item-topbar-mobile">
                                        <a class="dropdown-item p-4" href="{{url('homework/assignment')}}" style="padding: 4px!important; padding-left: 15px!important;">
                                            {{trans('frontend/homework/title.makehomework')}}
                                          </a>
                                      </li>
                                      <li class="p-0 item-topbar-mobile">
                                        <a class="dropdown-item p-4" href="{{ url('suksa/frontend/template/ex_course/คู่มือการบ้านนักเรียน.pdf') }}" style="padding: 4px!important; padding-left: 15px!important;">
                                            {{trans('frontend/homework/title.student_homework_guide')}}
                                          </a>
                                      </li>
                                    @endif

                                  </ul>
                                  <i class="arrow-main-menu fa fa-angle-right" aria-hidden="true"></i>
                              </li>
                            @endif


                            <li class="item-menu-mobile" onclick="login()">
                                @if(Auth::guard('members')->user())
                                    <a href="#">Coins</a>
                                @else
                                    <a onclick="login()">Coins</a>
                                @endif
                                    <ul class="sub-menu">
                                    @if(Auth::guard('members')->user())
                                        @if((Auth::guard('members')->user()->member_type == 'student') || (Auth::guard('members')->user()->member_role == 'student'))
                                            <li class="p-0 item-topbar-mobile">
                                                <a class="dropdown-item p-4" href="{{ route('coins.add') }}" style="padding: 4px!important; padding-left: 15px!important;">
                                                    @lang('frontend/layouts/title.topup_coins')
                                                </a>
                                            </li>
                                        @endif
                                        <li class="p-0 item-topbar-mobile">
                                            <a class="dropdown-item p-4" href="{{ route('coins.revoke') }}" style="padding: 4px!important; padding-left: 15px!important;">
                                                @lang('frontend/layouts/title.withdraw_coins')
                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a href="#" onclick="login()">Coins</a>
                                        </li>
                                    <script type="text/javascript">
                                    var please_login = '{{ trans('frontend/layouts/modal.please_login') }}';
                                    var please_login_for_topup_coins = '{{ trans('frontend/layouts/modal.please_login_for_topup_coins') }}';
                                    var close_window = '{{ trans('frontend/layouts/modal.close_window') }}';

                                    function login(){
                                        Swal.fire({
                                            title: '<strong>'+please_login+'</u></strong>',
                                            type: 'info',
                                            imageHeight: 100,
                                            html:
                                                please_login_for_topup_coins,
                                            showCloseButton: true,
                                            showCancelButton: false,
                                            focusConfirm: false,
                                            confirmButtonColor: '#28a745',
                                            confirmButtonText: close_window,
                                            });
                                        }
                                    </script>
                                    @endif
                                    </ul>
                                @if(Auth::guard('members')->user())
                                <i class="arrow-main-menu fa fa-angle-right" aria-hidden="true"></i>
                                @endif
                            </li>



                            @if(Auth::guard('members')->user() == null)
                              <li class='disabled_request item-menu-mobile'>
                                  <a href="#" onclick="login2()">@lang('frontend/members/title.build')</a>
                              </li>

                            @else
                              @if((Auth::guard('members')->user()->member_type =='student') || (Auth::guard('members')->user()->member_role =='student'))
                                <li class='item-menu-mobile request_subjects'>
                                    <a href="#">@lang('frontend/members/title.build')</a>
                                </li>

                              @endif
                            @endif
                        </a>

                        <a href="{{ url('/languageTH') }}" >
                            <li class="item-topbar-mobile p-l-20 p-t-8 p-b-8">
                                <img src="{{ asset ('suksa/frontend/template/images/icons/flag_th.png') }}" style="width: 25px;">&nbsp;
                                <span class="topbar-child1 topbar-email">Thai</span>
                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='th')) || (session('lang')=='th'))
                                    <i class="fa fa-check" aria-hidden="true" style="color: #569c37;"></i>
                                @endif
                            </li>
                            </a>
                            <a href="{{ url('/languageEN') }}" style="border-top: 1px solid #eee;">
                            <li class="item-topbar-mobile p-l-20 p-t-8 p-b-8">
                                <img src="{{ asset ('suksa/frontend/template/images/icons/flag_english.png') }}" style="width: 25px;">&nbsp;
                                <span class="topbar-child1 topbar-email">English</span>
                                @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                    <i class="fa fa-check" aria-hidden="true" style="color: #569c37;"></i>
                                @endif
                            </li>
                            </a>

                            </li>
                        </ul>
                    </nav>
                </div>
            </header>

            <section>
                <div ><hr class="new05" style="margin-bottom: 0;"/></div>
                <input type="hidden" name="current_lang" id="current_lang" value="{{ Config::get('app.locale') }}">
            </section>

            <!-- Group learning -->

            @include('frontend.alert') {{-- alert แจ้งเตือน --}}

            @yield('content')

            <!-- The Modal -->
            <div class="modal" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ url('login') }}" method="POST">
                            {{ csrf_field() }}
                            <!-- Modal Header -->
                            <div class="modal-header">

                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">

                                        </div>
                                        <div class="col-sm" style="text-align: center;">
                                            <img src="{!! asset ('suksa/frontend/template/images/logo_suksa.png') !!}" width="50px;">
                                        </div>
                                        <div class="col-sm">

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">
                                <label>@lang('frontend/layouts/modal.email')</label>
                                <input type="email" name="member_email" class="form-control"  placeholder="@lang('frontend/layouts/modal.enter_email')" required>
                                <br>
                                <label>@lang('frontend/layouts/modal.password')</label>
                                <input type="password" name="member_password" class="form-control"  placeholder="@lang('frontend/layouts/modal.enter_password')" required>
                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                              <div class="col-md-3">
                                <button type="button" class="btn btn-link" id="forgot_password"><u>@lang('frontend/layouts/title.forgot_your_password')</u></button>
                              </div>
                              <div class="text-right col-md-9">
                                <button type="submit" class="btn btn-success">@lang('frontend/layouts/modal.login')</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">@lang('frontend/layouts/modal.close')</button>
                              </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="modal_forgot_password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="container">
                    <div class="row modal-body" style="margin-left: -15px; margin-right: -15px;">
                      {{ csrf_field() }}
                      <div class="col-md-12 text-center">
                        <span style="font-size: 55px;">
                          <i class="fas fa-lock"></i>
                        </span>
                        <h2>@lang('frontend/layouts/title.forgot_your_password')</h2>
                        <br>
                        <div class="form-group text-left">
                         <label for="exampleFormControlInput1">@lang('frontend/layouts/modal.enter_email') : <span style="color: red; font-size: 20px;" >* </span></label>
                         <input type="email" class="form-control" id="email_forgot_password" name="email_forgot_password" placeholder="@lang('frontend/layouts/title.enter_the_email_that_is_registered')" autocomplete="off" required>
                       </div>
                        <button type="button" id="btn_post_forgot_password" class="btn col-md-12" style="color: #fff; background-color: #003D99; border-color: #003D99;">@lang('frontend/layouts/title.send_the_password_to_your_email')</button>
                      </div>
                    </div>
                </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="modal_change_password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="container">
                    <div class="row modal-body" style="margin-left: -15px; margin-right: -15px; margin-top: 0px; margin-bottom: 0px;">
                      {{ csrf_field() }}
                      <div class="col-md-12 text-center">
                        {{-- <span style="font-size: 55px;">
                          <i class="fas fa-lock"></i>
                        </span> --}}

                        <div style="font-size: 55px; height:82px;">
                          <i class="fas fa-lock" style="vertical-align: text-top;"></i>
                        </div>
                        <h2>@lang('frontend/layouts/title.Change_password')</h2>
                        <br>
                        <div class="needs-validation" novalidate>
                          <div class="form-group text-left" style="margin-bottom: 16px;">
                            <label for="">@lang('frontend/layouts/title.Current_password')</label>
                            <input type="password" class="form-control current_password_is_valid" name="Current_password" id="Current_password" placeholder="@lang('frontend/layouts/title.Enter_the_current_password')" required>
                            <input type="hidden" name="text_Current_password" value="@lang('frontend/layouts/title.current_password_is_correct')" required>
                            <input type="hidden" name="check_password" id="check_password" value="0">
                            {{-- <div class="current_invalid">
                              <textalert>
                            </div> --}}
                          </div>
                          <div class="form-row text-left" style="margin-left: -5px; margin-right: -5px;">
                            <div class="form-group col-md-6" style="margin-bottom: 16px;">
                              <label for="">@lang('frontend/layouts/title.New_password')</label>
                              <input type="password" class="form-control" id="New_password" name="New_password" placeholder="@lang('frontend/layouts/title.Enter_a_new_password')">
                              <input type="hidden" name="text_New_password" value="@lang('frontend/layouts/title.Enter_a_new_password')">
                            </div>
                            <div class="form-group col-md-6" style="margin-bottom: 16px;">
                              <label for="">@lang('frontend/layouts/title.Confirm_password')</label>
                              <input type="password" class="form-control confirm_password_is_valid" id="Confirm_password"  name="Confirm_password"placeholder="@lang('frontend/layouts/title.Enter_the_password_again')" required>
                              <input type="hidden" name="text_Confirm_password" value="@lang('frontend/layouts/title.Confirm_password')" required>
                            </div>
                          </div>
                        </div>
                        <button type="button" id="btn_confirm_password" onclick="forgot_password.confirm_password();" class="btn btn-success col-md-12" disabled>@lang('frontend/layouts/title.Confirm_password_change')</button>
                      </div>
                    </div>
                </div>
                </div>
              </div>
            </div>

            {{-- Model Request Subjects --}}
            @include('frontend.modal_request')
            @include('frontend.modal_request_detal')


            <!-- Back to top -->
            <div class="btn-back-to-top bg0-hov" id="myBtn">
                <span class="symbol-btn-back-to-top">
                    <i class="fa fa-angle-double-up" aria-hidden="true"></i>
                </span>
            </div>
        </div>

        <div id="dropDownSelect1"></div>

        <footer class="footer animsition">Copyright © 2019 Education. All rights reserved.</footer>

        <!-- Container Selection1 -->

    <script type="text/javascript" src="{{ asset ('suksa/frontend/template/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>
    <script type="text/javascript" src="{{ asset ('suksa/frontend/template/vendor/animsition/js/animsition.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset ('suksa/frontend/template/vendor/bootstrap/js/popper.js') }}"></script>
    <script type="text/javascript" src="{{ asset ('suksa/frontend/template/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset ('suksa/frontend/template/vendor/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset ('suksa/frontend/template/vendor/slick/slick.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset ('suksa/frontend/template/js/slick-custom.js') }}"></script>
    <script type="text/javascript" src="{{ asset ('suksa/frontend/template/vendor/countdowntime/countdowntime.js') }}"></script>
    <script type="text/javascript" src="{{ asset ('suksa/frontend/template/vendor/lightbox2/js/lightbox.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset ('suksa/frontend/template/vendor/sweetalert/sweetalert.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset ('suksa/frontend/template/vendor/parallax100/parallax100.js') }}"></script>
    <script src="{{ asset ('suksa/frontend/template/js/main.js') }}"></script>
    <script src="{{ asset ('suksa/frontend/template/js/profileuser.js') }}"></script>
    <script src="{{ asset ('suksa/frontend/template/js/forgot_password.js') }}"></script>
    <script src="https://js.pusher.com/5.0/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

    <script src="{{ asset ('suksa/frontend/template/js/bootstrap-datetimepicker.js') }}"></script>
    <script src="{{ asset ('suksa/frontend/template/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset ('suksa/frontend/template/js/modal_request.js') }}"></script>


    <script type="text/javascript" src="{{ asset('suksa/frontend/template/tinymce5.0.2/tinymce.min.js') }}"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-166081115-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-166081115-1');
    </script>

    <script>
        $(function () {
          forgot_password.check_password();
        })
        $(document).ready(function () {
            //Disable cut copy paste
            $('.header-cart-wrapitem').bind('cut copy paste', function (e) {
                e.preventDefault();
            });

            //Disable mouse right click
            $(".header-cart-wrapitem").on("contextmenu",function(e){
                return false;
            });
        });

        var member_id = $('#member_id').val();

        if(member_id != ''){
            var hostname = '{{ $_SERVER['HTTP_HOST'] }}';
            var my_channel = '';
            var my_event = '';
            var pusher_key = '';
            var lang = $('#current_lang').val();

            //if(location.hostname === "localhost" || location.hostname === "127.0.0.1"){

            if((hostname == "127.0.0.1:8000") || (hostname == "127.0.0.1") || (hostname == "localhost")){
                my_channel = 'localhost-frontend-channel';
                my_event = 'localhost-frontend-event';
                pusher_key = '24612f79a640aebad28e';
            }
            else if(hostname == 'dev.suksa.online'){
                my_channel = 'dev-frontend-channel';
                my_event = 'dev-frontend-event';
                pusher_key = '4e4475993467d44818c4';
            }
            else{ ////suksa.online
                my_channel = 'production-frontend-channel';
                my_event = 'production-frontend-event';
                // pusher_key = 'f7bb3cd5d97dc796dbdd';
                pusher_key = '57dcc45e7b0e52a95ecb';
            }

            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true;

            var pusher = new Pusher(pusher_key, {
                cluster: 'ap1',
                forceTLS: true
            });

            var channel = pusher.subscribe(my_channel);
            channel.bind(my_event, function(data) {
                // console.log(data);
                var count_member_noti = data.count_member_noti;

                if(member_id == data.member_id){
                    var badge = $('#badge').val();
                    var new_badge = parseInt(badge)+1;

                    if(badge == 0){
                        if(count_member_noti == 1){
                            var header_noti = document.getElementById('header_noti');
                            header_noti.innerHTML = '';
                        }

                        var show_noti = document.getElementById('show_noti');
                        show_noti.innerHTML += '<span class="header-icons-noti">'+new_badge+'</span>';

                        var show_noti_mobile = document.getElementById('show_noti_mobile');
                        show_noti_mobile.innerHTML += '<span class="header-icons-noti" style="margin-right: -23px; margin-top: -3px;">'+new_badge+'</span>';
                    }
                    else{
                        $('.header-icons-noti').text(new_badge);
                    }

                    document.title = '('+new_badge+')'+' Suksa Online';

                    $('#badge').val(new_badge);


                    var header_noti = document.getElementById('header_noti');
                    var header_noti_mobile = document.getElementById('header_noti_mobile');
                    var noti_box = '';

                    if(data.noti_type == 'open_course_teacher'){
                        var url_open_classroom  = '{{ url('classroom/check') }}'+'/'+data.noti_id;
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>Close to the time to teach </b></p><p class="fs-13 header-noti">'+data.classroom_name+' of '+data.teacher_fullname+'</p><p class="fs-13 header-noti">Teaching date '+data.classroom_date+' Time '+data.classroom_time_start+'-'+data.classroom_time_end+'</p>'+
                                '<div class="row">'+
                                '<div class="col-sm-7" >'+
                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                '</div>'+
                                    '<a href="'+url_open_classroom+'" target="_blank" '+'class="btn-noti size-noti "><object class="color-noti '+'fs-14">Join ></object></a>'+
                                '</div>'+
                                '</div></li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>ใกล้ถึงเวลาสอน</b></p><p class="fs-13 header-noti">'+data.classroom_name+' ของคุณ '+data.teacher_fullname+'</p><p class="fs-13 header-noti">วันที่สอน '+data.classroom_date+' เวลา '+data.classroom_time_start+'-'+data.classroom_time_end+'</p>'+
                                '<div class="row">'+
                                '<div class="col-sm-7" >'+
                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                '</div>'+
                                    '<a href="'+url_open_classroom+'" target="_blank" '+'class="btn-noti size-noti "><object class="color-noti '+'fs-14">เข้าร่วม ></object></a>'+
                                '</div>'+
                                '</div></li>';
                        }
                    }
                    else if(data.noti_type == 'open_course_student'){
                        var url_open_classroom  = '{{ url('classroom/check') }}'+'/'+data.noti_id;
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>Close to the time to study</b></p><p class="fs-13 header-noti">'+data.classroom_name+' with teacher '+data.teacher_fullname+'</p><p class="fs-13 header-noti">Teaching date '+data.classroom_date+' Time '+data.classroom_time_start+'-'+data.classroom_time_end+'</p>'+
                            '<div class="row">'+
                            '<div class="col-sm-7" >'+
                            '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+
                            '</p>'+
                            '</div>'+
                                '<a href="'+url_open_classroom+'" target="_blank" '+
                                'class="btn-noti size-noti "><object class="color-noti '+
                                'fs-14">Join ></object></a>'+
                            '</div>'+
                            '</div></li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>ใกล้ถึงเวลาเข้าเรียน</b></p><p class="fs-13 header-noti">'+data.classroom_name+' กับผู้สอน '+data.teacher_fullname+'</p><p class="fs-13 header-noti">วันที่นัด '+data.classroom_date+' เวลา '+data.classroom_time_start+'-'+data.classroom_time_end+'</p>'+
                            '<div class="row">'+
                            '<div class="col-sm-7" >'+
                            '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+
                            '</p>'+
                            '</div>'+
                                '<a href="'+url_open_classroom+'" target="_blank" '+
                                'class="btn-noti size-noti "><object class="color-noti '+
                                'fs-14">เข้าร่วม ></object></a>'+
                            '</div>'+
                            '</div></li>';
                        }
                    }
                    else if(data.noti_type == 'approve_topup_coins'){
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>Top up coins successfully</b></p><p class="fs-13 header-noti">You top up '+data.coins+' coins successfully</p><p class="fs-12 header-noti">'+data.created_at+'</p></div></li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>เติม Coins สำเร็จ</b></p><p class="fs-13 header-noti">คุณเติม Coins จำนวน '+data.coins+' Coins สำเร็จ</p><p class="fs-12 header-noti">'+data.created_at+'</p></div></li>';
                        }

                        $("label.set_coins").text(data.sum_coins);
                    }
                    else if(data.noti_type == 'not_approve_topup_coins'){
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>Top up coins unsuccessful</b></p><p class="fs-13 header-noti">You top up '+data.coins+' coins unsuccessful</p><p class="fs-13 header-noti">Because '+data.coins_description+'</p><p class="fs-12 header-noti">'+data.created_at+'</p></div></li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>เติม Coins ไม่สำเร็จ</b></p><p class="fs-13 header-noti">คุณเติม Coins จำนวน '+data.coins+' Coins ไม่สำเร็จ</p><p class="fs-13 header-noti">เนื่องจาก '+data.coins_description+'</p><p class="fs-12 header-noti">'+data.created_at+'</p></div></li>';
                        }
                    }
                    else if(data.noti_type == 'approve_withdraw_coins'){
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>Withdraw coins successfully</b></p><p class="fs-13 header-noti">You withdraw '+data.coins+' coins successfully</p><p class="fs-12 header-noti">'+data.created_at+'</p></div></li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>ถอน Coins สำเร็จ</b></p><p class="fs-13 header-noti">คุณถอน Coins จำนวน '+data.coins+' Coins สำเร็จ</p><p class="fs-12 header-noti">'+data.created_at+'</p></div></li>';
                        }
                    }
                    else if(data.noti_type == 'not_approve_withdraw_coins'){
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>Withdraw coins unsuccessful</b></p><p class="fs-13 header-noti">You withdraw '+data.coins+' coins unsuccessful</p><p class="fs-13 header-noti">Because '+data.coins_description+'</p><p class="fs-12 header-noti">'+data.created_at+'</p></div></li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>ถอน Coins ไม่สำเร็จ</b></p><p class="fs-13 header-noti">คุณถอน Coins จำนวน '+data.coins+' Coins ไม่สำเร็จ</p><p class="fs-13 header-noti">เนื่องจาก '+data.coins_description+'</p><p class="fs-12 header-noti">'+data.created_at+'</p></div></li>';
                        }
                        $("label.set_coins").text(data.sum_coins);
                    }
                    else if(data.noti_type == 'register_course_teacher'){
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><a href="#"><p class="text-head-noti"><b>Someone register for your course</b></p><p class="fs-13 header-noti">'+data.student_fullname+' register for '+data.classroom_name+'</p><p class="fs-13 header-noti">Teaching date '+data.classroom_datetime+'</p><p class="fs-12 header-noti">'+data.created_at+'</p></a></div></li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><a href="#"><p class="text-head-noti"><b>มีคนสมัครเรียนคอร์สของคุณ</b></p><p class="fs-13 header-noti">'+data.student_fullname+' สมัครเรียน '+data.classroom_name+'</p><p class="fs-13 header-noti">วันที่สอน '+data.classroom_datetime+'</p><p class="fs-12 header-noti">'+data.created_at+'</p></a></div></li>';
                        }
                    }
                    else if(data.noti_type == 'register_course_private_teacher'){
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><a href="#"><p class="text-head-noti"><b>Someone register for your course</b></p><p class="fs-13 header-noti">'+data.student_fullname+' register for '+data.classroom_name+'</p><p class="fs-13 header-noti">Teaching date '+data.classroom_date+'</p><p class="fs-12 header-noti">'+data.created_at+'</p></a></div></li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><a href="#"><p class="text-head-noti"><b>ชำระเงินสำเร็จ</b></p><p class="fs-13 header-noti">'+data.student_fullname+' ชำระค่าเรียน '+data.classroom_name+'</p><p class="fs-13 header-noti">วันที่สอน '+data.classroom_date+'</p><p class="fs-12 header-noti">'+data.created_at+'</p></a></div></li>';
                        }
                    }
                    else if(data.noti_type == 'register_course_student'){
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><a href="#"><p class="text-head-noti"><b>Registration completed</b></p><p class="fs-13 header-noti">You register for '+data.classroom_name+' with teacher '+data.teacher_fullname+'</p><p class="fs-13 header-noti">Teaching date '+data.classroom_datetime+'</p></a></div></li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><a href="#"><p class="text-head-noti"><b>สมัครเรียนสำเร็จ</b></p><p class="fs-13 header-noti">คุณสมัครเรียน '+data.classroom_name+' กับผู้สอน '+data.teacher_fullname+'</p><p class="fs-13 header-noti">วันที่สอน '+data.classroom_datetime+'</p></a></div></li>';
                        }
                    }
                    else if(data.noti_type == 'register_course_private_student'){
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><a href="#"><p class="text-head-noti"><b>Registration completed</b></p><p class="fs-13 header-noti">You register for '+data.classroom_name+' with teacher '+data.teacher_fullname+'</p><p class="fs-13 header-noti">Teaching date '+data.classroom_date+'</p></a></div></li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><a href="#"><p class="text-head-noti"><b>ชำระเงินสำเร็จ</b></p><p class="fs-13 header-noti">ชำระค่าเรียน '+data.classroom_name+' กับผู้สอน '+data.teacher_fullname+'</p><p class="fs-13 header-noti">วันที่สอน '+data.classroom_date+'</p></a></div></li>';
                        }
                    }
                    else if(data.noti_type == 'invite_course_student'){
                        var url_course  = '{{ url('courses') }}'+'/'+data.course_id;
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>Private course created, waiting for payment</b></p><p class="fs-13 header-noti">'+data.course_name+' with teacher '+data.teacher_fullname+'</p><p class="fs-13 header-noti">Teaching date '+data.course_datetime+'</p>'+
                                '<div class="row">'+
                                '<div class="col-sm-7" >'+
                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                '</div>'+
                                    '<a href="'+url_course+'" '+'class="btn-noti-invate size-noti"><object class="color-noti '+'fs-14">Pay</object></a>'+
                                '</div>'+
                                '</div></li>';
                        }else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>คอร์ส Private ถูกสร้างแล้ว รอการชำระ</b></p><p class="fs-13 header-noti">'+data.course_name+' กับผู้สอน '+data.teacher_fullname+'</p><p class="fs-13 header-noti">วันที่สอน '+data.course_datetime+'</p>'+
                                '<div class="row">'+
                                '<div class="col-sm-7" >'+
                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                '</div>'+
                                    '<a href="'+url_course+'" '+'class="btn-noti-invate size-noti "><object class="color-noti '+'fs-14">ไปชำระเงิน</object></a>'+
                                '</div>'+
                                '</div></li>';
                        }
                    }
                    else if(data.noti_type == 'invite_course_teacher'){
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>Create course '+data.noti_course_type+' successfully</b></p><p class="fs-13 header-noti">'+data.course_name+' with teacher '+data.teacher_fullname+'</p><p class="fs-13 header-noti">Teaching date '+data.course_datetime+'</p>'+
                                '<div class="row">'+
                                '<div class="col-sm-7" >'+
                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                '</div>'+
                                '</div>'+
                                '</div></li>';
                        }else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>สร้างคอร์ส '+data.noti_course_type+' สำเร็จ</b></p><p class="fs-13 header-noti">'+data.course_name+' กับผู้สอน '+data.teacher_fullname+'</p><p class="fs-13 header-noti">วันที่สอน '+data.course_datetime+'</p>'+
                                '<div class="row">'+
                                '<div class="col-sm-7" >'+
                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                '</div>'+
                                '</div>'+
                                '</div></li>';
                        }
                    }
                    else if(data.noti_type == 'invite_course_student_school'){
                        var url_course  = '{{ url('courses') }}'+'/'+data.course_id;
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>School course created, waiting for register</b></p><p class="fs-13 header-noti">'+data.course_name+' with teacher '+data.teacher_fullname+'</p><p class="fs-13 header-noti">Teaching date '+data.course_datetime+'</p>'+
                                '<div class="row">'+
                                '<div class="col-sm-7" >'+
                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                '</div>'+
                                    '<a href="'+url_course+'" '+'class="btn-noti-invate size-noti"><object class="color-noti '+'fs-14">Register</object></a>'+
                                '</div>'+
                                '</div></li>';
                        }else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>คอร์ส School ถูกสร้างแล้ว รอการลงทะเบียน</b></p><p class="fs-13 header-noti">'+data.course_name+' กับผู้สอน '+data.teacher_fullname+'</p><p class="fs-13 header-noti">วันที่สอน '+data.course_datetime+'</p>'+
                                '<div class="row">'+
                                '<div class="col-sm-7" >'+
                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                '</div>'+
                                    '<a href="'+url_course+'" '+'class="btn-noti-invate size-noti "><object class="color-noti '+'fs-14">ลงทะเบียน</object></a>'+
                                '</div>'+
                                '</div></li>';
                        }
                    }
                    else if(data.noti_type == 'cancel_course_teacher'){
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>Open course '+data.classroom_name+' (Private) unsuccessful</b></p><p class="fs-13 header-noti">Because students haven\'t paid all the study fee.</p>'+
                                '<p class="fs-13 header-noti">Contact students to re-open the course.</p>'+
                                '<div class="row">'+
                                '<div class="col-sm-7" >'+
                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                '</div>'+
                                '</div>'+
                                '</div></li>';
                        }else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>เปิดคอร์สเรียน '+data.classroom_name+' (Private) ไม่สำเร็จ</b></p><p class="fs-13 header-noti">เนื่องจากนักเรียนชำระค่าเรียนไม่ครบทุกคน</p>'+
                                '<p class="fs-13 header-noti">ติดต่อนักเรียนเพื่อเปิดคอร์สเรียนใหม่อีกครั้ง</p>'+
                                '<div class="row">'+
                                '<div class="col-sm-7" >'+
                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                '</div>'+
                                '</div>'+
                                '</div></li>';
                        }
                        $("label.set_coins").text(data.sum_coins);
                    }
                    else if(data.noti_type == 'cancel_course_teacher_not'){
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>Open course '+data.classroom_name+' (Private) unsuccessful</b></p><p class="fs-13 header-noti">Because no one pays study fee.</p>'+
                                '<p class="fs-13 header-noti">Contact students to re-open the course.</p>'+
                                '<div class="row">'+
                                '<div class="col-sm-7" >'+
                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                '</div>'+
                                '</div>'+
                                '</div></li>';
                        }else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>เปิดคอร์สเรียน '+data.classroom_name+' (Private) ไม่สำเร็จ</b></p><p class="fs-13 header-noti">เนื่องจากไม่มีผู้ชำระค่าเรียน</p>'+
                                '<p class="fs-13 header-noti">ติดต่อนักเรียนเพื่อเปิดคอร์สเรียนใหม่อีกครั้ง</p>'+
                                '<div class="row">'+
                                '<div class="col-sm-7" >'+
                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                '</div>'+
                                '</div>'+
                                '</div></li>';
                        }
                    }
                    else if(data.noti_type == 'cancel_course_student'){
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>The '+data.classroom_name+' course (Private) is canceled</b></p><p class="fs-13 header-noti">Because someone didn\'t pay the study fee.</p><p class="fs-13 header-noti">You have refunded '+data.course_price+' Coins</p>'+
                                '<div class="row">'+
                                '<div class="col-sm-7" >'+
                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                '</div>'+
                                '</div>'+
                                '</div></li>';
                        }else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>คอร์สเรียน '+data.classroom_name+' (Private) ถูกยกเลิก</b></p><p class="fs-13 header-noti">เนื่องจากมีผู้ไม่ชำระค่าเรียน</p><p class="fs-13 header-noti">คุณได้รับคืน Coins จำนวน '+data.course_price+' Coins</p>'+
                                '<div class="row">'+
                                '<div class="col-sm-7" >'+
                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                '</div>'+
                                '</div>'+
                                '</div></li>';
                        }
                        $("label.set_coins").text(data.sum_coins);
                    }
                    else if(data.noti_type == 'request_to_teacher'){
                        var url_course  = '{{ URL::to('/request/user_profile/') }}'+'/'+data.student_id;
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;">'+
                                '<div class="header-cart-item-txt">'+
                                '<p class="text-head-noti"><b>There is a request that matches you</b></p>'+
                                '<p class="fs-13 header-noti">students '+data.student_fullname+'</p>'+
                                '<p class="fs-13 header-noti">Request education group '+data.request_group_en+' subjects '+data.request_subject_en+'</p>'+
                                '<p class="fs-13 header-noti">Request date '+data.request_date+' time '+data.request_time+'</p>'+
                                '<div class="row">'+
                                '<div class="col-sm-7" >'+
                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                '</div>'+
                                    '<a href="'+url_course+'" '+'class="btn-noti-invate size-noti "><object class="color-noti '+'fs-14">Detail</object></a>'+
                                '</div>'+
                                '</div></li>';
                        }else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;">'+
                                '<div class="header-cart-item-txt">'+
                                '<p class="text-head-noti"><b>มี Request ที่ตรงกับคุณ</b></p>'+
                                '<p class="fs-13 header-noti">ผู้เรียน '+data.student_fullname+'</p>'+
                                '<p class="fs-13 header-noti">ได้ Request กลุ่มการศึกษา  '+data.request_group_th+' รายวิชา '+data.request_subject_th+'</p>'+
                                '<p class="fs-13 header-noti">วันที่ต้องการเรียน '+data.request_date+' เวลา '+data.request_time+'</p>'+
                                '<div class="row">'+
                                '<div class="col-sm-7" >'+
                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                '</div>'+
                                    '<a href="'+url_course+'" '+'class="btn-noti-invate size-noti "><object class="color-noti '+'fs-14">รายละเอียด</object></a>'+
                                '</div>'+
                                '</div></li>';
                        }
                    }
                    else if(data.noti_type == 'get_coins_course'){
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>You have received coins</b></p><p class="fs-13 header-noti">You have received '+data.coins+' coins</p><p class="fs-13 header-noti">From the course '+data.course_name+'</p><p class="fs-12 header-noti">'+data.created_at+'</p></div></li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>คุณได้รับ Coins</b></p><p class="fs-13 header-noti">คุณได้รับ '+data.coins+' Coins</p><p class="fs-13 header-noti">จากการสอนคอร์ส '+data.course_name+'</p><p class="fs-12 header-noti">'+data.created_at+'</p></div></li>';
                        }

                        $("label.set_coins").text(data.sum_coins);
                    }
                    else if(data.noti_type == 'sent_student_rating'){
                        var url_rating  = '{{ URL::to('rating/student_rating/') }}'+'/'+data.course_id;

                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>Learning assessment</b></p><p class="fs-13 header-noti">You have teached '+data.classroom_name+' successfully</p><div class="row"><div class="col-sm-7" ><p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p></div><a href="'+url_rating+'" class="btn-noti size-noti "><object class="color-noti fs-14">Assess</object></a></div></div></li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>ให้คะแนนการเรียน</b></p><p class="fs-13 header-noti">คุณได้ทำการสอน '+data.classroom_name+' เรียบร้อยแล้ว</p><div class="row"><div class="col-sm-7" ><p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p></div><a href="'+url_rating+'" class="btn-noti size-noti "><object class="color-noti fs-14">ให้คะแนน</object></a></div></div></li>';
                        }
                    }
                    else if(data.noti_type == 'sent_teacher_rating'){
                        var url_rating  = '{{ URL::to('rating/teacher_rating/') }}'+'/'+data.course_id;

                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>Teaching assessment</b></p><p class="fs-13 header-noti">You have studied '+data.classroom_name+'</p><p class="fs-13 header-noti">With teacher '+data.teacher_fullname+' successfully</p><div class="row"><div class="col-sm-7" ><p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p></div><a href="'+url_rating+'" class="btn-noti size-noti "><object class="color-noti fs-14">Assess</object></a></div></div></li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>ให้คะแนนการสอน</b></p><p class="fs-13 header-noti">คุณได้ทำการเรียน '+data.classroom_name+'</p><p class="fs-13 header-noti">กับผู้สอน '+data.teacher_fullname+' เรียบร้อยแล้ว</p><div class="row"><div class="col-sm-7" ><p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p></div><a href="'+url_rating+'" class="btn-noti size-noti "><object class="color-noti fs-14">ให้คะแนน</object></a></div></div></li>';
                        }
                    }
                    else if(data.noti_type == 'approve_refund_teacher'){
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>Refund successfully</b></p><p class="fs-13 header-noti">You don\'t receive '+data.coins+' coins of '+data.student_fullname+'</p><p class="fs-13 header-noti">From the course '+data.course_name+'</p><p class="fs-13 header-noti">Because '+data.refund_description+'</p><div class="row"><div class="col-sm-7"><p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p></div></div></div></li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>ขอคืนเงินสำเร็จ</b></p><p class="fs-13 header-noti">คุณไม่ได้รับ '+data.coins+' Coins ของ '+data.student_fullname+'</p><p class="fs-13 header-noti">จากคอร์สเรียน '+data.course_name+'</p><p class="fs-13 header-noti">เนื่องจาก '+data.refund_description+'</p><div class="row"><div class="col-sm-7" ><p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p></div></div></div></li>';
                        }
                    }
                    else if(data.noti_type == 'approve_refund_student'){
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>Refund successfully</b></p><p class="fs-13 header-noti">You have received '+data.coins+' coins refund from the course '+data.course_name+'</p><p class="fs-13 header-noti">Because '+data.refund_description+'</p><div class="row"><div class="col-sm-7"><p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p></div></div></div></li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>ขอคืนเงินสำเร็จ</b></p><p class="fs-13 header-noti">คุณได้รับ '+data.coins+' Coins คืนจากคอร์สเรียน '+data.course_name+'</p><p class="fs-13 header-noti">เนื่องจาก '+data.refund_description+'</p><div class="row"><div class="col-sm-7" ><p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p></div></div></div></li>';
                        }
                        $("label.set_coins").text(data.sum_coins);
                    }
                    else if(data.noti_type == 'not_approve_refund_teacher'){
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>Refund unsuccessfully</b></p><p class="fs-13 header-noti">You have receive '+data.coins+' coins of '+data.student_fullname+'</p><p class="fs-13 header-noti">From the course '+data.course_name+'</p><p class="fs-13 header-noti">Because '+data.refund_description+'</p><div class="row"><div class="col-sm-7"><p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p></div></div></div></li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>ขอคืนเงินไม่สำเร็จ</b></p><p class="fs-13 header-noti">คุณได้รับ '+data.coins+' Coins ของ '+data.student_fullname+'</p><p class="fs-13 header-noti">จากคอร์สเรียน '+data.course_name+'</p><p class="fs-13 header-noti">เนื่องจาก '+data.refund_description+'</p><div class="row"><div class="col-sm-7" ><p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p></div></div></div></li>';
                        }
                        $("label.set_coins").text(data.sum_coins);
                    }
                    else if(data.noti_type == 'not_approve_refund_student'){
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>Refund unsuccessfully</b></p><p class="fs-13 header-noti">You don\'t received '+data.coins+' coins refund from the course '+data.course_name+'</p><p class="fs-13 header-noti">Because '+data.refund_description+'</p><div class="row"><div class="col-sm-7"><p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p></div></div></div></li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>ขอคืนเงินไม่สำเร็จ</b></p><p class="fs-13 header-noti">คุณไม่ได้รับ '+data.coins+' Coins คืนจากคอร์สเรียน '+data.course_name+'</p><p class="fs-13 header-noti">เนื่องจาก '+data.refund_description+'</p><div class="row"><div class="col-sm-7" ><p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p></div></div></div></li>';
                        }
                    }
                    else if(data.noti_type == 'post_test_course'){
                        var url_posttest  = '{{ URL::to('examination/posttest/') }}'+'/'+data.course_id;

                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>Take the test after studying</b></p><p class="fs-13 header-noti">Take the test after studying '+data.classroom_name+' successfully</p><p class="fs-13 header-noti">Please take the test after studying to measure the results</p><div class="row"><div class="col-sm-7" ><p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p></div><a href="'+url_posttest+'" class="btn-noti size-noti "><object class="color-noti fs-14">Assess</object></a></div></div></li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;"><div class="header-cart-item-txt"><p class="text-head-noti"><b>ทำเเบบทดสอบหลังเรียน</b></p><p class="fs-13 header-noti">คุณได้ทำการเรียน '+data.classroom_name+' เรียบร้อยแล้ว</p><p class="fs-13 header-noti">กรุณาทำแบบทดสอบหลังเรียน เพื่อวัดผลการเรียน</p><div class="row"><div class="col-sm-7" ><p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p></div><a href="'+url_posttest+'" class="btn-noti size-noti "><object class="color-noti fs-14">ทำแบบทดสอบ</object></a></div></div></li>';
                        }
                    }
                    else if(data.noti_type == 'cancel_course_school'){
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;">'+
                                          '<div class="header-cart-item-txt">'+
                                            '<p class="text-head-noti">'+
                                              '<b>Cancel Classes</b>'+
                                            '</p>'+
                                            '<p class="fs-13 header-noti">You have studied '+data.classroom_name+' Has been canceled</p>'+
                                            '<p class="fs-13 header-noti">Of instructor '+data.teacher_fullname+'</p>'+
                                            '<div class="row">'+
                                              '<div class="col-sm-7" >'+
                                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                              '</div>'+
                                            '</div>'+
                                          '</div>'+
                                        '</li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;">'+
                                          '<div class="header-cart-item-txt">'+
                                            '<p class="text-head-noti">'+
                                              '<b>ยกเลิกคอร์สเรียน</b>'+
                                            '</p>'+
                                            '<p class="fs-13 header-noti">Course '+data.classroom_name+' ถูกยกเลิกแล้ว</p>'+
                                            '<p class="fs-13 header-noti">ของผู้สอน '+data.teacher_fullname+'</p>'+
                                            '<div class="row"><div class="col-sm-7" >'+
                                              '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                            '</div>'+
                                            '</div>'+
                                          '</div>'+
                                        '</li>';
                        }
                    }else if(data.noti_type == 'checkend_homework'){
                        var assignment_url  = '{{ url('homework/teacher/assignment') }}'+'/'+data.assignment_id;
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;">'+
                                          '<div class="header-cart-item-txt">'+
                                            '<p class="text-head-noti">'+
                                              '<b>Checked Homework</b>'+
                                            '</p>'+
                                            '<p class="fs-13 header-noti">It time Check homework '+data.assignment_name+'</p>'+
                                            '<p class="fs-13 header-noti">Of instructor '+data.member_fullname+'</p>'+
                                            '<div class="row">'+
                                              '<div class="col-sm-7" >'+
                                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                              '</div>'+
                                                '<a href="'+assignment_url+'" '+'class="btn-noti size-noti "><object class="color-noti fs-14">Check Homework ></object></a>'+
                                            '</div>'+
                                          '</div>'+
                                        '</li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;">'+
                                          '<div class="header-cart-item-txt">'+
                                            '<p class="text-head-noti">'+
                                              '<b>ตรวจการบ้าน</b>'+
                                            '</p>'+
                                            '<p class="fs-13 header-noti">ถึงเวลาตรวจการบ้าน '+data.assignment_name+'</p>'+
                                            '<p class="fs-13 header-noti">ของผู้สอน '+data.member_fullname+'</p>'+
                                            '<div class="row"><div class="col-sm-7" >'+
                                              '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                            '</div>'+
                                                '<a href="'+assignment_url+'" '+'class="btn-noti size-noti "><object class="color-noti fs-14">ตรวจการบ้าน ></object></a>'+
                                            '</div>'+
                                          '</div>'+
                                        '</li>';
                        }
                    }else if(data.noti_type == 'homework_studen'){
                        // var assignment_url  = '{{ url('assignment/list') }}'+'/'+data.assignment_id;
                        if(lang == 'en'){
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;">'+
                                          '<div class="header-cart-item-txt">'+
                                            '<p class="text-head-noti">'+
                                              '<b>Homework</b>'+
                                            '</p>'+
                                            '<p class="fs-13 header-noti"> Homework'+data.assignment_name+' of '+data.member_fullname+'</p>'+
                                            '<p class="fs-13 header-noti">By the teacher '+data.member_fullname+'</p>'+
                                            '<p class="fs-13 header-noti">Start date '+data.assignment_date_start+'</p>'+
                                            '<p class="fs-13 header-noti">End date '+data.assignment_date_end+'</p>'+
                                            '<div class="row">'+
                                              '<div class="col-sm-7" >'+
                                                '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                              '</div>'+
                                                // '<a href="'+assignment_url+'" '+'class="btn-noti size-noti "><object class="color-noti fs-14">Make Homework ></object></a>'+
                                            '</div>'+
                                          '</div>'+
                                        '</li>';
                        }
                        else{
                            noti_box = '<li class="header-cart-item" style="border-bottom: 1px solid #eee;">'+
                                          '<div class="header-cart-item-txt">'+
                                            '<p class="text-head-noti">'+
                                              '<b>การบ้าน</b>'+
                                            '</p>'+
                                            '<p class="fs-13 header-noti"> การบ้าน'+data.assignment_name+' ของคุณ '+data.member_fullname+'</p>'+
                                            '<p class="fs-13 header-noti">โดยอาจารย์ '+data.teacher_fullname+'</p>'+
                                            '<p class="fs-13 header-noti">วันที่เริ่มต้น '+data.assignment_date_start+'</p>'+
                                            '<p class="fs-13 header-noti">วันที่สิ้นสุด '+data.assignment_date_end+'</p>'+
                                            '<div class="row"><div class="col-sm-7" >'+
                                              '<p class="fs-12" style="margin-top: 2px;">'+data.created_at+'</p>'+
                                            '</div>'+
                                                // '<a href="'+assignment_url+'" '+'class="btn-noti size-noti "><object class="color-noti fs-14">ทำการบ้าน ></object></a>'+
                                            '</div>'+
                                          '</div>'+
                                        '</li>';
                        }
                    }

                    // if(count_member_noti > 1){
                    //     noti_box += '<hr>';
                    // }

                    header_noti.insertAdjacentHTML("afterbegin", noti_box);
                    header_noti_mobile.insertAdjacentHTML("afterbegin", noti_box);
                }
            });
        }

        $('.clear-badge').click(function() {
            $.ajax({
                url:"{{ route('frontend.clear_badge') }}",
                method:"POST",
                data:{
                    member_id: $('#member_id').val(),
                    _token: $('input[name="_token"]').val()
                },
                success:function(result){
                    if(result > 0){
                        var show_noti = document.getElementById('show_noti');
                        show_noti.innerHTML = '';

                        var show_noti_mobile = document.getElementById('show_noti_mobile');
                        show_noti_mobile.innerHTML = '';

                        $('#badge').val('0');
                        document.title = 'Suksa Online';
                    }
                }
            });
        });

        $('#datepicker').datetimepicker({
            uiLibrary: 'bootstrap4',
            format: "dd MM yyyy - hh:ii",
            autoclose: true,
            todayBtn: true,
            pickerPosition: "bottom-left"
        });
        // $('#datepicker01').datetimepicker({
        //     uiLibrary: 'bootstrap4',
        //     formatdate: "dd MM yyyy",
        //     autoclose: true,
        //     todayBtn: true,
        //     pickerPosition: "bottom-left"

        // });
        $('#datepicker02').datetimepicker({
            uiLibrary: 'bootstrap4',
            formatViewType: 'time',
            autoclose: true,
            startView: 1,
            maxView: 1,
            minView: 0,
            todayBtn: true,
            minuteStep: 5,
            format: 'hh:ii'
        });
        $('#datepicker03').datetimepicker({
            uiLibrary: 'bootstrap4',
            formatViewType: 'time',
            autoclose: true,
            startView: 1,
            maxView: 1,
            minView: 0,
            todayBtn: true,
            minuteStep: 5,
            format: 'hh:ii'
        });

        $('#start_Date').datetimepicker({
          uiLibrary: 'bootstrap4',
          lang:'th',
          format: 'dd/mm/yyyy',
          minView: "month",
          language: "fr",
          autoclose: true,
        });

        $('.start_Date').datetimepicker({
          uiLibrary: 'bootstrap4',
          lang:'th',
          format: 'dd/mm/yyyy',
          minView: "month",
          language: "fr",
          autoclose: true,
        });

        $("#start_time").datetimepicker({
          uiLibrary: 'bootstrap4',
          formatViewType: 'time',
          autoclose: true,
          startView: 1,
          maxView: 1,
          minView: 0,
          todayBtn: true,
          minuteStep: 5,
          format: 'hh:ii'
        });

        $('#datepicker_profile').datetimepicker({
          uiLibrary: 'bootstrap4',
          lang:'th',
          format: 'dd/mm/yyyy',
          minView: "month",
          language: "fr",
          autoclose: true,
        });

        // $('#start_Date').datetimepicker({
        //     format : "HH:mm",
        //     use24hours: true
        // });


        window.addEventListener("load", activateStickyFooter);

        function activateStickyFooter() {
          // adjustFooterCssTopToSticky();
          // window.addEventListener("resize", adjustFooterCssTopToSticky);
        }

        // function adjustFooterCssTopToSticky() {
        //   const footer = document.querySelector("#footer");
        //   const bounding_box = footer.getBoundingClientRect();
        //   const footer_height = bounding_box.height;
        //   const window_height = window.innerHeight;
        //   const above_footer_height = bounding_box.top - getCssTopAttribute(footer);
        //   if (above_footer_height + footer_height <= window_height) {
        //     const new_footer_top = window_height - (above_footer_height + footer_height);
        //     footer.style.top = new_footer_top + "px";
        //   } else if (above_footer_height + footer_height > window_height) {
        //     footer.style.top = null;
        //   }
        // }

        function getCssTopAttribute(htmlElement) {
          const top_string = htmlElement.style.top;
          if (top_string === null || top_string.length === 0) {
            return 0;
          }
          const extracted_top_pixels = top_string.substring(0, top_string.length - 2);
          return parseFloat(extracted_top_pixels);
        }

        LOREM_IPSUM_SENTENCES = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla viverra libero nibh, nec egestas mauris gravida non. Cras mattis, lacus ac ornare congue, augue felis volutpat nisi, ac lacinia eros libero quis urna. Praesent efficitur ex justo, sit amet condimentum leo elementum ac. Praesent lobortis id lacus non euismod. Pellentesque non ullamcorper nisi. Pellentesque iaculis urna ligula, vitae venenatis enim consequat id. Aliquam consequat egestas tellus. Aliquam erat volutpat. Interdum et malesuada fames ac ante ipsum primis in faucibus. In eu tortor eget sapien eleifend dignissim. Ut pretium nibh enim, eu cursus eros imperdiet id. Vestibulum aliquet finibus nisl nec blandit. Sed tempor id elit eu aliquet. Sed volutpat sodales maximus. Suspendisse dictum mauris rutrum nisl pretium gravida.".split(".");
        LOREM_IPSUM_INDEX = 1;

        function addContent() {
          const page_content = document.querySelector("#page-content");
          const text_node = document.createTextNode(LOREM_IPSUM_SENTENCES[LOREM_IPSUM_INDEX]);
          const div = document.createElement("div");
          div.appendChild(text_node);
          page_content.appendChild(div);
          LOREM_IPSUM_INDEX = (LOREM_IPSUM_INDEX + 1) % (LOREM_IPSUM_SENTENCES.length - 1);
          // reajust the footer
          adjustFooterCssTopToSticky();
        }

        $(function() {
            $('.btn_url_alerts_profile').click(function () {
              var valorAccion = $("input[name='url_alerts_profile']").val();
              var member_role = '{{ $member_role }}';

              if(member_role == 'teacher'){
                window.location.href = valorAccion+'/#alerts';
              }
              else{
                window.location.href = valorAccion+'/#user_alerts';
              }
            });
        });



    </script>
    @stack('scripts')

    @if(Auth::guard('members')->user())
        <!-- 3. AddChat JS -->
        <!-- Modern browsers -->
        {{-- <script type="module" src="{{ asset('assets/addchat/js/addchat.min.js') }}"></script> --}}
        <!-- Fallback support for Older browsers -->
        {{-- <script nomodule src="{{ asset('assets/addchat/js/addchat-legacy.min.js') }}"></script> --}}
    @endif
  </body>
</html>
