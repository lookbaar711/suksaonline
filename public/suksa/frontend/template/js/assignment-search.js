function pagination(data, url) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: window.location.origin + url,
            headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: 'post',
            data: data,
            success: function(data) {
                // console.log(data);
                $('#datas').html(data.datas);
                $('#pagination').html(data.paginator);
            }
        });

      }).then(function(){
          return true;
      })

}

function searachteacher (data, url)
{
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: window.location.origin + url,
            headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: 'post',
            data: data,
            success: function(data) {
                $('#datas').html(data.datas);
                $('#pagination').html(data.paginator);
            }
        });

      }).then(function(){
          return true;
      })
}

