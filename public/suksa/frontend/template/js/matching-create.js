
  var matching_extarea = {
      textarea_question:(id) => {
          $('.hidden').hide();
            // console.log(id);
            tinyinit('#question_'+id);

            function tinyinit(name){
                tinymce.init({
                    selector: name,
                    menubar:false,
                     plugins:'autolink autosave save imagetools quickbars image',
                     height : 400,
                    //  toolbar: "fontselect",
                    //  toolbar2: "",
                     toolbar2: " alignleft aligncenter alignright alignjustify",
                    //  toolbar3: " bullist numlist outdent indent  ",
                    //  toolbar5: " image",
                     // enable title field in the Image dialog
                     image_title: true,
                     quickbars_insert_toolbar : 'quickimage',
                     content_style: "body { font-family: Arial; }",
                     // enable automatic uploads of images represented by blob or data URIs
                     automatic_uploads: true,
                     relative_urls : false,
                     remove_script_host : false,
                     convert_urls : true,
                     // add custom filepicker only to Image dialog
                     file_picker_types: 'image',
                    file_picker_callback: function (callback, value, meta) {
                       if (meta.filetype == 'image') {
                         $(name+'_upload').trigger('click');
                         $(name+'_upload').on('change', function () {
                           var file = this.files[0];
                           var reader = new FileReader();
                           var formData = new FormData();
                           // console.log(file);
                           formData.append('picture',this.files[0]);
                           $.ajaxSetup({
                           headers: {
                               'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                           }
                       });
                            $.ajax({
                               type: "post",
                               url: "{!! URL::to('courses/uploadImage/') !!}",
                               data : formData,
                               dataType : 'json',
                               contentType: false,
                               processData: false,
                               success: function (result) {
                                   callback("{{url('storage/courses/test')}}"  + '/' + result.status);
                               }
                               });
                         });
                       }
                     }
                   });
            }
        },
        textarea_answer:(id) => {
            tinyinit('#choice_'+id);
            function tinyinit(name){
                tinymce.init({
                    selector: name,
                    menubar:false,
                     plugins:'preview autolink autosave save directionality visualblocks visualchars fullscreen advlist lists imagetools textpattern quickbars',
                     height : 400,
                     toolbar2: " alignleft aligncenter alignright alignjustify",
                    //  toolbar: "fontselect",
                    //  toolbar2: "fontsizeselect  ",
                    //  toolbar3: "styleselect | bold italic | alignleft aligncenter alignright alignjustify",
                    //  toolbar4: " bullist numlist outdent indent  ",
                     // enable title field in the Image dialog
                     image_title: true,
                     quickbars_insert_toolbar : 'quickimage',
                     content_style: "body { font-family: Arial; }",
                     // enable automatic uploads of images represented by blob or data URIs
                     automatic_uploads: true,
                     relative_urls : false,
                     remove_script_host : false,
                     convert_urls : true,
                     // add custom filepicker only to Image dialog
                     file_picker_types: 'image',
                    file_picker_callback: function (callback, value, meta) {
                       if (meta.filetype == 'image') {
                         $(name+'_upload').trigger('click');
                         $(name+'_upload').on('change', function () {
                           var file = this.files[0];
                           var reader = new FileReader();
                           var formData = new FormData();
                           // console.log(file);
                           formData.append('picture',this.files[0]);
                           $.ajaxSetup({
                           headers: {
                               'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                           }
                       });
                            $.ajax({
                               type: "post",
                               url: "{!! URL::to('courses/uploadImage/') !!}",
                               data : formData,
                               dataType : 'json',
                               contentType: false,
                               processData: false,
                               success: function (result) {
                                   callback("{{url('storage/courses/test')}}"  + '/' + result.status);
                               }
                               });
                         });
                       }
                     }
                   });
            }
        },
        textarea_editor_question:() => {
            setTimeout(function () {
                tinymce.init({
                selector: '.editorTinyquestion',
                menubar:false,
                    plugins:'preview autolink autosave save directionality visualblocks visualchars fullscreen  advlist lists imagetools textpattern quickbars',
                    height : 400,
                    toolbar2: " alignleft aligncenter alignright alignjustify",
                    // toolbar: "fontselect",
                    // toolbar2: "fontsizeselect  ",
                    // toolbar3: "styleselect | bold italic | alignleft aligncenter alignright alignjustify",
                    // toolbar4: " bullist numlist outdent indent  ",
                    // enable title field in the Image dialog
                    image_title: true,
                    quickbars_insert_toolbar : 'quickimage',
                    content_style: "body { font-family: Arial; }",
                    // enable automatic uploads of images represented by blob or data URIs
                    automatic_uploads: true,
                    relative_urls : false,
                    remove_script_host : false,
                    convert_urls : true,
                    // add custom filepicker only to Image dialog
                    file_picker_types: 'image',
                    file_picker_callback: function (callback, value, meta) {
                        if (meta.filetype == 'image') {
                            $(name+'_upload').trigger('click');
                            $(name+'_upload').on('change', function () {
                            var file = this.files[0];
                            var reader = new FileReader();
                            var formData = new FormData();
                            // console.log(file);
                            formData.append('picture',this.files[0]);
                            $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                            $.ajax({
                                type: "post",
                                url: "{!! URL::to('courses/uploadImage/') !!}",
                                data : formData,
                                dataType : 'json',
                                contentType: false,
                                processData: false,
                                success: function (result) {
                                    callback("{{url('storage/courses/test')}}"  + '/' + result.status);
                                }
                                });
                            });
                        }
                    }
                });
            },200);
        },
        textarea_editor_answer:() => {
            setTimeout(function () {
                tinymce.init({
                selector: '.editorTinyanswer',
                menubar:false,
                    plugins:'preview autolink autosave save directionality visualblocks visualchars fullscreen advlist lists imagetools textpattern quickbars',
                    height : 400,
                    toolbar2: " alignleft aligncenter alignright alignjustify",
                    // toolbar: "fontselect",
                    // toolbar2: "fontsizeselect  ",
                    // toolbar3: "styleselect | bold italic | alignleft aligncenter alignright alignjustify",
                    // toolbar4: " bullist numlist outdent indent  ",
                    // enable title field in the Image dialog
                    image_title: true,
                    quickbars_insert_toolbar : 'quickimage',
                    content_style: "body { font-family: Arial; }",
                    // enable automatic uploads of images represented by blob or data URIs
                    automatic_uploads: true,
                    relative_urls : false,
                    remove_script_host : false,
                    convert_urls : true,
                    // add custom filepicker only to Image dialog
                    file_picker_types: 'image',
                    file_picker_callback: function (callback, value, meta) {
                        if (meta.filetype == 'image') {
                            $(name+'_upload').trigger('click');
                            $(name+'_upload').on('change', function () {
                            var file = this.files[0];
                            var reader = new FileReader();
                            var formData = new FormData();
                            // console.log(file);
                            formData.append('picture',this.files[0]);
                            $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                            $.ajax({
                                type: "post",
                                url: "{!! URL::to('courses/uploadImage/') !!}",
                                data : formData,
                                dataType : 'json',
                                contentType: false,
                                processData: false,
                                success: function (result) {
                                    callback("{{url('storage/courses/test')}}"  + '/' + result.status);
                                }
                                });
                            });
                        }
                    }
                });
            },200);
        }
    }

    $(document).on('keyup', '.munber_input', function(event) {

            var input = $(this).val();
                //console.log(input);
                var regex = new RegExp('^[0-9]+$');
                if(regex.test(input)) {
                  }else {
                      $(this).val('');
                  }
        })

    $(function() {
      matching_extarea.textarea_question('1');
      matching_extarea.textarea_answer('1');
      matching_extarea.textarea_editor_question();
      matching_extarea.textarea_editor_answer();

    //   $(document).ready(function() {
    //     $(".wrap_header").hide();
    //   });

      setTimeout(function () {
        $('.date_homework_start').datetimepicker({
             format: 'dd/mm/yyyy',
             minView: "month",
             language: "fr",
             autoclose: true,
         });
      }, 2000);

      setTimeout(function () {
        $('#date_start').datetimepicker({
             format: 'dd/mm/yyyy',
             minView: "month",
             language: "fr",
             autoclose: true,
         });
      }, 2000);

      var add_text_date = $("#add_text_date"); //Fields wrapper
      var add_button = $("#add_datetime"); //Add button ID
      var x = 1; //initlal text box count

      var please_enter_open_datetime = `{{ trans('frontend/courses/title.please_enter_open_datetime') }}`;
      var please_select_valid_open_course_date = `{{ trans('frontend/courses/title.please_select_valid_open_course_date') }}`;
      var please_select_course_time_match = `{{ trans('frontend/courses/title.please_select_course_time_match') }}`;


      $(add_button).click(function(e){ //on add input button click
              x++; //text box increment

              var nowdate = new Date();
              var dd = nowdate.getDate(); //yields day
              var MM = nowdate.getMonth(); //yields month
              var yyyy = nowdate.getFullYear(); //yields year
              var currentDate = dd + "/" +( MM+1) + "/" + yyyy;
              var date_s = $('#date_start').val();
              var time_s = $('#datepicker02').val();
              var time_e = $('#datepicker03').val();



              if (currentDate <= $('#date_start').val()) {
                if ($('#datepicker02').val() <= $('#datepicker03').val()) { // เวลาเริ่มน้อยกว่า เวลาสิ่นสุด

                  $(add_text_date).append( `<div class="form-row text_div_date" >`+
                    `<div class="form-group col-md-4" >`+
                        `<label for="dtp_input1" class="col-md control-label p-l-0">@lang('frontend/courses/title.open_course_date'): <span style="color: red; font-size: 20px;" >*</span></label>`+
                       `<input class="form-control daterange-input date_homework_start" name="date_homework_start[]" id="date_homework_start" value="`+date_s+`" data-format="dd/mm/yyyy" type="text"  placeholder="@lang('frontend/courses/title.select_open_course_date')" autocomplete="off" disabled >`+
                    `</div>`+
                    `<div class="form-group col-md-3">`+
                        `<label for="dtp_input1" class="col-md control-label p-l-0">@lang('frontend/courses/title.start_time') : <span style="color: red; font-size: 20px;" >*</span></label>`+
                        `<div class="input-group date form_datetime col-md-12 p-l-0" style="padding-right: unset;" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">`+
                            `<input class="form-control daterange-input2 time-icon start_time" size="16" id="time_homework_start" type="text"  value="`+time_s+`" placeholder="@lang('frontend/courses/title.select_start_time')" name="time_homework_start[]" autocomplete="off" disabled>`+
                            `<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>`+
                        `</div>`+
                    `</div>`+
                    `<div class="form-group col-md-3">`+
                        `<label for="dtp_input1" class="col-md control-label p-l-0">@lang('frontend/courses/title.end_time') : <span style="color: red; font-size: 20px;" >*</span></label>`+
                        `<div class="input-group date form_datetime col-md-12 p-l-0" style="padding-right: unset;" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">`+
                            `<input class="form-control daterange-input2 time-icon end_time" size="16" id="time_homework_end" type="text"  value="`+time_e+`" placeholder="@lang('frontend/courses/title.select_end_time')" name="time_homework_end[]" autocomplete="off" disabled>`+
                            `<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>`+
                        `</div>`+
                    `</div>`+
                    `<div class="form-group col-md-2 p-t-0" style="padding-left: 0;"  >`+
                        `<label class="profile01  p-t-0 p-b-0" > &nbsp; &nbsp; <span style="color: red; font-size: 20px;" >&nbsp;</span></label>`+
                        `<button type="button" class="btn btn-danger btn-md form-control remove_datetime">@lang('frontend/courses/title.remove_button')</button>`+
                    `</div>`+
                  `</div>`); //add input box

                  $('#date_start').val('');
                  $('#datepicker02').val('');
                  $('#datepicker03').val('');

                }
                else { // เวลาเริ่มมากกว่า เวลาสิ่นสุด
                  //
                }

              }
              else {//วันที่ปัจจุบันมากกว่าวันที่เริ่ม
                Swal.fire({
                    type: 'error',
                    title: please_select_valid_open_course_date,
                    // showCloseButton: true,
                    showCancelButton: false,
                    focusConfirm: false,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: close_window,
                    //text: 'Something went wrong!',
                  })
                  // console.log(now);

                return false;
              }



      });
      $(add_text_date).on("click",".remove_datetime", function(e){ //user click on remove text
          $(this).parent('div').parent('div').remove(); x--;
      })

      //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

      var add_question_text = $(".add_question_text"); //Fields wrapper
      var add_question_button = $(".add_question"); //Add button ID


      var x = 1; //initlal text box count
      var num = 1;
      var article = 100;

      $(add_question_button).click(function(e){ //on add input button click
        if($(this).hasClass('remove_question') == false){
            var thirty = $('.num_question').length;
            if(thirty > 29)
            {
                Swal.fire({
                    title: '<strong>'+assignments_30+'</strong>',
                    type: 'error',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: close_window,
                })
            }else{
                    $(this).removeClass('"btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question');
                    $(this).addClass('btn btn-danger btn-sm col-12 remove_question');
                    $(this).text(btndel)
                    x++; //text box increment
                    var num_question = $('.num_question').length + 1;
                    // console.log(num_question);
                    num = num+1;
                    article = article+1;
                     //add input box
                    $(add_question_text).append(
                        '<div class="alert alert-secondary num_question num_'+num_question+'" dataarticle="'+article+'" data-id="'+article+'" dataquestion="'+num_question+'" role="alert">'+
                            '<div class="row">'+
                                '<div class="col-sm-12">'+
                                    '<div class="row">'+
                                        '<div class="col-sm-1">'+
                                            '<label for="exampleInputEmail1">'+no_number+' <label class="question_num">'+num_question+'</label></label>'+
                                        '</div>'+
                                        '<div class="col-sm-1">'+
                                            '<div class="row">'+
                                                '<input name="score_question['+num_question+']" id="score_question_'+article+'" class="form-control munber_input" type="text" value="">'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-sm-1">'+
                                        '<label >'+score+' <span class="red">*</span></label>'+
                                        '</div>'+
                                        '<div class="col-sm-2">'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                            '<div class="row">'+
                                '<div class="col-sm-5">'+
                                    '<div class="question_answer">'+
                                        '<label class="title_question">'+question+'</label>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-sm-2">'+
                                '</div>'+
                                '<div class="col-sm-5">'+
                                    '<div class="question_answer">'+
                                        '<label class="title_answer">'+response+'</label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                            '<div class="row">'+
                                '<div class="col-sm-5">'+
                                    '<textarea name="question['+num_question+']" id="question_'+article+'" class="editorTinyquestion form-control question editorTiny col-12"></textarea>'+
                                '</div>'+
                                '<div class="col-sm-1">'+
                                    ' <div class="decoration">'+
                                        '<div class="decoration-inside">'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-sm-1">'+
                                    '<div class="answer_desktop">'+
                                        '<input name="answer['+num_question+']" id="answer_'+article+'" class="form-control munber_input matching_answer" type="hidden" value="'+num_question+'">'+
                                    ' </div>'+
                                '</div>'+
                                '<div class="col-sm-5">'+
                                    '<textarea name="choice['+num_question+']" id="choice_'+article+'" class="editorTinyanswer form-control question editorTiny col-12"></textarea>'+
                                '</div>'+
                            '</div>'+
                            '<div class="blank"></div>'+
                                '<div class="row">'+
                                    '<button type="button" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question" style="color: white; height: 38px;">'+addquestions+'</button>'+
                                '</div>'+
                        '</div>'
                    );

                    // $(add_question_text).append( `<div class="alert alert-secondary num_question num_`+num_question+`" dataarticle=`+article+` dataquestion=`+num_question+` data-id="`+article+`" role="alert">`+
                    // `<label for="exampleInputEmail1 question_num">`+no_number+` <label class="question_num">`+num_question+`</label></label>`+
                    // `</br>`+
                    // `</br>`+
                    // `<div class="form-group">`+
                    //     // `<input name="image_question" type="file" id="question_`+num_question+`_upload" accept=".png, .jpg, .jpeg"  class="hidden" onchange="">`+
                    //     `<textarea name="question[`+num_question+`]" id="question_`+article+`" class="form-control question editorTiny col-12"></textarea>`+
                    // `</div>`+

                    // `<div class="form-group">`+
                    //     `<input name="image_question" type="file" id="choice_`+num_question+`_upload" accept=".png, .jpg, .jpeg"  class="hidden" onchange="">`+
                    //     `<textarea name="choice[`+num_question+`]" id="choice_`+article+`" class="form-control question editorTiny col-12"></textarea>`+
                    // `</div>`+

                    // `<div class="form-group">`+
                    //     `<label >`+response+` :<span class="red">*</span></label>`+
                    //     `<input type="text"  name="answer[`+num_question+`]" id="answer_`+article+`" required  class="form-control munber_input" >`+
                    // `</div>`+
                    // `<div class="form-group">`+
                    //     `<label >`+score+` :<span class="red">*</span></label>`+
                    //     `<input type="text"  name="score_question[`+num_question+`]" id="score_question_`+article+`" required  class="form-control munber_input" >`+
                    // `</div>`+
                    // `</br>`+
                    // `<button type="button" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question" style="color: white; height: 38px;">`+
                    //     `<label class="colorz" style="margin-top: 10px;">`+addquestions+`</label>`+
                    // `</button>`+
                    // `</button>`+
                    // `</br>`+
                    // `</div>`); //add input box

                    matching_extarea.textarea_question(article);
                    matching_extarea.textarea_answer(article);
            }

        }else{
            var old_question = $('.boxQueurion').find('.num_question');

            var num = $(this).parent('div').parent('div').attr('dataquestion');

                $.each(old_question, function (indexInArray, valueOfElement) {
                    if(indexInArray > num-2)
                    {
                        var num_chang = indexInArray+1;
                        var key = indexInArray+2;
                        $('.num_'+key).find('.question_num').text(num_chang);
                        $('.num_'+key).find('.matching_answer').val(num_chang);
                        $('.num_'+key).attr('dataquestion',num_chang);
                        $('.num_'+key).addClass('num_'+num_chang);
                        $('.num_'+key).toggleClass('num_'+key);
                        // console.log(key,num_chang);

                        // console.log($('.num_'+key).children('label').children('label'));

                    }

                });
                $(this).parent('div').parent('div').remove(); x--;
        }

      });

      $('.remove_question').click(function(){
        var old_question = $('.boxQueurion').find('.num_question');
        var num = $(this).parent('div').parent('div').attr('dataquestion');
            $.each(old_question, function (indexInArray, valueOfElement) {
                if(indexInArray > num-2)
                {
                    var num_chang = indexInArray+1;
                    var key = indexInArray+2;
                    $('.num_'+key).find('.question_num').text(num_chang);
                    $('.num_'+key).find('.matching_answer').val(num_chang);
                    $('.num_'+key).attr('dataquestion',num_chang);
                    $('.num_'+key).addClass('num_'+num_chang);
                    $('.num_'+key).toggleClass('num_'+key);
                    // console.log(key,num_chang);

                    // console.log($('.num_'+key).children('label').children('label'));

                }

            });
        $(this).parent('div').parent('div').remove(); x--;
      });

      $(add_question_text).on("click",".remove_question", function(e){ //user click on remove text

        var old_question = $('.boxQueurion').find('.num_question');

        var num = $(this).parent('div').parent('div').attr('dataquestion');

            $.each(old_question, function (indexInArray, valueOfElement) {
                if(indexInArray > num-2)
                {
                    var num_chang = indexInArray+1;
                    var key = indexInArray+2;
                    $('.num_'+key).find('.question_num').text(num_chang);
                    $('.num_'+key).find('.matching_answer').val(num_chang);
                    $('.num_'+key).attr('dataquestion',num_chang);
                    $('.num_'+key).addClass('num_'+num_chang);
                    $('.num_'+key).toggleClass('num_'+key);
                    // console.log(key,num_chang);

                    // console.log($('.num_'+key).children('label').children('label'));

                }

            });
                // console.log($(add_question_text).children());

          $(this).parent('div').parent('div').remove(); x--;
      })


      $(add_question_text).on("click",".add_question", function(e){ //user click on remove text
          x++; //text box increment
          article_r = $('.num_question').last().attr('data-id');
        //   console.log(article_r);

            var thirty = $('.num_question').length;
            if(thirty > 29)
            {
                Swal.fire({
                    title: '<strong>'+assignments_30+'</strong>',
                    type: 'error',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: close_window,
                })

            }else{
                    var num_question = $('.num_question').length + 1;
                    var num_question = $('.num_question').length + 1;
                    num = num+1;
                    article = article+1;
                    $(this).removeClass('"btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question');
                    $(this).addClass('btn btn-danger btn-sm col-12 remove_question');
                    $(this).text(btndel)

                    $(add_question_text).append(
                        '<div class="alert alert-secondary num_question num_'+num_question+'" dataarticle="'+article+'" data-id="'+article+'" dataquestion="'+num_question+'" role="alert">'+
                            '<div class="row">'+
                                '<div class="col-sm-12">'+
                                    '<div class="row">'+
                                        '<div class="col-sm-1">'+
                                            '<label for="exampleInputEmail1">'+no_number+' <label class="question_num">'+num_question+'</label></label>'+
                                        '</div>'+
                                        '<div class="col-sm-1">'+
                                            '<div class="row">'+
                                                '<input name="score_question['+num_question+']" id="score_question_'+article+'" class="form-control munber_input" type="text" value="">'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-sm-1">'+
                                        '<label >'+score+' <span class="red">*</span></label>'+
                                        '</div>'+
                                        '<div class="col-sm-2">'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                            '<div class="row">'+
                                '<div class="col-sm-5">'+
                                    '<div class="question_answer">'+
                                        '<label class="title_question">'+question+'</label>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-sm-2">'+
                                '</div>'+
                                '<div class="col-sm-5">'+
                                    '<div class="question_answer">'+
                                        '<label class="title_answer">'+response+'</label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                            '<div class="row">'+
                                '<div class="col-sm-5">'+
                                    '<textarea name="question['+num_question+']" id="question_'+article+'" class="editorTinyquestion form-control question editorTiny col-12"></textarea>'+
                                '</div>'+
                                '<div class="col-sm-1">'+
                                    ' <div class="decoration">'+
                                        '<div class="decoration-inside">'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-sm-1">'+
                                    '<div class="answer_desktop">'+
                                        '<input name="answer['+num_question+']" id="answer_'+article+'" class="form-control munber_input matching_answer" type="hidden" value="'+num_question+'">'+
                                    ' </div>'+
                                '</div>'+
                                '<div class="col-sm-5">'+
                                    '<textarea name="choice['+num_question+']" id="choice_'+article+'" class="editorTinyanswer form-control question editorTiny col-12"></textarea>'+
                                '</div>'+
                            '</div>'+
                            '<div class="blank"></div>'+
                                '<div class="row">'+
                                    '<button type="button" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question" style="color: white; height: 38px;">'+addquestions+'</button>'+
                                '</div>'+
                        '</div>'
                    );
                matching_extarea.textarea_question(article);
                matching_extarea.textarea_answer(article);
            }

      })

    });




    function subject(value){
        var subject_text = `{{ trans('frontend/courses/title.subject') }}`;
        var select_text = `{{ trans('frontend/courses/title.select') }}`;

        if(value!=''){
            var subject =[];
            $.ajax({
              method: 'GET',
              dataType: 'json',
              url: `{{ url('/courses/getsubject') }}`+`/`+value,
              success: function(data) {

                  select = '<label class="profile01">'+subject_text+' : <span style="color: red; font-size: 20px; " >*</span></label><select class="form-control" name="course_subject" id="course_subject" required style="text-align: center; text-align: center;  text-align-last: center; padding-top: 4px;"><option value="" selected readonly >'+select_text+'</option>';
                  var option = '';
                  for(i=0; i<data.length; i++){
                      option += '<option value="'+data[i]['subject_id']+'">'+data[i]['subject_name']+'</option><br>';

                  }
                  document.getElementById("subject").innerHTML = select+option+'</select>';
                  //console.log(data);

              },
              error: function(data) {
                  console.log('error');
              }
            });
        }
        else{
            $('#course_subject').val('');
            $('#course_subject').html('<option value="">'+select_text+'</option>');
        }
    }

    async function footerBottom() {

        await removeFt0();
        windowHeight = $(window).height();
        footerHeight = $('#footer')[0].offsetTop + $('#footer')[0].offsetHeight;

        if (footerHeight <= windowHeight) {
            $("#footer").addClass("footer-0");
        }
    }

    function removeFt0() {
        return new Promise(function(resolve, reject) {
          $("#footer").removeClass("footer-0");
          resolve()
          }).then(function(){
              return true;
          })
    }
