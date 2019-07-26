$(document).ready(function(){
    $(document).on('click', '.user-row', function(){
        var userId = $(this).attr('data-user-id');
        $('#user-' + userId).find('.income-message').hide();
        $.ajax({
            method: 'post',
            url: '/chat/dialog',
            data: {user_id : userId},
            success: function(res) {
                $('#dialog-container').empty().append(res);
                $('#message-container').animate({
                    scrollTop: $('#message-container').find('.direct-chat-msg:last-child').offset().top + 'px'
                }, 'fast');
            }
        });
    });

    $(document).on('click', '.group-row', function(){
        var roomId = $(this).attr('data-room-id');
        $.ajax({
            method: 'get',
            url: '/chat/room/' + roomId,
            success: function(res) {
                $('#dialog-container').empty().append(res);
                $('#message-container').animate({
                    scrollTop: $('#message-container').find('.direct-chat-msg:last-child').offset().top + 'px'
                }, 'fast');
            }
        });
    });

    $(document).on('click', '#send-new-message', function(){
       var dialogId = $(this).closest('.input-group').find('#dialog-id').val();
       var message = $(this).closest('.input-group').find('#new-message').val();
       $(this).closest('.input-group').find('#new-message').val('');
       $.ajax({
           method: 'post',
           url: '/chat/' + dialogId + '/send',
           data: {
               dialog_id : dialogId,
               message: message
           },
           success: function(res) {
               $('#message-container').append(res);
               $('#message-container').animate({
                   scrollTop: $('#message-container').find('.direct-chat-msg:last-child').offset().top + 'px'
               }, 'fast');
           }
       });
    });

    $(document).on('click', '#room-send-new-message', function(){
        var roomId = $(this).closest('.input-group').find('#room-id').val();
        var message = $(this).closest('.input-group').find('#new-message').val();
        $(this).closest('.input-group').find('#new-message').val('');
        $.ajax({
            method: 'post',
            url: '/room/' + roomId + '/send',
            data: {
                room_id : roomId,
                message: message
            },
            success: function(res) {
                $('#message-container').append(res);
                $('#message-container').animate({
                    scrollTop: $('#message-container').find('.direct-chat-msg:last-child').offset().top + 'px'
                }, 'fast');
            }
        });
    });

    $(document).on('submit', '#roomCreateForm', function() {
        $.ajax({
            url: '/chat/room',
            type: 'post',
            data: $(this).serialize(),
            success: function(response) {
                $('#group-container').append(response.roomInListTemplate);
                $('#createRoomModal').modal('hide');
                $('#roomCreateForm').get(0).reset();
            }
        });
        return false;
    });
});