@extends('frontend.default')

<?php
    if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
        App::setLocale('en');
    }
    else{
        App::setLocale('th');
    }

    $check_login = Auth::guard('members')->user();
?>

@section('css')
    <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/css/homepage.css') !!}">
@endsection

@section('content')
    <section class="slide1">
        <div class="wrap-slick1">
            <div class="slick1">
              {{-- <div class="item-slick1 item1-slick1" style="background-image: url(suksa/frontend/template/images/banner.jpg);"> --}}
                <div class="item-slick1 item1-slick1" style="background-image: url(suksa/frontend/template/images/banner_covid_2.jpg);">
                    <div class="wrap-content-slide1 sizefull flex-col-c-m p-l-15 p-r-15 p-t-150 p-b-170">
                        {{-- <h2 class="caption1-slide1 p-b-6 animated visible-false m-b-22" data-appear="fadeInUp" style="text-align: center;">
                            LEARN ON YOUR TERMS FROM AN EXPERT TUTOR.
                        </h2>
                         <span class="caption2-slide1  animated visible-false m-b-33" data-appear="fadeInDown" style="text-align: center;">
                            Private, 1-on-1 lessons with the expert instructor of your choice.You decide when to meet,<br> how much to pay, and who you want to work with.
                        </span> --}}
                        <div class="caption1-slide1 p-b-6 animated visible-false">
                          <img src="{!! asset ('/suksa/frontend/template/images/logo123123123.png') !!}" >
                        </div>

                        <h5 class="caption1-slide1 p-b-6 animated visible-false m-b-22" data-appear="fadeInUp" style="text-align: center;">
                            Suksa Online ร่วมมือกับสำนักงานคณะกรรมการส่งเสริมการศึกษาเอกชน
                        </h5>
                         <span class="caption2-slide1  animated visible-false m-b-22" data-appear="fadeInDown" style="text-align: center;">
                          ช่วยชาติผ่านพ้น วิกฤตการณ์ โคโรน่าไวรัส 2019 <br> เปิดให้ใช้ระบบห้องเรียนออนไลน์ ผ่าน Suksa online ฟรี
                        </span>

                        {{-- <div class="wrap-btn-slide1 w-size1 animated visible-false" data-appear="zoomIn" > --}}
                          <input type="text" name="subjects_request" id="subjects_request" value="{{$check_login}}" style="display:none;">
                        {{-- </div> --}}



                        <div class="text-center wrap-btn-slide1 w-size1 animated visible-false" data-appear="zoomIn">
                          @if(Auth::guard('members')->user() == null)
                            <div class="form-group">
                              <a href="https://docs.google.com/forms/d/e/1FAIpQLSeKOQUQEb-K-auFchLd4uJVtFcea2dJmo0xWJDmxO2iFyQXbA/viewform">
                                <button type='button' class='flex-c-m size2 bo-rad-23  bgwhite hov1 trans-0-4 color7'>
                                 <p style='color: white;'>Register</p>
                               </button>
                              </a>
                            </div>
                          @endif

                          {{-- <div class="form-group">
                            @if(Auth::guard('members')->user() == null)
                              <button type='button' id='disabled_request' class='flex-c-m size2 bo-rad-23  bgwhite hov1 trans-0-4 color7' >
                               <i class='fa fa-plus' aria-hidden='true' style='color: white;'></i>&nbsp;
                               <p style='color: white;'>@lang('frontend/members/title.build')</p>
                             </button>
                            @else
                              @if((Auth::guard('members')->user()->member_type =='student') || (Auth::guard('members')->user()->member_role =='student'))
                                <button type='button' class='flex-c-m size2 bo-rad-23  bgwhite hov1 trans-0-4 color7' id='request_subjects'>
                                  <i class='fa fa-plus' aria-hidden='true' style='color: white;'></i>&nbsp;
                                  <p style='color: white;'>@lang('frontend/members/title.build')</p>
                                </button>
                              @endif
                            @endif
                          </div> --}}

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Model Request Subjects --}}
    {{-- @include('frontend.modal_request')
    @include('frontend.modal_request_detal') --}}


    <!-- Group learning -->
    <section class="p-t-50 p-b-65 t-center" >

        <div class="container">

                <div class="row align-items-center">

                        <div class="col-md" style="text-align: left;">
                                <div class="row" >
                                        <div class="col">
                                        <label style="color:  #6ab22a; font-size: 35px; ">Getting Help</label>
                                        <p style="font-size: 25px; font:bold; color: rgb(24, 24, 24);">is easier than you think</p>
                                        </div>
                                </div>

                                <div class="row p-t-20 p-b-10 p-l-1" >
                                        <div class="col"><label class="bigtext"> <labe class="textnumber">1</labe>&nbsp;TELL US WHERE YOU'RE STRUGGLING <p>Connect with experts in more than 300 skills and subjects.</p></label> </div>
                                </div>
                                <div class="row   p-b-20 p-l-1">
                                        <div class="col"> <label class="bigtext"><labe class="textnumber">2</labe>&nbsp;CHOOSE THE TUTOR YOU WANT<p>Find the right fit based on qualifications and hourly rates.</p></label> </div>
                                </div>
                                <div class="row p-l-1">
                                        <div class="col"> <label class="bigtext"> <labe class="textnumber" >3</labe>&nbsp;BOOK YOUR LESSON<p>Tell your tutor when you'd like to meet, and only pay for the time you need.</p></label> </div>
                                </div>

                          </div>
                          <div class="col">
                                <div class="product-grid">
                                     <div class="product-image" >
                                            <img src="{{ asset ('suksa/frontend/template/images/img_index_0201.png') }}"style="background-size: cover;  width: 80%;"  >
                                    </div>
                                </div>
                            </div>
                     </div>
              </div>
    </section>



    <!-- Group learning -->
    <section class="p-t-50 p-b-65 t-center"  style="background-color: #FAFBFD ; background-size: cover; width: 100%;">

        <div class="container">

                <div class="row align-items-center">
                        <div class="col">
                            <div class="product-grid">
                                 <div class="product-image" >
                                <img  src="{{ asset ('suksa/frontend/template/images/img_onlinelesson.png')  }}"style="background-size: cover; width: 100%;" >
                                </div>
                            </div>
                        </div>
                        <div class="col-md" style="text-align: left;">
                                <div class="row" >
                                        <div class="col" style="font-size: 30px;">Online lessons. <label style="color: #6ab22a;">Real-world</label> results.</label>   </div>
                                </div>
                                <div class="row " >
                                        <div class="col"><p style="font-size: 18px;">   Get real results without ever leaving the house.</label>  </div>
                                </div>
                                <div class="row p-t-20 p-b-10 p-l-1" >
                                        <div class="col"><img  src="{{ asset ('suksa/frontend/template/images/icons/img_check01.png') }}" class="sizecheck">&nbsp; Meet with the expert of your choice, anywhere in the country.</div>
                                </div>
                                <div class="row   p-b-20 p-l-1">
                                        <div class="col"><img  src="{{ asset ('suksa/frontend/template/images/icons/img_check01.png') }}" class="sizecheck">&nbsp; Save time and easily fit lessons into your schedule.</div>
                                </div>
                                <div class="row p-l-1">
                                        <div class="col"><img  src="{{ asset ('suksa/frontend/template/images/icons/img_check01.png') }}" class="sizecheck">&nbsp; Collaborate with features built for any skill or subject.</div>
                                </div>

                          </div>
                     </div>
              </div>
    </section>



    <!-- Teachers -->
    <section class="blog p-t-30 p-b-65" >
            <!-- <section id="reviews" class="reviews"> -->
            <div class="container">

                    <div class="row">

                            <div class="container">
                                    <div class="row">
                                      <div class="col-sm-auto">

                                      </div>
                                      <div class="col-sm" style="text-align: center; font-size: 30px;  ">
                                    <label>Learn from the nation's largest community of <label style="color:  #6ab22a;">professional</label> tutors.</label>
                                      </div>
                                      <div class="col-sm-auto">

                                      </div>
                                    </div>
                                  </div>
                                  <div class="container">
                                        <div class="row" style="text-align: center;">
                                          <div class="col-sm">
                                                <labe > <img  src="{{ asset ('suksa/frontend/template/images/icons/img_professional_01.png') }}" class="sizepro"></labe>
                                                <br><br>
                                                <p class="right1">EXPERTS.</p>
                                                <p class="right0">More qualifiled instructors than anywhere else, ready to help</p>
                                          </div>
                                          <div class="col-sm">
                                                <labe > <img  src="{{ asset ('suksa/frontend/template/images/icons/img_professional_02.png') }}" class="sizepro"></labe>
                                                <br><br>
                                                <p class="right1">THE RIGHT FIT.</p>
                                                <p class="right0">Find an expert who suits your needs and learning style</p>
                                          </div>
                                          <div class="col-sm">
                                                <labe > <img  src="{{ asset ('suksa/frontend/template/images/icons/img_professional_03.png') }}" class="sizepro"></labe>
                                                <br><br>
                                                <p class="right1">REAL RESULTS.</p>
                                                <p class="right0">Reach your goals faster with privete, 1-to-1 lessons</p>
                                          </div>
                                        </div>
                                      </div>
                                    </div>

                            </div>
                </section>




    <!-- Group learning -->
    <section class="p-t-50 p-b-65 t-center"  style="background-color: #FAFBFD; background-size: cover; width: 100%;">

            <div class="container">
                    <div class="row align-items-center">

                            <div class="col">

                                    <div class="product-grid">

                                            <div class="product-image"  >

                                                    <img  src="{{ asset ('suksa/frontend/template/images/img_education_guarantee.png')  }}"  style="background-size: cover; width: 100%;">


                                            </div>

                                         </div>

                                 </div>

                            <div class="col-sm" style="text-align: left;">

                                <div class="col">
                                    <label style="font-size: 30px;" >Find the right fit or it's free</label>
                                    <p style="color: black;">We guarantee you'll find the right tutor, or we'll cover the first hour of your lesson.</p>
                                </div>




                              </div>
                          </div>

                  </div>
        </section>





    <!-- Group learning -->
    <section class="p-t-50 p-b-65 t-center">
        <div class="container">
            <table>
                <tr>
                    <td style="display: inline-block; padding: 10px;">
                        <div class="circle-grid">
                            <div class="circle-image">
                                <img src="{{ asset ('suksa/frontend/template/images/group_learning/GroupLearning_01.png') }}">
                            </div>
                        </div>
                        <div class="product-content">
                            <h6 class="title t-center p-b-20">Thai Language</h6>
                        </div>
                    </td>
                    <td style="display: inline-block; padding: 10px;">
                        <div class="circle-grid">
                            <div class="circle-image">
                                <img src="{{ asset ('suksa/frontend/template/images/group_learning/GroupLearning_02.png') }}">
                            </div>
                        </div>
                        <div class="product-content">
                            <h6 class="title t-center p-b-20">Math</h6>
                        </div>
                    </td>
                    <td style="display: inline-block; padding: 10px;">
                        <div class="circle-grid">
                            <div class="circle-image">
                                <img src="{{ asset ('suksa/frontend/template/images/group_learning/GroupLearning_03.png') }}">
                            </div>
                        </div>
                        <div class="product-content">
                            <h6 class="title t-center p-b-20">Science</h6>
                        </div>
                    </td>
                    <td style="display: inline-block; padding: 10px;">
                        <div class="circle-grid">
                            <div class="circle-image">
                                <img src="{{ asset ('suksa/frontend/template/images/group_learning/GroupLearning_04.png') }}">
                            </div>
                        </div>
                        <div class="product-content">
                            <h6 class="title t-center p-b-20">Social</h6>
                        </div>
                    </td>
                    <td style="display: inline-block; padding: 10px;">
                        <div class="circle-grid">
                            <div class="circle-image">
                                <img src="{{ asset ('suksa/frontend/template/images/group_learning/GroupLearning_06.png') }}">
                            </div>
                        </div>
                        <div class="product-content">
                            <h6 class="title t-center p-b-20">English</h6>
                        </div>
                    </td>
                    <td style="display: inline-block; padding: 10px;">
                        <div class="circle-grid">
                            <div class="circle-image">
                                <img src="{{ asset ('suksa/frontend/template/images/group_learning/GroupLearning_00.png') }}">
                            </div>
                        </div>
                        <div class="product-content">
                            <h6 class="title t-center p-b-20">More..</h6>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </section>


    <!-- Teachers -->
    <section class="blog p-t-30 p-b-65" style="background-image: url(suksa/frontend/template/images/banner005.jpg); background-size: cover; width: 100%;">
        <!-- <section id="reviews" class="reviews"> -->
        <div class="container">
            <div class="row">
                @foreach ($members as $i => $member)
                  {{-- @php
                  print_r("<pre>");
                  print_r($member->member_img);
                  print_r("<pre>");
                  @endphp --}}

                  <div class="col-md-4 col-sm-6">
                    <div class="card mb-3">
                        <div class="product-grid">
                          @if($member->member_img || ($member->member_img != null))
                              <a href="{{ url('members/detail/'.$member->id) }}">
                                  <img width="100%"  src="{{ URL::asset('/storage/memberProfile/'.$member->member_img) }}" onerror="this.onerror=null;this.src='suksa/frontend/template/images/icons/blank_image.jpg';" style="background-size: cover; height:350px; object-fit: cover">
                              </a>
                          @else
                              <a href="{{ url('members/detail/'.$member->id) }}">
                                  <img width="100%"   src="{{ asset ('suksa/frontend/template/images/icons/imgprofile_default.jpg') }}" onerror="this.onerror=null;this.src='suksa/frontend/template/images/icons/blank_image.jpg';" style="background-size: cover; height:350px; object-fit: cover">
                              </a>
                          @endif
                            <ul class="social">
                                <li><a href="{{ url('members/detail/'.$member->id) }}" data-tip="รายละเอียด"><i class="fa fa-search"></i></a></li>
                            </ul>
                        </div>
                          <div class="card-body text-center">
                            <h6 class="font-weight-bold mb-2 overflow_index">{{ $member->member_fname." ".$member->member_lname }}</h6>
                            <h6 class="text-secondary mb-2 overflow_index">
                              <a href="{{ url('members/detail/'.$member->id) }}">
                                  <span class="" style="color:#3990f2">{{number_format($member->member_rate_start)." - ".number_format($member->member_rate_end)}}</span> @lang('frontend/members/title.coins_per_hour')
                              </a>

                            </h6>
                          </div>
                    </div>
                </div>

                @endforeach
            </div>


            <div class="container">
                <div class="row p-t-20" style="text-align: right; float: right;">
                    <a href="{{ URL::to('teacher/') }}" class="t-right">@lang('frontend/members/title.show_all_teacher')&nbsp;<i class="fa fa-angle-right p-t-5" aria-hidden="true"></i></a>
                </div>
            </div>

            {{-- <br><br><br><br>
            <div class="container" style="background-color: #111111">
                <div class="row" style="text-align: right; float: right;">
                    <a href="{{ URL::to('coins/refund') }}" class="t-right">การขอคืนเงิน</a>
                </div>
            </div> --}}

        </div>
    </section>

    <section class="blog p-t-20 p-b-20" style="background-color: #3d424f">
        <div class="container-menu-header-v2 p-t-2" style="background-color: #3d424f">
            <div class="row">
                <div class="col-md-2 col-sm-4" style="text-align: right; padding-right: 30px;">
                    <img src="{{ asset ('suksa/frontend/template/images/icons/school_icon.png') }}" style="width: 100%; max-width: 70px; height: auto;">
                </div>
                <div class="col-md-5 col-sm-8" style="text-align: left;">
                    <h4 style="color: #ffffff">@lang('frontend/members/title.guaranteed')</h4>
                    <p style="color: #ffffff">@lang('frontend/members/title.after_end_class')</p>
                    <p style="color: #ffffff">@lang('frontend/members/title.dissatisfied_teaching')</p>
                </div>
                <div class="col-md-4 col-sm-12" style="text-align: right; margin-top: 40px;">
                  <a href="{{ URL::to('coins/refund') }}" class="btn btn-light button-s" style="border-radius: 20px; font-size: 14px">@lang('frontend/members/title.refund_button')</a>
                  <div class="btn-group" >
                    <button type="button" class="btn btn-light button-s dropdown-toggle" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false" style="border-radius: 20px;font-size: 14px;">
                      @lang('frontend/members/title.download_manual')
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left">
                      <button class="dropdown-item" type="button">
                        <a class="dropdown-item p-4" href="{{ URL::to('/download_student_doc') }}" style="padding: 4px!important; padding-left: 15px!important;">
                          @lang('frontend/members/title.student_manual')
                        </a>
                      </button>
                      <button class="dropdown-item" type="button">
                        <a class="dropdown-item p-4" href="{{ URL::to('/download_teacher_doc') }}" style="padding: 4px!important; padding-left: 15px!important;">
                          @lang('frontend/members/title.teacher_manual')
                        </a>
                      </button>

                    </div>
                  </div>

                </div>
            </div>
        </div>
        <div class="wrap_header_mobile" style="background-color: #3d424f">
            <div style="text-align: right; width: 20%; padding-right: 30px;">
                <img src="{{ asset ('suksa/frontend/template/images/icons/school_icon.png') }}" style="width: 100%; max-width: 70px; height: auto;">
            </div>
            <div style="text-align: left; width: 80%;">
                <h4 style="color: #ffffff">@lang('frontend/members/title.guaranteed')</h4>
                <p style="color: #ffffff">@lang('frontend/members/title.after_end_class')</p>
                <p style="color: #ffffff">@lang('frontend/members/title.dissatisfied_teaching')</p>
            </div>
            <div style="text-align: center; width: 100%; margin-top: 10px;">
                <a href="{{ URL::to('coins/refund') }}" class="btn btn-light button-s" style="border-radius: 20px; font-size: 14px">@lang('frontend/members/title.refund_button')</a>
                <div class="btn-group">
                  <button type="button" class="btn btn-light button-s dropdown-toggle" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false" style="border-radius: 20px;font-size: 14px;">
                    @lang('frontend/members/title.download_manual')
                  </button>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left">
                    <button class="dropdown-item" type="button">
                      <a class="dropdown-item p-4" href="{{ URL::to('/download_student_doc') }}" style="padding: 4px!important; padding-left: 15px!important;">
                        @lang('frontend/members/title.student_manual')
                      </a>
                    </button>
                    <button class="dropdown-item" type="button">
                      <a class="dropdown-item p-4" href="{{ URL::to('/download_teacher_doc') }}" style="padding: 4px!important; padding-left: 15px!important;">
                        @lang('frontend/members/title.teacher_manual')
                      </a>
                    </button>
                  </div>
                </div>
            </div>

        </div>
    </section>


    <!-- Banner -->
    {{-- <section class="blog p-t-10">
        <img src="{{ asset ('suksa/frontend/template/images/banner3.png') }}" style="width: 100%; height: 100%;">
    </section> --}}

    <script src="/suksa/frontend/template/js/modal_request.js"></script>

@stop
