@extends('backend.layouts/default')

{{-- Web site Title --}}
@section('title')
    @lang('admin/subject/title.create')
    @parent
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
        เพิ่มโรงเรียน
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('backend') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('Dashboard')
            </a>
        </li>
        <li><a href="{{ url('backend/school') }}">จัดการโรงเรียน</a></li>
        <li class="active">
            เพิ่มโรงเรียน
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        เพิ่มโรงเรียน
                    </h4>
                </div>
                <div class="panel-body">
                    {!! $errors->first('slug', '<span class="help-block">Another role with same slug exists, please choose another name</span> ') !!}
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form class="form-horizontal" role="form" method="post" action="{{ route('school.store') }}" enctype="multipart/form-data">
                        <!-- CSRF Token -->

                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->
                            first('school_name_th', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                ชื่อโรงเรียนภาษาไทย
                                <span style="color: red; font-size: 15px;">* </span>
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="school_name_th" name="school_name_th" class="form-control" placeholder="ชื่อโรงเรียนภาษาไทย" value="{!! old('school_name_th') !!}" required>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('school_name_th', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('school_name_en', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                ชื่อโรงเรียนภาษาอังกฤษ
                                <span style="color: red; font-size: 15px;">* </span>
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="school_name_en" name="school_name_en" class="form-control" placeholder="ชื่อโรงเรียนภาษาอังกฤษ" value="{!! old('school_name_en') !!}" required>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('school_name_en', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('school_email', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                อีเมลโรงเรียน
                                <span style="color: red; font-size: 15px;">* </span>
                            </label>
                            <div class="col-sm-5">
                                <input type="email" id="school_email" name="school_email" class="form-control" placeholder="อีเมลโรงเรียน" value="{!! old('school_email') !!}" required>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('school_email', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('school_tell', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                เบอร์โทรโรงเรียน
                                <span style="color: red; font-size: 15px;">* </span>
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="school_tell" name="school_tell" class="form-control" placeholder="เบอร์โทรโรงเรียน" value="{!! old('school_tell') !!}" required>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('school_tell', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>

                        <div class="form-group {{ $errors->
                            first('school_logo', 'has-error') }}">
                            <label for="title" class="col-sm-2 control-label">
                                โลโก้โรงเรียน
                                <span style="color: red; font-size: 15px;">* รองรับเฉพาะไพล์ .png, .jpg, .jpeg เท่านั้น</span>
                            </label>
                            <div class="col-sm-5">
                                <input type="file" name="school_logo" value="" accept=".png, .jpg, .jpeg" required>
                            </div>
                            <div class="col-sm-4">
                                {!! $errors->first('school_logo', '<span class="help-block">:message</span> ') !!}
                            </div>
                        </div>





                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-4">
                                <a class="btn btn-danger" href="{{ url('backend/school') }}">
                                    ยกเลิก
                                </a>
                                <button type="submit" class="btn btn-success">
                                    บันทึก
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- row-->
</section>
{{-- @section('js') --}}
  <script type="text/javascript">

      function autoTab(obj){
        /* กำหนดรูปแบบข้อความโดยให้ _ แทนค่าอะไรก็ได้ แล้วตามด้วยเครื่องหมาย
        หรือสัญลักษณ์ที่ใช้แบ่ง เช่นกำหนดเป็น  รูปแบบเลขที่บัตรประชาชน
        4-2215-54125-6-12 ก็สามารถกำหนดเป็น  _-____-_____-_-__
        รูปแบบเบอร์โทรศัพท์ 08-4521-6521 กำหนดเป็น __-____-____
        หรือกำหนดเวลาเช่น 12:45:30 กำหนดเป็น __:__:__
        ตัวอย่างข้างล่างเป็นการกำหนดรูปแบบเลขบัตรประชาชน
        */
          var pattern=new String("___-_-_____-_"); // กำหนดรูปแบบในนี้
          var pattern_ex=new String("-"); // กำหนดสัญลักษณ์หรือเครื่องหมายที่ใช้แบ่งในนี้
          var returnText=new String("");
          var obj_l=obj.value.length;
          var obj_l2=obj_l-1;

          for(i=0;i<pattern.length;i++){
              if(obj_l2==i && pattern.charAt(i+1)==pattern_ex){
                  returnText+=obj.value+pattern_ex;
                  obj.value=returnText;
              }
          }
          if(obj_l>=pattern.length){
              obj.value=obj.value.substr(0,pattern.length);
          }
      }

      // จำกัดการป้อน
      function isNumber(evt) {
          evt = (evt) ? evt : window.event;
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode > 31 && (charCode < 48 || charCode > 57)) {
              return false;
          }
          return true;
      }
  </script>
  {{-- @stop --}}
@stop
