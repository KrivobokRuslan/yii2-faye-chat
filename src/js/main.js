$(document).ready(function(){
    $('.user-row').on('click', function(){
        var userId = $(this).attr('data-user-id');
        $.ajax({
            method: 'post',
            url: '/chat/dialog',
            data: {user_id : userId},
            success: function(res) {
                $('#dialog-container').append(res);
            }
        });
    });
});