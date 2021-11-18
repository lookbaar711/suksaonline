
  var multiplechoice_extarea = {

    textarea_question:(id) => {
        $('.hidden').hide();
          // console.log(id);
          tinyinit('#question_'+id);

          function tinyinit(name){
              tinymce.init({
                    selector: name,
                    menubar:false,
                    plugins:'autolink autosave save imagetools quickbars image',
                    height : 200,
                    toolbar: "alignleft aligncenter alignright alignjustify",
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

    textarea_answer:(id) => {
        tinyinit('#answer_'+id);
        function tinyinit(name){
            tinymce.init({
                selector: name,
                menubar:false,
                plugins:'autolink autosave save imagetools quickbars image',
                height : 200,
                toolbar: "alignleft aligncenter alignright alignjustify",
            //  toolbar: "fontselect",
            //  toolbar2: "fontsizeselect  ",
            //  toolbar3: "styleselect | bold italic | alignleft aligncenter alignright alignjustify",
            //  toolbar4: " bullist numlist outdent indent  ",
                // enable title field in the Image dialog
                image_title: true,
                quickbars_insert_toolbar : 'quickimage',
                content_style: "body { font-family: Arial; }",
                automatic_uploads: true,
                relative_urls : false,
                remove_script_host : false,
                convert_urls : true,
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
                plugins:'autolink autosave save imagetools quickbars image',
                height : 200,
                toolbar: "alignleft aligncenter alignright alignjustify",
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
            // tinymce.init({
            // selector: '.editorTinyquestion',
            // menubar:false,
            // plugins:'autolink autosave save imagetools quickbars image',
            // height : 200,
            // toolbar: "alignleft aligncenter alignright alignjustify",
            // image_title: true,
            // quickbars_insert_toolbar : 'quickimage',
            // content_style: "body { font-family: Arial; }",
            // automatic_uploads: true,
            // relative_urls : false,
            // remove_script_host : false,
            // convert_urls : true,
            // file_picker_types: 'image',
            // file_picker_callback: function (callback, value, meta) {
            //     if (meta.filetype == 'image') {
            //         $(name+'_upload').trigger('click');
            //         $(name+'_upload').on('change', function () {
            //         var file = this.files[0];
            //         var reader = new FileReader();
            //         var formData = new FormData();
            //         // console.log(file);
            //         formData.append('picture',this.files[0]);
            //         $.ajaxSetup({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            //         }
            //     });
            //         $.ajax({
            //             type: "post",
            //             url: "{!! URL::to('courses/uploadImage/') !!}",
            //             data : formData,
            //             dataType : 'json',
            //             contentType: false,
            //             processData: false,
            //             success: function (result) {
            //                 callback("{{url('storage/courses/test')}}"  + '/' + result.status);
            //             }
            //             });
            //         });
            //     }
            // }
            // });
        },200);
    },

    up_quiz_file:() => {
        let quiz_file = $("#quiz_file").val();
        var Please_attach_a_test_file = "{{ trans('frontend/courses/title.Please_attach_a_test_file') }}";
        if(quiz_file == ""){
          Swal.fire({
            type: 'info',
            title: Please_attach_a_test_file,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: "ตกลง",
          })
        }else {
          Swal.showLoading();
          var form_data = new FormData();
          form_data.append('file', $('#quiz_file')[0].files[0]);
          $.ajax({
            url: window.location.origin + "/homework/upload/Up_multiplechoice/",
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'POST',
            success:function(data) {
                // console.log(data);
            Swal.close();
            var add_question_text = $(".add_question_text");
            var n = 0;
            var x = 1; //initlal text box count
            var num = 1;
            var article = 200;
            var i = 1;
            var Radios1 ='';
            var Radios2 ='';
            var Radios3 ='';
            var Radios4 ='';
            $('.num_question').remove();


            $.each(data ,function(key, value) {
                var num_question = $('.num_question').length + 1;

                if (num_question <= 30) {
                    article = article+1;


                if (value.question_true == "1") {
                    Radios1 = `<input class="form-check-input question" type="radio" name="answer`+num+`"  id="gridRadios1`+article+`" value="1" checked required>`;
                }
                else{
                    Radios1 = `<input class="form-check-input question" type="radio" name="answer`+num+`"  id="gridRadios1`+article+`" value="1" required>`;
                }
                if (value.question_true == "2") {
                    Radios2 = `<input class="form-check-input question" type="radio" name="answer`+num+`"  id="gridRadios2`+article+`" value="2" checked required>`;
                }
                else{
                    Radios2 = `<input class="form-check-input question" type="radio" name="answer`+num+`"  id="gridRadios2`+article+`" value="2" required>`;
                }

                if (value.question_true == "3") {
                    Radios3 = `<input class="form-check-input question" type="radio" name="answer`+num+`"  id="gridRadios3`+article+`" value="3" checked required>`;
                }
                else{
                    Radios3 = `<input class="form-check-input question" type="radio" name="answer`+num+`"  id="gridRadios3`+article+`" value="3" required>`;
                }

                if (value.question_true == "4") {
                    Radios4 = `<input class="form-check-input question" type="radio" name="answer`+num+`" id="gridRadios4`+article+`" value="4" checked required>`;
                }
                else{
                    Radios4 = `<input class="form-check-input question" type="radio" name="answer`+num+`" id="gridRadios4`+article+`" value="4" required>`;
                }


                $(add_question_text).append( `<div class="alert alert-secondary num_question num_`+num_question+`" dataarticle=`+article+` dataquestion=`+num_question+` role="alert">`+
                    `<label for="exampleInputEmail1 question_num">`+no_number+` <label class="question_num">`+num_question+`</label></label>`+
                    `</br>`+
                    `</br>`+
                    `<div class="form-group">`+
                        `<input name="image_question" type="file" id="question_`+num_question+`_upload" accept=".png, .jpg, .jpeg"  class="hidden" onchange="">`+
                        `<textarea name="question[]" id="question_`+article+`" class="textarea`+num_question+` form-control question editorTiny col-12">`+value.question+`</textarea>`+
                    `</div>`+
                    `<div class="form-group">`+
                    `<div class="row">`+
                        `<div class="form-row col-md-6">`+
                            `<div class="col-1">`+
                            `<div class="form-check">`+
                                Radios1
                                +`<label class="form-check-label" for="gridRadios1`+article+`">A</label>`+
                            `</div>`+
                            `</div>`+
                            `<div class="form-group col-md-10">`+
                            `<input type="text" name="answer_texta[]"  class="form-control question"value="`+value[0].answer_a+`" autocomplete="off" placeholder="ตัวเลือกที่ 1" required>`+
                            `</div>`+
                        `</div>`+
                        `<div class="form-row col-md-6">`+
                            `<div class="col-1">`+
                            `<div class="form-check">`+
                                Radios2
                                +`<label class="form-check-label" for="gridRadios2`+article+`">B</label>`+
                            `</div>`+
                            `</div>`+
                            `<div class="form-group col-md-10">`+
                            `<input type="text" name="answer_textb[]" class="form-control question" value="`+value[0].answer_b+`" autocomplete="off" placeholder="ตัวเลือกที่ 2" required>`+
                            `</div>`+
                        `</div>`+
                        `</div>`+
                        `<div class="row">`+
                        `<div class="form-row col-md-6">`+
                            `<div class="col-1">`+
                            `<div class="form-check">`+
                                Radios3
                                +`<label class="form-check-label" for="gridRadios3`+article+`">C</label>`+
                            `</div>`+
                            `</div>`+
                            `<div class="form-group col-md-10">`+
                            `<input type="text" name="answer_textc[]" class="form-control question" value="`+value[0].answer_c+`" autocomplete="off" placeholder="ตัวเลือกที่ 3" required>`+
                            `</div>`+
                        `</div>`+
                        `<div class="form-row col-md-6">`+
                            `<div class="col-1">`+
                            `<div class="form-check">`+
                                Radios4
                                +`<label class="form-check-label" for="gridRadios4`+article+`">D</label>`+
                            `</div>`+
                            `</div>`+
                            `<div class="form-group col-md-10">`+
                            `<input type="text" name="answer_textd[]" class="form-control question" value="`+value[0].answer_d+`" autocomplete="off" placeholder="ตัวเลือกที่ 4" required>`+
                            `</div>`+
                        `</div>`+
                        `</div>`+
                    `</div>`+
                    `<div class="form-group">`+
                        `<label >`+score+` : <span style="color: red; font-size: 20px;" >*</span></label>`+
                        `<input type="text"  name="score_question[]" value="`+value.score+`" required  class="form-control munber_input" >`+
                    `</div>`+
                    `</br>`+
                    `<button type="button" class="btn btn-danger btn-sm col-12 remove_question" id="btn_remove_add`+i+`" style="color: white; height: 38px;">`+
                        `<label class="colorz" style="margin-top: 5px;">`+btndel+`</label>`+
                    `</button>`+
                    `</button>`+
                    `</br>`+
                    `</div>`); //add input box

                        multiplechoice_extarea.textarea_question(article);
                        multiplechoice_extarea.textarea_answer(article);

                        // console.log("#gridRadios1"+article+"","#gridRadios2"+article+"","#gridRadios3"+article+"","#gridRadios4"+article+"");


                        // $('#question_ifr').contents().find('body').text(value.question);


                        if ( i == data.length ) {
                            $("#btn_remove_add"+i+"").addClass('add_question');
                            $("#btn_remove_add"+i+"").removeClass('btn btn-danger btn-sm col-12 remove_question');
                            $("#btn_remove_add"+i+"").addClass('btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question');
                            $("#btn_remove_add"+i+"").children().text(addquestions);
                        }

                        i++;
                        num++;
                }

            });

            $("#quiz_file").val("");

            },
          });
        }
    },

    up_quiz_edit_file:() => {
        let quiz_file = $("#quiz_file").val();
        var Please_attach_a_test_file = "{{ trans('frontend/courses/title.Please_attach_a_test_file') }}";
        if(quiz_file == ""){
          Swal.fire({
            type: 'info',
            title: Please_attach_a_test_file,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: "ตกลง",
          })
        }else {
          Swal.showLoading();
          var form_data = new FormData();
          form_data.append('file', $('#quiz_file')[0].files[0]);
          $.ajax({
            url: window.location.origin + "/homework/upload/Up_multiplechoice/",
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'POST',
            success:function(data) {
                // console.log(data);
            Swal.close();
            var add_question_text = $(".add_question_text");
            var n = 0;
            var x = 1; //initlal text box count
            var num = 0;
            var article = 300;
            var i = 1;
            var Radios1 ='';
            var Radios2 ='';
            var Radios3 ='';
            var Radios4 ='';
            var num_id = $('.num_question').length;


            $("#btn_remove_add"+num_id+"").removeClass('"btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question');
            $("#btn_remove_add"+num_id+"").addClass('btn btn-danger btn-sm col-12 remove_question');
            $("#btn_remove_add"+num_id+"").children().text(btndel)

            $.each(data ,function(key, value) {
                // console.log(1212);



                if (value[0].answer_a) {

                var num_question = $('.num_question').length + 1;

                    if (num_question <= 30) {
                        article = article+1;

                        if (value.question_true == "1") {
                            Radios1 = `<input class="form-check-input question" type="radio" name="answer`+num_question+`"  id="gridRadios1`+article+`" value="1" checked required>`;
                        }
                        else{
                            Radios1 = `<input class="form-check-input question" type="radio" name="answer`+num_question+`"  id="gridRadios1`+article+`" value="1" required>`;
                        }
                        if (value.question_true == "2") {
                            Radios2 = `<input class="form-check-input question" type="radio" name="answer`+num_question+`"  id="gridRadios2`+article+`" value="2" checked required>`;
                        }
                        else{
                            Radios2 = `<input class="form-check-input question" type="radio" name="answer`+num_question+`"  id="gridRadios2`+article+`" value="2" required>`;
                        }

                        if (value.question_true == "3") {
                            Radios3 = `<input class="form-check-input question" type="radio" name="answer`+num_question+`"  id="gridRadios3`+article+`" value="3" checked required>`;
                        }
                        else{
                            Radios3 = `<input class="form-check-input question" type="radio" name="answer`+num_question+`"  id="gridRadios3`+article+`" value="3" required>`;
                        }

                        if (value.question_true == "4") {
                            Radios4 = `<input class="form-check-input question" type="radio" name="answer`+num_question+`" id="gridRadios4`+article+`" value="4" checked required>`;
                        }
                        else{
                            Radios4 = `<input class="form-check-input question" type="radio" name="answer`+num_question+`" id="gridRadios4`+article+`" value="4" required>`;
                        }


                        $(add_question_text).append( `<div class="alert alert-secondary num_question num_`+num_question+`" dataarticle=`+article+` dataquestion=`+num_question+` role="alert">`+
                            `<label for="exampleInputEmail1 question_num">`+no_number+` <label class="question_num">`+num_question+`</label></label>`+
                            `</br>`+
                            `</br>`+
                            `<div class="form-group">`+
                                `<input name="image_question" type="file" id="question_`+num_question+`_upload" accept=".png, .jpg, .jpeg"  class="hidden" onchange="">`+
                                `<textarea name="question[]" id="question_`+article+`" class="textarea`+num_question+` form-control question editorTiny col-12">`+value.question+`</textarea>`+
                            `</div>`+
                            `<div class="form-group">`+
                            `<div class="row">`+
                                `<div class="form-row col-md-6">`+
                                    `<div class="col-1">`+
                                    `<div class="form-check">`+
                                        Radios1
                                        +`<label class="form-check-label" for="gridRadios1`+article+`">A</label>`+
                                    `</div>`+
                                    `</div>`+
                                    `<div class="form-group col-md-10">`+
                                    `<input type="text" name="answer_texta[]"  class="form-control question"value="`+value[0].answer_a+`" autocomplete="off" placeholder="ตัวเลือกที่ 1" required>`+
                                    `</div>`+
                                `</div>`+
                                `<div class="form-row col-md-6">`+
                                    `<div class="col-1">`+
                                    `<div class="form-check">`+
                                        Radios2
                                        +`<label class="form-check-label" for="gridRadios2`+article+`">B</label>`+
                                    `</div>`+
                                    `</div>`+
                                    `<div class="form-group col-md-10">`+
                                    `<input type="text" name="answer_textb[]" class="form-control question" value="`+value[0].answer_b+`" autocomplete="off" placeholder="ตัวเลือกที่ 2" required>`+
                                    `</div>`+
                                `</div>`+
                                `</div>`+
                                `<div class="row">`+
                                `<div class="form-row col-md-6">`+
                                    `<div class="col-1">`+
                                    `<div class="form-check">`+
                                        Radios3
                                        +`<label class="form-check-label" for="gridRadios3`+article+`">C</label>`+
                                    `</div>`+
                                    `</div>`+
                                    `<div class="form-group col-md-10">`+
                                    `<input type="text" name="answer_textc[]" class="form-control question" value="`+value[0].answer_c+`" autocomplete="off" placeholder="ตัวเลือกที่ 3" required>`+
                                    `</div>`+
                                `</div>`+
                                `<div class="form-row col-md-6">`+
                                    `<div class="col-1">`+
                                    `<div class="form-check">`+
                                        Radios4
                                        +`<label class="form-check-label" for="gridRadios4`+article+`">D</label>`+
                                    `</div>`+
                                    `</div>`+
                                    `<div class="form-group col-md-10">`+
                                    `<input type="text" name="answer_textd[]" class="form-control question" value="`+value[0].answer_d+`" autocomplete="off" placeholder="ตัวเลือกที่ 4" required>`+
                                    `</div>`+
                                `</div>`+
                                `</div>`+
                            `</div>`+
                            `<div class="form-group">`+
                                `<label >`+score+` : <span style="color: red; font-size: 20px;" >*</span></label>`+
                                `<input type="text"  name="score_question[]" value="`+value.score+`" required  class="form-control munber_input" >`+
                            `</div>`+
                            `</br>`+
                            `<button type="button" class="btn btn-danger btn-sm col-12 remove_question" id="btn_remove_add`+num_question+`" style="color: white; height: 38px;">`+
                                `<label class="colorz" style="margin-top: 5px;">@lang('frontend/courses/title.btndel')</label>`+
                            `</button>`+
                            `</button>`+
                            `</br>`+
                            `</div>`); //add input box

                                multiplechoice_extarea.textarea_question(article);
                                multiplechoice_extarea.textarea_answer(article);

                                // console.log("#gridRadios1"+article+"","#gridRadios2"+article+"","#gridRadios3"+article+"","#gridRadios4"+article+"");


                                // $('#question_ifr').contents().find('body').text(value.question);

                            //    console.log(i,data.length,num_question);

                                if ( i == data.length ) {
                                    $("#btn_remove_add"+num_question+"").addClass('add_question');
                                    $("#btn_remove_add"+num_question+"").removeClass('btn btn-danger btn-sm col-12 remove_question');
                                    $("#btn_remove_add"+num_question+"").addClass('btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question');
                                    $("#btn_remove_add"+num_question+"").children().text(addquestions);
                                }

                                i++;
                                num++;
                    }
                    else{

                        Swal.fire({
                            title: '<strong>'+assignments_30+'</strong>',
                            type: 'error',
                            showCloseButton: false,
                            showCancelButton: false,
                            focusConfirm: false,
                            confirmButtonColor: '#28a745',
                            confirmButtonText: close_window,
                        })

                        return false;
                    }


                }


              });
              $("#quiz_file").val("");
            },
          });
        }
    },

  }

  $(document).on('keyup', '.munber_input', function(event) {
      // $('.inputnumber').keyup(function(){
          var input = $(this).val();
              //console.log(input);
              var regex = new RegExp('^[0-9]+$');
              if(regex.test(input)) {
                }else {
                    $(this).val('');
                }
  })

  $(function() {
    multiplechoice_extarea.textarea_question('1');
    multiplechoice_extarea.textarea_answer('1');
    multiplechoice_extarea.textarea_editor_question();
    multiplechoice_extarea.textarea_editor_answer();

    // $(document).ready(function() {
    //   $(".wrap_header").hide();
    // });

    var add_question_text = $(".add_question_text"); //Fields wrapper
    var add_question_button = $(".add_question"); //Add button ID


    var x = 1; //initlal text box count

    var article = 100;


    $(add_question_button).click(function(e){ //on add input button click
        // console.log(5555);

        var input = true;
        var score_question = true;
        if (input == true) {
            $('[name="answer_texta[]"]').each(function() {
                if ($(this).val() == "") {
                    input = false;
                }
            });
        }
        if (input == true) {
            $('[name="answer_textb[]"]').each(function() {
                if ($(this).val() == "") {
                    input = false;
                }
            });
        }
        if (input == true) {
            $('[name="answer_textc[]"]').each(function() {
                if ($(this).val() == "") {
                    input = false;
                }
            });
        }
        if (input == true) {
            $('[name="answer_textd[]"]').each(function() {
                if ($(this).val() == "") {
                    input = false;
                }
            });
        }


        $('[name="score_question[]"]').each(function() {
            if ($(this).val() == "") {
                score_question = false;
            }
        });





      if($(this).hasClass('remove_question') == false){

          for (var i = 1; i <= $('.num_question').length; i++) {
            console.log('[name="answer'+i+'"]:checked',22);
            if ($('[name="answer'+i+'"]:checked').val() == undefined) {
              Swal.fire({
                  title: '<strong>'+please_select_an_answer+'</strong>',
                  type: 'error',
                  showCloseButton: false,
                  showCancelButton: false,
                  focusConfirm: false,
                  confirmButtonColor: '#28a745',
                  confirmButtonText: close_window,
              })
              return false;
            }
          }
          if($('#question_1_ifr').contents().find('body').text().trim().length == 0)
          {
            Swal.fire({
                title: '<strong>'+pleasequestion+'</strong>',
                type: 'error',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            })
            return false;
          }
          else if(input == false){
            Swal.fire({
                title: '<strong>'+pleaseanswer+'</strong>',
                type: 'error',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            })
            return false;
          }
          else if(score_question == false ){
            Swal.fire({
                title: '<strong>'+pleasescore+'</strong>',
                type: 'error',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            })
            return false;
          }
          else{
              $(this).removeClass('"btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question');
              $(this).addClass('btn btn-danger btn-sm col-12 remove_question');
              $(this).children().text(btndel)
              x++; //text box increment
              var num_question = $('.num_question').length + 1;
              article = article+1;
              // console.log(num_question,22222);
            $(add_question_text).append( `<div class="alert alert-secondary num_question num_`+num_question+`" dataarticle=`+article+` dataquestion=`+num_question+` role="alert">`+
                `<label for="exampleInputEmail1 question_num">`+no_number+` <label class="question_num">`+num_question+`</label></label>`+
                `</br>`+
                `</br>`+
                `<div class="form-group">`+
                    `<input name="image_question" type="file" id="question_`+num_question+`_upload" accept=".png, .jpg, .jpeg"  class="hidden" onchange="">`+
                    `<textarea name="question[]" id="question_`+article+`" class="textarea`+num_question+` form-control question editorTiny col-12"></textarea>`+
                `</div>`+
                `<div class="form-group">`+
                `<div class="row">`+
                    `<div class="form-row col-md-6">`+
                        `<div class="col-1">`+
                        `<div class="form-check">`+
                            `<input class="form-check-input question" type="radio" name="answer`+(num_question)+`" id="gridRadios1`+num_question+`" value="1" required>`+
                            `<label class="form-check-label" for="gridRadios1`+num_question+`">A</label>`+
                        `</div>`+
                        `</div>`+
                        `<div class="form-group col-md-10">`+
                        `<input type="text" name="answer_texta[]"  class="form-control question"  autocomplete="off" placeholder="ตัวเลือกที่ 1" required>`+
                        `</div>`+
                    `</div>`+
                    `<div class="form-row col-md-6">`+
                        `<div class="col-1">`+
                        `<div class="form-check">`+
                            `<input class="form-check-input question" type="radio" name="answer`+(num_question)+`" id="gridRadios2`+num_question+`" value="2" required>`+
                            `<label class="form-check-label" for="gridRadios2`+num_question+`">B</label>`+
                        `</div>`+
                        `</div>`+
                        `<div class="form-group col-md-10">`+
                        `<input type="text" name="answer_textb[]" class="form-control question"  autocomplete="off" placeholder="ตัวเลือกที่ 2" required>`+
                        `</div>`+
                    `</div>`+
                    `</div>`+
                    `<div class="row">`+
                    `<div class="form-row col-md-6">`+
                        `<div class="col-1">`+
                        `<div class="form-check">`+
                            `<input class="form-check-input question" type="radio" name="answer`+(num_question)+`" id="gridRadios3`+num_question+`" value="3" required>`+
                            `<label class="form-check-label" for="gridRadios3`+num_question+`">C</label>`+
                        `</div>`+
                        `</div>`+
                        `<div class="form-group col-md-10">`+
                        `<input type="text" name="answer_textc[]" class="form-control question"  autocomplete="off" placeholder="ตัวเลือกที่ 3" required>`+
                        `</div>`+
                    `</div>`+
                    `<div class="form-row col-md-6">`+
                        `<div class="col-1">`+
                        `<div class="form-check">`+
                            `<input class="form-check-input question" type="radio" name="answer`+(num_question)+`" id="gridRadios4`+num_question+`" value="4" required>`+
                            `<label class="form-check-label" for="gridRadios4`+num_question+`">D</label>`+
                        `</div>`+
                        `</div>`+
                        `<div class="form-group col-md-10">`+
                        `<input type="text" name="answer_textd[]" class="form-control question" autocomplete="off" placeholder="ตัวเลือกที่ 4" required>`+
                        `</div>`+
                    `</div>`+
                    `</div>`+
                `</div>`+
                `<div class="form-group">`+
                    `<label >`+score+` : <span style="color: red; font-size: 20px;" >*</span></label>`+
                    `<input type="text"  name="score_question[]" required  class="form-control munber_input" >`+
                `</div>`+
                `</br>`+
                `<button type="button" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question" style="color: white; height: 38px;">`+
                    `<label class="colorz" style="margin-top: 5px;">`+addquestions+`</label>`+
                `</button>`+
                `</button>`+
                `</br>`+
                `</div>`); //add input box

                multiplechoice_extarea.textarea_question(article);
                multiplechoice_extarea.textarea_answer(article);

          }


      }else{
          var old_question = $('.boxQueurion').find('.num_question');

          var num = $(this).parent('div').attr('dataquestion');

              $.each(old_question, function (indexInArray, valueOfElement) {
                  if(indexInArray > num-2)
                  {
                      var num_chang = indexInArray+1;
                      var key = indexInArray+2;
                      $('.num_'+key).children('label').children('label').text(num_chang);
                      $('.num_'+key).attr('dataquestion',num_chang);
                      $('.num_'+key).addClass('num_'+num_chang);
                      $('.num_'+key).toggleClass('num_'+key);
                      console.log(key,num_chang,777777);
                      if ($("input[name='answer"+key+"']").length == 4) {
                        $("input[name='answer"+key+"']").attr('name', 'answer'+(num_chang));
                      }else {
                        $("input[name='answer"+key+"']").attr('name', 'answer'+(num_chang-1));
                      }

                      // console.log($('.num_'+key).children('label').children('label'));

                  }

              });
          $(this).parent('div').remove(); x--;
      }

    });

    $('.remove_question').click(function(){
        var old_question = $('.boxQueurion').find('.num_question');
        var num = $(this).parent('div').attr('dataquestion');
        $.each(old_question, function (indexInArray, valueOfElement) {
            if(indexInArray > num-2)
            {
                var num_chang = indexInArray+1;
                var key = indexInArray+2;
                $('.num_'+key).children('label').children('label').text(num_chang);
                $('.num_'+key).attr('dataquestion',num_chang);
                $('.num_'+key).addClass('num_'+num_chang);
                $('.num_'+key).toggleClass('num_'+key);
                // console.log(key,num_chang,999999);
                if ($("input[name='answer"+key+"']").length == 4) {
                  $("input[name='answer"+key+"']").attr('name', 'answer'+(num_chang));
                }else {
                  $("input[name='answer"+key+"']").attr('name', 'answer'+(num_chang-1));
                }

                // console.log($('.num_'+key).children('label').children('label'));

            }

        });
        $(this).parent('div').remove(); x--;


    });

    $(add_question_text).on("click",".remove_question", function(e){ //user click on remove text

        var old_question = $('.boxQueurion').find('.num_question');

        var num = $(this).parent('div').attr('dataquestion');

            $.each(old_question, function (indexInArray, valueOfElement) {
                if(indexInArray > num-2)
                {
                    var num_chang = indexInArray+1;
                    var key = indexInArray+2;
                    $('.num_'+key).children('label').children('label').text(num_chang);
                    $('.num_'+key).attr('dataquestion',num_chang);
                    $('.num_'+key).addClass('num_'+num_chang);
                    $('.num_'+key).toggleClass('num_'+key);
                    // console.log(key,num_chang,88888888,"input[name='answer"+num_chang+"']");

                    if ($("input[name='answer"+key+"']").length == 4) {
                      $("input[name='answer"+key+"']").attr('name', 'answer'+(num_chang));
                    }else {
                      $("input[name='answer"+key+"']").attr('name', 'answer'+(num_chang-1));
                    }

                    // if (true) {
                    //   $("input[name='answer"+num_chang"']").attr('name', 'answer'+num_chang);
                    // }

                    // console.log($('.num_'+key).children('label').children('label'));

                }

            });

            // var num_on = $('.num_question').length;
            // var num_on_text = $('.num_question').length+1;
            // for (var i = 0; i < num_on; i++) {
            //   $("input[name='answer"+i+"']").attr('name', 'answer'+num_on_text);
            // }
                // console.log($(add_question_text).children());

          $(this).parent('div').remove(); x--;
      })



    $(add_question_text).on("click",".add_question", function(e){ //user click on remove text
        // console.log(554);


        var articles = article;
        var num_question = $('.num_question').length + 1;
        article = article+1;
        var input = true;
        var score_question = true;
        var check = true;
        var text_total = $('.num_question').length;

        if(text_total > 30){
            Swal.fire({
                title: '<strong>'+assignments_30+'</strong>',
                type: 'error',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            })
        }


        if (input == true) {
            $('[name="answer_texta[]"]').each(function() {
                if ($(this).val() == "") {
                    input = false;
                }
            });
        }
        if (input == true) {
            $('[name="answer_textb[]"]').each(function() {
                if ($(this).val() == "") {
                    input = false;
                }
            });
        }
        if (input == true) {
            $('[name="answer_textc[]"]').each(function() {
                if ($(this).val() == "") {
                    input = false;
                }
            });
        }
        if (input == true) {
            $('[name="answer_textd[]"]').each(function() {
                if ($(this).val() == "") {
                    input = false;
                }
            });
        }


        $('[name="score_question[]"]').each(function() {
            if ($(this).val() == "") {
                score_question = false;
                return false;
            }
        });

        var num_remove = (articles + (num_question-1));
        var atricle_r = $(this).parent('div').attr('dataarticle');


        // console.log(articles,num_question,article,num_remove);
        if ($('#question_'+atricle_r+'_ifr').contents().find('body').text().trim().length == 0) {
            check = false;
        }
        if(input == false){
            check = false;
        }
        // if(score_question == false){
        //     check = false;
        // }

        for (var i = 1; i <= $('.num_question').length; i++) {
          console.log('[name="answer'+i+'"]:checked',11);
          if ($('[name="answer'+i+'"]:checked').val() == undefined) {
            Swal.fire({
                title: '<strong>'+please_select_an_answer+'</strong>',
                type: 'error',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            })
            return false;
          }
        }

        if (score_question == false) {
            Swal.fire({
                title: '<strong>'+pleasescore+'</strong>',
                type: 'error',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: close_window,
            })
            return false;
        }
        // console.log('[name="answer'+(num_question-1)+'"]:checked');
        // if ($('[name="answer'+(num_question-1)+'"]:checked').val()) {
        //   Swal.fire({
        //       title: '<strong>'+pleasequestion+'</strong>',
        //       type: 'error',
        //       showCloseButton: false,
        //       showCancelButton: false,
        //       focusConfirm: false,
        //       confirmButtonColor: '#28a745',
        //       confirmButtonText: close_window,
        //   })
        //   return false;
        // }

        if (check != true) {
            if (score_question == false) {
                Swal.fire({
                    title: '<strong>'+pleasescore+'</strong>',
                    type: 'error',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: close_window,
                })
            }
            else{
                Swal.fire({
                    title: '<strong>'+pleasequestion+'</strong>',
                    type: 'error',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: close_window,
                })
            }

            return false;
        }
        else {

            // var num_question = $('.num_question').length + 1;
            $(this).removeClass('"btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question');
            $(this).addClass('btn btn-danger btn-sm col-12 remove_question');
            $(this).children().text(btndel)
            x++; //text box increment
            var num_question = $('.num_question').length + 1;
            // console.log(num_question,1111);

            $(add_question_text).append( `<div class="alert alert-secondary  num_question num_`+num_question+`" dataarticle=`+article+` dataquestion=`+num_question+` role="alert">`+
              `<label for="exampleInputEmail1">`+no_number+`  <label class="question_num">`+num_question+`</label></label>`+
              `</br>`+
              `</br>`+
              `<div class="form-group">`+
              `<input name="image_question" type="file" id="question_`+num_question+`_upload" accept=".png, .jpg, .jpeg"  class="hidden" onchange="">`+
              `<textarea name="question[]" id="question_`+article+`" class="textarea`+num_question+` form-control question editorTiny col-12"></textarea>`+
              `</div>`+

              `<div class="form-group">`+
                `<div class="row">`+
                    `<div class="form-row col-md-6">`+
                        `<div class="col-1">`+
                        `<div class="form-check">`+
                            `<input class="form-check-input question" type="radio" name="answer`+(num_question)+`" id="gridRadios1`+num_question+`" value="1" required>`+
                            `<label class="form-check-label" for="gridRadios1`+num_question+`">A</label>`+
                        `</div>`+
                        `</div>`+
                        `<div class="form-group col-md-10">`+
                        `<input type="text" name="answer_texta[]"  class="form-control question"  autocomplete="off" placeholder="ตัวเลือกที่ 1" required>`+
                        `</div>`+
                    `</div>`+
                    `<div class="form-row col-md-6">`+
                        `<div class="col-1">`+
                        `<div class="form-check">`+
                            `<input class="form-check-input question" type="radio" name="answer`+(num_question)+`" id="gridRadios2`+num_question+`" value="2" required>`+
                            `<label class="form-check-label" for="gridRadios2`+num_question+`">B</label>`+
                        `</div>`+
                        `</div>`+
                        `<div class="form-group col-md-10">`+
                        `<input type="text" name="answer_textb[]" class="form-control question"  autocomplete="off" placeholder="ตัวเลือกที่ 2" required>`+
                        `</div>`+
                    `</div>`+
                    `</div>`+
                    `<div class="row">`+
                    `<div class="form-row col-md-6">`+
                        `<div class="col-1">`+
                        `<div class="form-check">`+
                            `<input class="form-check-input question" type="radio" name="answer`+(num_question)+`" id="gridRadios3`+num_question+`" value="3" required>`+
                            `<label class="form-check-label" for="gridRadios3`+num_question+`">C</label>`+
                        `</div>`+
                        `</div>`+
                        `<div class="form-group col-md-10">`+
                        `<input type="text" name="answer_textc[]" class="form-control question"  autocomplete="off" placeholder="ตัวเลือกที่ 3" required>`+
                        `</div>`+
                    `</div>`+
                    `<div class="form-row col-md-6">`+
                        `<div class="col-1">`+
                        `<div class="form-check">`+
                            `<input class="form-check-input question" type="radio" name="answer`+(num_question)+`" id="gridRadios4`+num_question+`" value="4" required>`+
                            `<label class="form-check-label" for="gridRadios4`+num_question+`">D</label>`+
                        `</div>`+
                        `</div>`+
                        `<div class="form-group col-md-10">`+
                        `<input type="text" name="answer_textd[]" class="form-control question" autocomplete="off" placeholder="ตัวเลือกที่ 4" required>`+
                        `</div>`+
                    `</div>`+
                    `</div>`+
                `</div>`+
              `<div class="form-group">`+
              `<label >`+score+` : <span style="color: red; font-size: 20px;" >*</span></label>`+
              `<input type="text"  name="score_question[]"  class="form-control munber_input" >`+
              `</div>`+
              `</br>`+
              `<button type="button" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question" style="color: white; height: 38px;">`+
              `<label class="colorz" style="margin-top: 5px;">`+addquestions+`</label>`+
              `</button>`+
              `</br>`+
          `</div>`); //add input box

          multiplechoice_extarea.textarea_question(article);
          multiplechoice_extarea.textarea_answer(article);
        }
    })

  });



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
