$(document).ready(function(){
    $('.user-row').on('click', function(){
        var userId = $(this).attr('data-user-id');
        $.ajax({
            method: 'post',
            url: '/chat/dialog',
            data: {user_id : userId},
            success: function(res) {
                $('#dialog-container').empty().append(res);
            }
        });
    });

    $('#send-new-message').on('click', function(){
       var dialogId = $(this).closest('.input-group').find('#dialog-id').val();
       var message = $(this).closest('.input-group').find('#new-message').val();
       $.ajax({
           method: 'post',
           url: '/chat/' + dialogId + '/send',
           data: {
               dialog_id : dialogId,
               message: message
           },
           success: function(res) {
               $('#message-container').append(res);
           }
       });
    });
});