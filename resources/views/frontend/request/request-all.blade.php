@extends('frontend/default')

@section('content')
  @php
  if((isset(Auth::guard('members')->user()->member_lang) && (Auth::guard('members')->user()->member_lang=='en')) || (session('lang')=='en')){
      App::setLocale('en');
  }
  else{
      App::setLocale('th');
  }
  @endphp
  <style>
  .form-inline {
      display: unset;
      flex-flow: unset;
      align-items: unset;
  }
</style>
  <section class="p-t-50 p-b-65">
      <div class="container">
          <div class="form-row">
              <div class="col-sm-12">
                <input type="hidden" name="id_request" value="{{$request_id}}">
                <h4>@lang('frontend/members/title.there_are') <t style="color:#6ab22a;"> {{ $teacher_detail_sum }} @lang('frontend/members/title.teacher')</t> @lang('frontend/members/title.there_are_teacher')</h4>
              </div>
          </div>
          <hr>


          <table class="table table-bordered" id="table_request"></table>

      </div>
  </section>
{{-- <script type="text/javascript" src="https://dev.suksa.online/suksa/frontend/template/vendor/jquery/jquery-3.2.1.min.js"></script> --}}
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<script>
    $(document).ready( function () {
      setTimeout(function () {
        // tableList: function () {
            $('#table_request').DataTable({
              ordering: true,
              processing: true,
              serverSide: true,
              bLengthChange: false,
              bInfo: false,
              bAutoWidth: false,
              bFilter: true,
              scrollX: true,
              language:
                {
                    paginate:
                    {
                        previous: "«",
                        next: "»",
                    }
                } ,
                ajax: {
                    "url": window.location.origin + '/request/request_detail/',
                    "type": "post",
                    "data": {
                      'id' : $("input[name='id_request']").val(),
                    },
                    "headers": {
                        'X-CSRF-TOKEN': $('input[name=_token]').val()
                    },
                    // success: function(data, textStatus, jqXHR)
                    // {
                    //     console.log(data); //*** returns correct json data
                    // }
                },
                columns: [
                  { title: 'รูปอาจารย์', data: function(d) {
                    if (d.member_img =="" || (d.member_img)) {
                      return `<img src="`+ window.location.origin + `/suksa/frontend/template/images/icons/blank_image.jpg" class="mr-3 rounded-circle" style="width: 100px; height: 100px;" onerror="this.onerror=null;this.src='http://localhost:8000/suksa/frontend/template/images/icons/blank_image.jpg';">`;
                    }
                    else {
                      return `<img src="`+ window.location.origin + `/storage/memberProfile/`+ d.member_img +`" class="mr-3 rounded-circle" style="width: 100px; height: 100px;" onerror="this.onerror=null;this.src='http://localhost:8000/suksa/frontend/template/images/icons/blank_image.jpg';">`;
                    }
                  },name: 'member_fullname', width: "10%"},
                  { title: 'ประวัติ', data: function(d) {

                    if (d.member_nickname) {
                      var nickname = d.member_nickname;
                    }else {
                      var nickname = " ";
                    }

                    if (d.online_status == '1') {
                      var online_status = `<p class="btn btn-sm" style="background: #7ED103; border-radius: 25px; font-size: 12px; color: white;">`+'@lang('frontend/users/title.Online')'+`</p>`;
                    }
                    else {
                      var online_status = `<p class="btn btn-sm" style="background: #BCBCBC; border-radius: 25px; font-size: 12px; color: white;">`+'@lang('frontend/users/title.Offline')'+`</p>`;
                    }


                    return `<h5 class="mt-0 mb-1">`+ d.member_fname+` `+d.member_lname +` `+nickname+` `+ online_status+` </h5>`+
                    `<h6 class="mt-0 mb-1"><p class="showteacher"><img  class="p-b-5 " style="width: 20px; " src="`+ window.location.origin + `/suksa/frontend/template/images/icons/ico_03.png">`+` `+ d.member_email +`</h6>`+
                    `<h6 class="mt-0 mb-1"><p class="showteacher"><img  class="p-b-5 " style="width: 20px; " src="`+ window.location.origin + `/suksa/frontend/template/images/icons/ico_07.png">`+` `+
                    `@lang('frontend/members/title.teaching_rate')`+` `+ (d.member_rate_start*1).toLocaleString(undefined, {minimumFractionDigits: 0}) +` - `+(d.member_rate_end*1).toLocaleString(undefined, {minimumFractionDigits: 0}) +` @lang('frontend/members/title.coins_hour')</h6>`;
                  }, name: 'member_lname', width: "80%"},
                  { title: '#', data: function (d) {
                    return `<a href="`+ window.location.origin + `/members/detail/`+ d.member_id +`">`+
                              `<button class="btn btn-outline-dark button-s btn-sm" style="border-radius: 25px;" >`+
                                `<img  class="p-b-5 " style="width: 20px; " src="`+ window.location.origin + `/suksa/frontend/template/images/icons/ico_06.png">`+` `+'@lang('frontend/members/title.teacher_information')'+` `+
                              `</button></a>`;
                  }, name: 'member_lname' ,width: "10%"},
                ]
            });

        // },

      }, 1000);
    } );

    $(function () {
      setTimeout(function () {
        $('.input-sm').attr('autocomplete', 'off');
        $('#table_request_filter').attr('autocomplete', 'off');
        $('#table_request_filter').hide();
      }, 1000);

    })
</script>

@stop
