$(document).ready(function(){
    $(document).on('click', '.user-row', function(){
        $('.user-row').each(function(i, el) {
            $(el).removeClass('active');
        });
        $('.group-row').each(function(i, el) {
            $(el).removeClass('active');
        });
        $('.nav-tabs .nav-item').each(function(i, el) {
            $(el).removeClass('active');
        });
        $('.nav-tabs .nav-item:first-child').addClass('active');
        $('#group-container').removeClass('active in');
        $('#user-container').addClass('active in');
        var userId = $(this).attr('data-user-id');
        $('#user-' + userId).addClass('active');
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

    $(document).on('click', '.room-leave', function() {
       var roomId = $(this).attr('data-room-id');
       $.ajax({
           method: 'get',
           url: '/room/' + roomId + '/leave',
           success: function(res) {
               if (res.status) {
                   $('#group-container #room-' + roomId).remove();
                   if($('#room-content-' + roomId)) {
                       $('#dialog-container').empty();
                   }
               } else {
                   alert(res.error);
               }
           }
       });
       return false;
    });

    $(document).on('click', '.room-delete', function() {
        if (confirm('Вы действительно желаете удалить комнату? Вся история сообщений будет потеряна!')) {
            var roomId = $(this).attr('data-room-id');
            $.ajax({
                method: 'delete',
                url: '/room/' + roomId,
                success: function(res) {
                    if (res.status) {
                        $('#group-container #room-' + roomId).remove();
                        if($('#room-content-' + roomId)) {
                            $('#dialog-container').empty();
                        }
                    } else {
                        alert(res.error);
                    }
                }
            });
        }
        return false;
    });

    $(document).on('click', '.group-row', function(){
        $('.user-row').each(function(i, el) {
            $(el).removeClass('active');
        });
        $('.group-row').each(function(i, el) {
            $(el).removeClass('active');
        });
        $(this).addClass('active');
        var roomId = $(this).attr('data-room-id');
        $('#room-' + roomId).find('.income-message').hide();
        $.ajax({
            method: 'get',
            url: '/chat/room/' + roomId,
            success: function(res) {
                $('#dialog-container').empty().append(res);
                $('#user-container .user-row .user-status.online').each(function(i, el) {
                    var cUid = $(el).closest('.user-row').attr('data-user-id');
                    $('#user-in-room-' + cUid + ' .user-status').removeClass('offline').addClass('online');
                    $('#user-in-room-' + cUid + ' .text-status').text('Online');
                });
                $('#message-container').animate({
                    scrollTop: $('#message-container').find('.direct-chat-msg:last-child').offset().top + 'px'
                }, 'fast');
            }
        });
    });

    $(document).on('click', '.room-member-remove', function(){
        var userId = $(this).attr('data-user-id');
        var roomId = $(this).attr('data-room-id');
        $.ajax({
            method: 'post',
            url: '/room/' + roomId + '/ban',
            data: {
                members : [userId]
            },
            success: function(res) {
                if (res.status) {
                    $('#user-in-room-' + userId).remove();
                } else {
                    alert(res.error);
                }

            }
        });
        return false;
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
               $('.no-messages').remove();
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
                $('.no-messages').remove();
                $('#message-container').append(res);
                $('#message-container').animate({
                    scrollTop: $('#message-container').find('.direct-chat-msg:last-child').offset().top + 'px'
                }, 'fast');
            }
        });
    });

    $(document).on('keyup', '#new-message.room-message', function(event) {
        if (event.keyCode == 13) {
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
                    $('.no-messages').remove();
                    $('#message-container').append(res);
                    $('#message-container').animate({
                        scrollTop: $('#message-container').find('.direct-chat-msg:last-child').offset().top + 'px'
                    }, 'fast');
                }
            });
        }
    });

    $(document).on('keyup', '#new-message.dialog-message', function(event) {
        if (event.keyCode == 13) {
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
                    $('.no-messages').remove();
                    $('#message-container').append(res);
                    $('#message-container').animate({
                        scrollTop: $('#message-container').find('.direct-chat-msg:last-child').offset().top + 'px'
                    }, 'fast');
                }
            });
        }
    });

    $(document).on('submit', '#roomCreateForm', function() {
        $.ajax({
            url: '/chat/room',
            type: 'post',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status) {
                    $('#createRoomModal').modal('hide');
                    $('#roomCreateForm').get(0).reset();
                } else {
                    alert(response.error);
                }
            }
        });
        return false;
    });

    $(document).on('submit', '#roomMembersForm', function() {
        var roomId = $(this).attr('data-room-id');
        $.ajax({
            url: '/chat/room/' + roomId + '/add-members',
            type: 'post',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status) {
                    $('#addRoomMembersModal').modal('hide');
                    $('#roomMembersForm').get(0).reset();
                } else {
                    alert(response.error);
                }
            }
        });
        return false;
    });
});