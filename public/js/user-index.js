function resetMultiplePassword(e) {
    var arr = [];
    $('input.check:checkbox:checked').each(function () {
        arr.push($(this).val());
    });
    if (arr.length == 0) {
        alert('No user was selected');
    }
    else
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })

        $.ajax({

            type: 'POST',
            url: '/users/resetmultiplepasswords/',
            data: {arr:arr},
            success: function (data) {
                alert('The passwords has been reset');
                /*console.log(data);*/
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }

}

function search_autocomplete() {
    var term = $('#key').val();
    if (term.length > 0) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })
        var url = "http://127.0.0.1:8000/api/users/autocomplete";
        $.ajax({
            type: 'GET',
            url: url,
            dataType: "json",
            data: {term:term},
            success: function (data) {
                var html = "";
                data.forEach(function(user) {
                    html = html + "<li><a href='{{ url('users') }}" + "/" + user.id + "'>"+ user.name +"</a></li>";
                });
                if (data.length == 0)
                {
                    html = html + "<li>No search results</li>";
                }
                $('#list_item').html(html);
                /*console.log(html);*/
                /* console.log(data);*/
            },
            error: function (data) {
                console.log('Error:', data);
            },
            timeout: 1000 //sets timeout to 1 seconds
        });
    } else
    {
        $('#list_item').html("");
    }

}

$("#message").delay(2000).fadeOut(2000);
