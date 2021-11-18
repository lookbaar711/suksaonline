
  var longanswer = {
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
                    toolbar: " alignleft aligncenter alignright alignjustify",
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
        tinyinit('#choice_'+id);
        function tinyinit(name){
            tinymce.init({
                selector: name,
                menubar:false,
                plugins:'autolink autosave save imagetools quickbars image',
                height : 200,
                toolbar: " alignleft aligncenter alignright alignjustify",
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
            toolbar: " alignleft aligncenter alignright alignjustify",
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
                pplugins:'autolink autosave save imagetools quickbars image',
                height : 200,
                toolbar: " alignleft aligncenter alignright alignjustify",
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
    longanswer.textarea_question('1');
    longanswer.textarea_answer('1');
    longanswer.textarea_editor_question();
    longanswer.textarea_editor_answer();

    // $(document).ready(function() {
    //   $(".wrap_header").hide();
    // });

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    var add_question_text = $(".add_question_text"); //Fields wrapper
      var add_question_button = $(".add_question"); //Add button ID


      var x = 1; //initlal text box count
      var num = 1;
      var article = 100;

      $(add_question_button).click(function(e){ //on add input button click
        // console.log(555);
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
                var article_r = $('.num_question').last().attr('data-id');
                if($('#question_'+article_r+'_ifr').contents().find('body').children('p').children('img').length == 0 && $('#question_'+article_r+'_ifr').contents().find('body').text().trim().length == 0)
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
                }else if($('#score_question_'+article_r).val() == null || $('#score_question_'+article_r).val() == ''){
                    Swal.fire({
                        title: '<strong>'+pleasescore+'</strong>',
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
                    $(this).children().text(btndel)
                    x++; //text box increment
                    var num_question = $('.num_question').length + 1;
                    // console.log(num_question);
                    num = num+1;
                    article = article+1;
                    $(add_question_text).append( `<div class="alert alert-secondary num_question num_`+num_question+`" dataarticle=`+article+` dataquestion=`+num_question+` data-id="`+article+`" role="alert">`+
                    `<label for="exampleInputEmail1 question_num">`+no_number+` <label class="question_num">`+num_question+`</label></label>`+
                    `</br>`+
                    `</br>`+
                    `<div class="form-group">`+
                        `<input name="image_question" type="file" id="question_`+num_question+`_upload" accept=".png, .jpg, .jpeg"  class="hidden" onchange="">`+
                        `<textarea name="question[]" id="question_`+article+`" class="textarea`+num_question+` form-control question editorTiny col-12"></textarea>`+
                    `</div>`+

                    `<div class="form-group">`+
                        `<label >`+score+` :<span class="red">*</span></label>`+
                        `<input type="text"  name="score_question[]" id="score_question_`+article+`" required  class="form-control munber_input" >`+
                    `</div>`+
                    `</br>`+
                    `<button type="button" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question" style="color: white; height: 38px;">`+
                        `<label class="colorz" style="margin-top: 10px;">`+addquestions+`</label>`+
                    `</button>`+
                    `</button>`+
                    `</br>`+
                    `</div>`);

                    longanswer.textarea_question(article);
                    longanswer.textarea_answer(article);
                }

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
                        $('.textarea'+key).addClass('textarea'+num_chang);
                        $('.textarea'+key).toggleClass('textarea'+key);
                        // console.log(key,num_chang);

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
                $('.textarea'+key).addClass('textarea'+num_chang);
                $('.textarea'+key).toggleClass('textarea'+key);
                // console.log(key,num_chang);
                //
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
                    // console.log(key,num_chang);
                    //
                    // console.log($('.num_'+key).children('label').children('label'));

                }

            });
                // console.log($(add_question_text).children());

          $(this).parent('div').remove(); x--;
      })


      $(add_question_text).on("click",".add_question", function(e){ //user click on remove text
          x++; //text box increment
          article_r = $('.num_question').last().attr('data-id');
        //   console.log(444);

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
                if($('#question_'+article_r+'_ifr').contents().find('body').children('p').children('img').length == 0 && $('#question_'+article_r+'_ifr').contents().find('body').text().trim().length == 0)
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
                    }else if($('#score_question_'+article_r).val() == null || $('#score_question_'+article_r).val() == ''){
                        Swal.fire({
                            title: '<strong>'+pleasescore+'</strong>',
                            type: 'error',
                            showCloseButton: false,
                            showCancelButton: false,
                            focusConfirm: false,
                            confirmButtonColor: '#28a745',
                            confirmButtonText: close_window,
                        })
                    }else{
                        var num_question = $('.num_question').length + 1;
                        // var num_question = $('.num_question').length + 1;
                        num = num+1;
                        article = article+1;
                        $(this).removeClass('"btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question');
                        $(this).addClass('btn btn-danger btn-sm col-12 remove_question');
                        $(this).children().text(btndel)
                        $(add_question_text).append( `<div class="alert alert-secondary num_question num_`+num_question+`" dataarticle=`+article+` dataquestion=`+num_question+` data-id="`+article+`" role="alert">`+
                            `<label for="exampleInputEmail1 question_num">`+no_number+` <label class="question_num">`+num_question+`</label></label>`+
                            `</br>`+
                            `</br>`+
                            `<div class="form-group">`+
                                `<input name="image_question" type="file" id="question_`+num_question+`_upload" accept=".png, .jpg, .jpeg"  class="hidden" onchange="">`+
                                `<textarea name="question[]" id="question_`+article+`" class="textarea`+num_question+` form-control question editorTiny col-12"></textarea>`+
                            `</div>`+

                            `<div class="form-group">`+
                                `<label >`+score+` :<span class="red">*</span></label>`+
                                `<input type="text"  name="score_question[]" id="score_question_`+article+`" required  class="form-control munber_input" >`+
                            `</div>`+
                            `</br>`+
                            `<button type="button" class="btn flex-c-m s-text3 bgwhite hov1 trans-0-5 col-12 add_question" style="color: white; height: 38px;">`+
                                `<label class="colorz" style="margin-top: 10px;">`+addquestions+`</label>`+
                            `</button>`+
                            `</button>`+
                            `</br>`+
                            `</div>`);
                            longanswer.textarea_question(article);
                            longanswer.textarea_answer(article);
                    }
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
