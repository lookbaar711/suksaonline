@extends('frontend.default')

<?php
    if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
        App::setLocale('en');
    }
    else{
        App::setLocale('th');
    }
?>

@section('css')
    <link rel="stylesheet" type="text/css" href="{!! asset ('suksa/frontend/template/css/profile.css') !!}">
    <style type="text/css">
        .text-profile-noti{
            color: #6ab22a;
            font-size: 16px;
            font-weight: bold;
        }
        .text-head-coins{
            color: rgb(37, 37, 37);
            font-size: 16px;
        }
        .footer-0 {
          top: unset !important;
          position: absolute !important;
          bottom: 0;
          width: 100%
        }
        td, th {
            white-space: nowrap;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
<script type="text/javascript">
@if(session('imgprofile')=='success')
    var close_window = '{{ trans('frontend/users/title.close_window') }}';
    var change_profile_image_success = '{{ trans('frontend/users/title.change_profile_image_success') }}';

    Swal.fire({
        title: '<strong>'+change_profile_image_success+'</strong>',
        type: 'success',
        imageHeight: 100,
        showCloseButton: true,
        showCancelButton: false,
        focusConfirm: false,
        confirmButtonColor: '#28a745',
        confirmButtonText: close_window,
    });
@endif
</script>
<section class="p-t-20 p-b-50">
  <div class="container">
    <table align="left" >
       <tr>
          <td style="display: inline-block; padding-right: 15px;">
            <div class="circle-grid-profile">
                @if(empty($members->member_img))
                  <img id="myImage" class="circular_image" src="{{ asset ('suksa/frontend/template/images/icons/imgprofile_default.jpg') }}" style="background-size: cover; width: 100%;">
                @else
                  <img id="myImage"  class="circular_image" src="{{ asset ('storage/memberProfile/'.$members->member_img) }}" style="background-size: cover; width: 100%;" >
                @endif
            </div>
          </td>
        </tr>
    </table>
  </div>
</section>
<section>
  <div class="container">
    <div class="col">
        <label class="fontsize478">@lang('frontend/users/title.hello')
          @if(Auth::guard('members')->user()->member_role=="teacher")
            @lang('frontend/layouts/title.teacher')
          @else
            @lang('frontend/layouts/title.you')
          @endif

          {{ $members->member_fname." ".$members->member_lname }}
        </label>
        <div class="row align-items-center">
            <div class="col-sm-auto">
                    <object class="fontstyle02">@lang('frontend/users/title.your_coins')
                        <label class="fontnum">
                          @if($members->member_coins>0)
                            {{ $members->member_coins }}
                          @else
                            0
                          @endif
                          <img src="{{ asset ('suksa/frontend/template/images/icons/Coins.png') }}" >
                        </label>
                    </object>
                </div>
        <div class="col">
        </div>
        <div class="col-sm-auto"  id="sub3" >
                <label class="btn btn-outline-secondary" for="upload-profile">@lang('frontend/users/title.change_profile_image')</label><input id="upload-profile" name='upload_slip' type="file" style="display:none;" required onchange="upfile(this.files[0])">
            <label class="btn btn-outline-secondary " for="editprofile" onclick="edit()">@lang('frontend/users/title.edit_profile')</label>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Group learning -->
<section class="p-t-25 p-b-65">
    <div class="container">
        <div class="tab" role="tabpanel">
            <ul class="nav nav-tabs" id="myTab" role="tablist" style="width: 100%">
                <li class="nav-item">
                    <a class="nav-link user_home @if(!session('active')) active @endif " id="home-tab" data-toggle="tab" href="#user_home" role="tab" aria-controls="profile" aria-selected="true" onclick="setVisibility('sub3', 'inline');";>@lang('frontend/users/title.profile_info')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link user_contact @if(session('active')) active @endif " id="lern-tab" data-toggle="tab" href="#user_lern" role="tab" aria-controls="lern" aria-selected="true"  onclick="setVisibility('sub3', 'none');";>@lang('frontend/users/title.course_register_history')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link user_coins" id="coins-tab" data-toggle="tab" href="#user_coins" role="tab" aria-controls="coins" aria-selected="true"  onclick="setVisibility('sub3', 'none');";>@lang('frontend/users/title.coins_history')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link user_alerts" id="alert-tab" data-toggle="tab" href="#user_alerts" role="tab" aria-controls="alert" aria-selected="true"  onclick="setVisibility('sub3', 'none');";>@lang('frontend/users/title.notification_history')</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link user_request"  id="request-tab" data-toggle="tab" href="#user_request" role="tab" aria-controls="profile" aria-selected="false" onclick="setVisibility('disabled_button', 'none');";>@lang('frontend/users/title.request_history')</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">

                <div class="tab-pane user_dis_home fade show @if(!session('active')) active @endif   " id="user_home" role="tabpanel" aria-labelledby="home-tab" >
                    <form class="box1" action="" id="data_user_profile" method="POST">
                      <input name="_method" type="hidden" value="PUT">
                      <input name="id_user" id="id_user" type="hidden" value="{{$members->id}}">
                        {{ csrf_field() }}
                        <div class="form-row">
                            <label class="form-group col-md-12" style="font-size: 25px; font-weight: bold; color: #65BB34;"><br>@lang('frontend/users/title.profile_info')</label>
                            <div class="form-row col-12">
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4" style="font-size: 16px;">@lang('frontend/users/title.student_email') :</label>
                                    <input type="text" class="form-control" name="" value="{{$members->member_email}}" disabled />
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputEmail4" style="font-size: 16px;">@lang('frontend/users/title.mobile_no') :</label>
                                    <input type="text" disabled class="form-control" name="member_tell" id="member_tell" maxlength="10" onkeypress="return isNumber(event)" value="{{$members->member_tell}}" required>
                                </div>
                            </div>
                            <div class="form-row col-12">
                                <div class="form-group col-md-4">
                                    <label for="inputPassword4"  style="font-size: 16px; ">@lang('frontend/users/title.first_name') :</label>
                                    <input type="text" disabled class="form-control" name="member_fname"  value="{{$members->member_fname}}" id="member_fname" required/>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="inputPassword4"  style="font-size: 16px; ">@lang('frontend/users/title.last_name') :</label>
                                    <input type="text" disabled class="form-control" name="member_lname" id="member_lname" value="{{$members->member_lname}}"  required/>
                                </div>
                            </div>

                            <hr>

                            {{-- ข้อมูลธนาคาร --}}
                            <label class="form-group col-md-12" style="font-size: 25px; font-weight: bold; color: #65BB34;"><br>@lang('frontend/members/title.bank_information')</label>

                            {{-- add form --}}
                            <div class="form-row col-12 bank_edit_form" id="user_option_bank" style="display: none;">
                              <div class="form-row col-12">
                                <div class="col-md-4 col-xs-12">
                                    <label class="col-form-label">  @lang('frontend/members/title.bank')  : </label>
                                    <select name="user_bank_name[]" id="user_bank_name" class="form-control user_bank_name" style="font-size: 16px; padding-top: 4px;" disabled>
                                      <option value="">------ @lang('frontend/users/title.please_select_a_bank') ------</option>
                                      @foreach ($bank_master as $key => $bank)
                                        @if ((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                          <option value="{{ $bank->id }}"> {{ $bank->bank_name_en }}</option>
                                        @else
                                          <option value="{{ $bank->id }}"> {{ $bank->bank_name_th }}</option>
                                        @endif
                                      @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 col-xs-12">
                                  <label class="col-form-label">  @lang('frontend/members/title.account_number') : </label>
                                    <input name="user_account_number" id="user_account_number" class="form-control user_bank_account_number" maxlength="10" onkeypress="return isNumber(event)" type="text" placeholder="@lang('frontend/members/title.account_number')" disabled/>
                                </div>

                                <div class="col-md-1 col-xs-12">
                                  <label class="col-12 col-form-label"><span style="color: red;" >&nbsp; </span></label>
                                  <button type="button" id="btn-option_bank" onclick="user_profile_edit.add_option_user_profile();" class="btn btn-success btn-md btn-add add_bankinformation" disabled>@lang('frontend/users/title.add')</button>
                                </div>
                              </div>
                            </div>

                            {{-- show disabled --}}
                            <div class="form-row col-12 bank_show_form">
                              @php
                                // dd($member_bank,count($member_bank));
                              @endphp
                              @if (!empty($member_bank))
                                @if (count($member_bank) > 0)
                                  @foreach($member_bank as $index => $item)
                                  <div class="form-row col-12">
                                    <div class="col-md-4 col-xs-12">
                                        <label class="col-form-label">  @lang('frontend/members/title.bank')  : </label>
                                        <input name="user_bank_name_id" value="{{$item->bank_id}}" class="form-control user_bank_name" type="text" style="display: none;"/>
                                        @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                            <input class="form-control user_bank" name="user_bank_name[]" value="{{$item->bank_name_en}}" disabled /></input>
                                        @else
                                            <input class="form-control user_bank" name="user_bank_name[]" value="{{$item->bank_name_th}}" disabled /></input>
                                        @endif
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                      <label class="col-form-label">  @lang('frontend/members/title.account_number') : </label>
                                      <input class="form-control user_bank_account_number" name="user_account_number"  value="{{$item->bank_account_number}}" disabled /></input>
                                    </div>
                                  </div>
                                  @endforeach
                                @else
                                  <div class="form-row col-12">
                                    <div class="col-md-4 col-xs-12">
                                        <label class="col-form-label">  @lang('frontend/members/title.bank')  : </label>
                                        <select name="user_bank_name1[]" id="user_bank_name1" class="form-control user_bank_name" style="font-size: 16px; padding-top: 4px;" disabled>
                                          <option value="">------ @lang('frontend/users/title.please_select_a_bank') ------</option>
                                          @foreach ($bank_master as $key => $bank)
                                            @if ((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                              <option value="{{ $bank->id }}"> {{ $bank->bank_name_en }}</option>
                                            @else
                                              <option value="{{ $bank->id }}"> {{ $bank->bank_name_th }}</option>
                                            @endif
                                          @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                      <label class="col-form-label">  @lang('frontend/members/title.account_number') :</label>
                                        <input name="user_account_number1" id="user_account_number1" class="form-control user_bank_account_number" maxlength="10" onkeypress="return isNumber(event)" type="text" placeholder="@lang('frontend/members/title.account_number')" disabled/>
                                    </div>
                                  </div>
                                @endif
                              @endif
                            </div>

                            {{-- edit form --}}
                            <div class="form-row col-12 bank_edit_form" style="display: none;">
                              @foreach($member_bank as $index => $item)
                                <div class="form-row col-12">
                                  <div class="col-md-4 col-xs-12">
                                    <label class="col-form-label">  @lang('frontend/members/title.bank')  :</label>
                                      <input name="user_bank_name_id[]" value="{{$item->bank_id}}" class="form-control user_bank_name" type="text" style="display: none;"/>
                                      @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                        <input name="user_bank_name[]" value="{{$item->bank_name_en}}" class="form-control user_bank_name" type="text" disabled/>
                                      @else
                                        <input name="user_bank_name[]" value="{{$item->bank_name_th}}" class="form-control user_bank_name" type="text" disabled/>
                                      @endif
                                  </div>

                                  <div class="col-md-4 col-xs-12">
                                    <label class="col-form-label">  @lang('frontend/members/title.account_number') : </label>
                                      <input name="user_account_number[]" value="{{$item->bank_account_number}}" class="form-control user_bank_account_number" maxlength="10" onkeypress="return isNumber(event)" type="text" disabled/>
                                  </div>

                                  <div class="col-md-1 col-xs-12">
                                    <label class="col-12 col-form-label">&nbsp; </span></label>
                                        <button type="button" class="btn btn-danger btn-md btn-add remove_bankinformation" onclick="user_profile_edit.remove_bankinformation(this)">@lang('frontend/users/title.delete')</button>
                                  </div>
                                </div>
                              @endforeach
                            </div>

                            <div class="form-row col-12 Add_user_option_bank"></div>

                            <hr>

                            {{-- ข้อมูลโรงเรียน --}}
                            <label class="form-group col-md-12" style="font-size: 25px; font-weight: bold; color: #65BB34;"><br>@lang('frontend/users/modal.school_information')</label>

                            {{-- show ข้อมูลโรงเรียน --}}
                            <div class="form-row col-12 show_school_student" >
                              {{-- @php
                                dd($school,$school_all,!empty($school));
                              @endphp --}}
                              @if (!empty($school))

                                @if (count($school) <= 0)
                                  <div class="form-row col-12">
                                    <div class="col-md-4 col-xs-12">
                                      <label class="col-form-label">  @lang('frontend/users/modal.school')  : </label>
                                      <input name="" value="{{"-"}}" class="form-control " type="text" disabled/>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                      <label class="col-form-label"> @lang('frontend/users/modal.classroom_user') : </label>
                                        <input name="" value="{{"-"}}" class="form-control " type="text" disabled/>
                                    </div>
                                  </div>
                                @else
                                  @foreach($school as $index => $item)
                                    @php
                                      $school_student = collect($item['school_student'])->Where('student_email',$members->member_email);
                                      // print_r('<pre>');
                                      // print_r($school_student);
                                      // print_r('<pre>');
                                      // print_r($members->id);
                                      // print_r('<pre>');
                                      // print_r(collect($item['school_student']));
                                      // print_r('<pre>');
                                      // print_r(collect($item['school_student'])->Where('student_id',$members->id));
                                    @endphp

                                    <div class="form-row col-12">
                                      <div class="col-md-4 col-xs-12">
                                        <label class="col-form-label">  @lang('frontend/users/modal.school')  : </label>
                                        @if ( count($school_student) > 0 )
                                          <input name="school_student" value="{{$item->_id}}" class="form-control school_student" type="text" style="display: none;"/>
                                          @if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                            <input name="school_student" value="{{$item->school_name_en}}" class="form-control school_student" type="text" disabled/>
                                          @else
                                            <input name="school_student" value="{{$item->school_name_th}}" class="form-control school_student" type="text" disabled/>
                                          @endif
                                        @endif
                                      </div>
                                      <div class="col-md-4 col-xs-12">
                                        <label class="col-form-label"> @lang('frontend/users/modal.classroom_user') : </label>
                                        @if ( count($school_student) > 0 )
                                          @foreach ($school_student as $key => $value)
                                            @if (!empty($value['student_classroom']))
                                              <input name="school_student" value="{{$value['student_classroom']}}" class="form-control school_student" type="text" disabled/>
                                            @else
                                              <input name="school_student" value="{{"-"}}" class="form-control school_student" type="text" disabled/>
                                            @endif
                                          @endforeach
                                        @endif
                                      </div>
                                    </div>
                                  @endforeach
                                @endif


                              @else
                                <div class="form-row col-12">
                                  <div class="col-md-8 col-xs-12">
                                    <input name="" value="-" class="form-control " type="text" style="display: none;"/>
                                  </div>
                                </div>
                              @endif
                            </div>

                            {{-- add ข้อมูลโรงเรียน --}}
                            <div class="form-row col-12 add_school_student" style="display: none;">
                              @if (!empty($school[0]['_id']))
                                <div class="form-row col-12">
                                  <div class="col-md-4 col-xs-12">
                                    <label class="col-form-label">  @lang('frontend/users/modal.school')  : </label>
                                    <select name="school_student_name" id="school_student_name" class="form-control school_student_name" style="font-size: 16px; padding-top: 4px;" disabled>
                                      <option value="">------ @lang('frontend/users/modal.please_select_a_school') ------</option>

                                      @if (!empty($school[0]['_id']))
                                        @foreach($school_all as $index => $item)
                                          @if (isset($school[0]['_id']))
                                            @if ($item->_id == $school[0]['_id'])
                                              @if ((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                                <option value="{{ $item->id }}" selected="selected"> {{ $item->school_name_en }}</option>
                                              @else
                                                <option value="{{ $item->id }}" selected="selected"> {{ $item->school_name_th }}</option>
                                              @endif
                                            @endif
                                          @endif
                                        @endforeach
                                        @endif
                                    </select>
                                  </div>
                                  @foreach($school as $index => $item)
                                    @php
                                      $school_student = collect($item['school_student'])->Where('student_email',$members->member_email);
                                    @endphp
                                      <div class="col-md-4 col-xs-12">
                                        <label class="col-form-label"> @lang('frontend/users/modal.classroom_user') : </label>
                                        @if ( count($school_student) > 0 )
                                          @foreach ($school_student as $key => $value)
                                            @if (!empty($value['student_classroom']))
                                              <input name="school_student" value="{{$value['student_classroom']}}" class="form-control school_student" type="text" disabled/>
                                            @else
                                              <input name="school_student" value="{{"-"}}" class="form-control school_student" type="text" disabled/>
                                            @endif
                                          @endforeach
                                        @endif
                                      </div>
                                  @endforeach
                                </div>
                              @else
                                <div class="form-row col-12">
                                  <div class="col-md-4 col-xs-12">
                                    <label class="col-form-label">  @lang('frontend/users/modal.school')  : </label>
                                    <select name="school_student_name" id="school_student_name" class="form-control school_student_name" style="font-size: 16px; padding-top: 4px;">
                                      <option value="">------ @lang('frontend/users/modal.please_select_a_school') ------</option>

                                        @foreach($school_all as $index => $item)
                                          @if ((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en'))
                                            <option value="{{ $item->id }}"> {{ $item->school_name_en }}</option>
                                          @else
                                            <option value="{{ $item->id }}"> {{ $item->school_name_th }}</option>
                                          @endif
                                        @endforeach

                                    </select>
                                  </div>
                                  @foreach($school as $index => $item)
                                    @php
                                      $school_student = collect($item['school_student'])->Where('student_email',$members->member_email);
                                    @endphp
                                      <div class="col-md-4 col-xs-12">
                                        <label class="col-form-label"> @lang('frontend/users/modal.classroom_user') : </label>
                                        @if ( count($school_student) > 0 )
                                          @foreach ($school_student as $key => $value)
                                            @if (!empty($value['student_classroom']))
                                              <input name="school_student" value="{{$value['student_classroom']}}" class="form-control school_student" type="text" disabled/>
                                            @else
                                              <input name="school_student" value="{{"-"}}" class="form-control school_student" type="text" disabled/>
                                            @endif
                                          @endforeach
                                        @endif
                                      </div>
                                  @endforeach
                                </div>
                              @endif

                            </div>

                        </div>
                        <br><br>
                      <div class="col-md-12" >
                          <div class="container">
                              <div class="row">
                                  <div class="col-sm"></div>
                                  <div class="col-sm">
                                      <button type="button" id="submit" onclick="user_profile_edit.users_update();" class=" flex-c-m size2 bo-rad-23 s-text3 bgwhite hov1 trans-0-5 col-12"
                                      style="display: none;"><object class="colorz">@lang('frontend/users/title.edit_button')</object></button>
                                  </div>
                                  <div class="col-sm"></div>
                              </div>
                          </div>
                      </div>
                    </form>
                </div>

                <div class="tab-pane user_dis_contact fade show @if(session('active')) active @endif " id="user_lern" role="tabpanel" aria-labelledby="lern-tab">
                  <div class="form-row">
                    <div class="container" id="user_page_contact"></div>
                  </div>

                  <div class="btn-group pull-right">
                    <nav aria-label="Page navigation example">
                       <ul class="pagination user_page_contact" id="user_page_num">
                      </ul>
                    </nav>
                  </div>
                </div>

                <div class="tab-pane user_dis_coins fade" id="user_coins" role="tabpanel" aria-labelledby="coins-tab">
                  <div class="form-row">
                    <div class="container">
                      <div class="form-row">
                        <div class="container" id="user_page_coins"></div>
                      </div>
                      <div class="btn-group pull-right">
                        <nav aria-label="Page navigation example">
                           <ul class="pagination user_page_coins" id="user_coins_page_num">
                          </ul>
                        </nav>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="tab-pane user_dis_alerts fade" id="user_alerts" role="tabpanel" aria-labelledby="alert-tab">
                  <div class="form-row">
                    <div class="container">
                      <div class="form-row">
                        <div class="container" id="user_page_alerts"></div>
                      </div>

                      <div class="btn-group pull-right">
                        <nav aria-label="Page navigation example">
                           <ul class="pagination user_page_alerts" id="user_alerts_page_num"></ul>
                        </nav>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade user_dis_request" id="user_request" role="tabpanel" aria-labelledby="request-tab">
                  <div class="container">
                    <div class="form-row">
                      <div class="container" id="user_page_request"></div>
                    </div>

                      <div class="btn-group pull-right">
                        <nav aria-label="Page navigation example">
                           <ul class="pagination user_page_request" id="user_alerts_page_request"></ul>
                        </nav>
                      </div>
                  </div>
                </div>

        </div>
    </div>
</section>
<script src="/suksa/frontend/template/js/user_profile.js"></script>
<script>

var user_profile_edit = {

  add_option_user_profile: function (){
    if ($('#user_bank_name').val() == "" || $('#user_account_number').val() == "" ) {
      Swal.fire({
          title: '<strong> @lang('frontend/users/title.please_select_a_bank') </strong>',
          type: 'info',
          imageHeight: 100,
          showCloseButton: true,
          showCancelButton: false,
          focusConfirm: false,
          confirmButtonColor: '#28a745',
          confirmButtonText: close_window,
      });
    }else {
      var stringb = `<div class="form-row col-12">
                      <div class="col-md-4 col-xs-12">
                        <label class="col-form-label">  @lang('frontend/members/title.bank')  : <span style="color: red;" >* </span></label>
                          <input name="user_bank_name_id[]" value="`+$('#user_bank_name option:selected').val()+`" class="form-control user_bank_name" type="text" style="display: none;"/>
                          <input name="user_bank_name[]" value="`+$('#user_bank_name option:selected').text()+`" class="form-control user_bank_name" type="text" disabled/>
                      </div>

                      <div class="col-md-4 col-xs-12">
                        <label class="col-form-label">  @lang('frontend/members/title.account_number') : <span style="color: red;" >* </span></label>
                          <input name="user_account_number[]" value="`+$('input[name="user_account_number"]').val()+`" class="form-control user_bank_account_number" maxlength="10" onkeypress="return isNumber(event)" type="text" disabled/>
                      </div>

                      <div class="col-md-1 col-xs-12">
                        <label class="col-12 col-form-label"><span style="color: red; " >&nbsp; </span></label>
                            <button type="button" class="btn btn-danger btn-md btn-add remove_bankinformation" onclick="user_profile_edit.remove_bankinformation(this)">@lang('frontend/users/title.delete')</button>
                      </div>
                    </div>`;

      $('.Add_user_option_bank').append(stringb);

      $('#user_bank_name').val('');
      $('#user_account_number').val('');
    }
  },

  remove_bankinformation: (e) => {
      $(e).parent('div').parent('div').remove();
  },

  users_update:() => {
    var member_bank = {};
    var bank_name=[];
      $('[name="user_bank_name_id[]"]').each(function() {
       bank_name.push($(this).val());
      });

    var bank_account_number=[];
     $('[name="user_account_number[]"]').each(function() {
      bank_account_number.push($(this).val());
     });

     if (bank_account_number.length > 0) {
       for (var i = 0; i < bank_name.length; i++) {
         member_bank[i] = [bank_name[i],bank_account_number[i]];
       }
     }


     var data_user = {
       'id' : $('#id_user').val(),
       "member_fname" : $('#member_fname').val(),
       "member_lname" : $('#member_lname').val(),
       "member_tell" : $('#member_tell').val(),
       "member_school" : $('select[name="school_student_name"] option:selected').val(),
       'member_bank' : member_bank,
     }
     // console.log(bank_account_number.length);
     // console.log(bank_account_number);

     if (bank_account_number.length <= 0) {
       // console.log(22);
       Swal.fire({
           title: '<strong> @lang('frontend/layouts/title.please_select_bank') </strong>',
           type: 'warning',
           imageHeight: 100,
           showCloseButton: true,
           showCancelButton: false,
           focusConfirm: false,
           confirmButtonColor: '#28a745',
           confirmButtonText: close_window,
       });
     }else {
       $.ajax({
         url: window.location.origin + '/users/update_profile' ,
         headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         type: 'post',
         data: {
           'data': data_user,
         },
         dataType: "json",
         success: function(data) {
            if(data.success){
                Swal.fire({
                    title: '<strong>'+data.success+'</strong>',
                    type: 'success',
                    showConfirmButton: false,
                    timer: 500
                });

                setTimeout(function () {
                    location.reload();
                }, 1000);
            }else{
                Swal.fire({
                title: '<strong>'+data.error+'</strong>',
                type: 'error',
                showConfirmButton: true,
                showCancelButton: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
                })
            // .then((result) => {
            //     if (result.value) {
            //         location.reload();
            //     }
            // })
            }
         }
       });
     }
  },

}

function edit(){

  var bank=[];
   $('[name="user_account_number[]"]').each(function() {
    bank.push($(this).val());
   });

   // console.log(bank);

    if(document.getElementById('member_tell').disabled === true){
        document.getElementById('member_tell').disabled = false;
        document.getElementById('member_fname').disabled = false;
        document.getElementById('member_lname').disabled = false;
        document.getElementById('submit').style.display = "block";
        // console.log(1);
        $('.bank_show').hide();
        if (bank) {
          $('#user_option_bank').hide();
        }else {
          $('#user_option_bank').show();
        }
        // console.log("เปิด");

        $('.bank_edit_form').show();
        $('.bank_show_form').hide();
        $('.remove_bankinformation').show();
        $( "#user_bank_name" ).prop( "disabled", false );
        $( "#user_account_number" ).prop( "disabled", false );
        $( "#btn-option_bank" ).prop( "disabled", false );
        $('.show_school_student').hide();
        $('.add_school_student').show();

    }
    else{
      // console.log(bank.length);
      // console.log("ปิด");
        document.getElementById('member_tell').disabled = true;
        document.getElementById('member_fname').disabled = true;
        document.getElementById('member_lname').disabled = true;
        document.getElementById('submit').style.display = "none";

        $('.bank_show').show();
        if (bank.length > 0) {
          $('#user_option_bank').hide();
        }else {
          $('#user_option_bank').show();
        }
        $('.remove_bankinformation').hide();
        $( "#user_bank_name" ).prop( "disabled", true );
        $( "#user_account_number" ).prop( "disabled", true );
        $( "#btn-option_bank" ).prop( "disabled", true );
        $('.show_school_student').show();
        $('.add_school_student').hide();


        // $('.bank_edit_form').hide();
        // $('.bank_show_form').show();
    }

}
function show2(){
    document.getElementById('course_price').disabled = true;
    document.getElementById('course_price').disabled = true;
    document.getElementById('course_price').disabled = false;
}

function upfile(file){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var formData = new FormData();
    formData.append('img', file);

    var close_window = '{{ trans('frontend/users/title.close_window') }}';
    var uploading_profile_image = '{{ trans('frontend/users/title.uploading_profile_image') }}';
    var uploading_profile_image_message = '{{ trans('frontend/users/title.uploading_profile_image_message') }}';
    var upload_profile_image_error = '{{ trans('frontend/users/title.upload_profile_image_error') }}';

    Swal.fire({
        title: uploading_profile_image,
        html: uploading_profile_image_message,
        timer: 100000,
        onBeforeOpen: () => {
            Swal.showLoading()
            timerInterval = setInterval(() => {
                    //.textContent = Swal.getTimerLeft()
            }, 100)
        },
        onClose: () => {
            clearInterval(timerInterval)
        }
        }).then((result) => {
            if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.timer
            ) {
                console.log('I was closed by the timer')
            }
        })
    $.ajax({
    url: "/imgprofile",
    data: formData,
    contentType: false,
    cache: false,
    processData: false,
    type: "POST",
        success: function(data) {
            location.href = '{{route("users.imgprofile")}}';
            //location.reload();
            //document.getElementById('myImage').src = window.URL.createObjectURL(file)
        },
        error: function(data) {
          Swal.fire({
                title: '<strong>'+upload_profile_image_error+'</strong>',
                type: 'false',
                imageHeight: 100,
                showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            });
        }
    });
}
$(function() {
  $( "#open_model_user_profile_request" ).click(function() {
     $("#model_user_profile_request").modal('show');
  });

  $( "#close_model_user_profile_request2" ).click(function() {
     $("#model_user_profile_request").modal('toggle');
  });

  //Disable cut copy paste
  $('#alert').bind('cut copy paste', function (ee) {
      ee.preventDefault();
  });

  //Disable mouse right click
  $("#alert").on("contextmenu",function(ee){
      return false;
  });

});
</script>

<!-- ปิดปุ่มแก้ไชเมื่อเลือก tab -->
<script language="JavaScript">
function setVisibility(id, visibility) {
// document.getElementById(id).style.display = visibility;
}
</script>
<!-- ปิดปุ่มแก้ไชเมื่อเลือก tab -->
@include('frontend.users.alert')
@stop
