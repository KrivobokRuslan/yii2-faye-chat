(function(){
    var handler = {
        userConnect : function(data) {
            $('#user-' + data.user_id + ' .user-status').removeClass('offline').addClass('online');
            $('#user-' + data.user_id + ' .text-status').text('Online');

            $('#user-in-room-' + data.user_id + ' .user-status').removeClass('offline').addClass('online');
            $('#user-in-room-' + data.user_id + ' .text-status').text('Online');
        },
        usersOnline : function(data) {
            data.users.forEach(function(item, i) {
                $('#user-' + item + ' .user-status').removeClass('offline').addClass('online');
                $('#user-' + item + ' .text-status').text('Online');

                $('#user-in-room-' + item + ' .user-status').removeClass('offline').addClass('online');
                $('#user-in-room-' + item + ' .text-status').text('Online');
            });
        },
        userDisconnect : function(data) {
            $('#user-' + data.user_id + ' .user-status').removeClass('inline').addClass('offline');
            $('#user-' + data.user_id + ' .text-status').text('Offline');

            $('#user-in-room-' + data.user_id + ' .user-status').removeClass('inline').addClass('offline');
            $('#user-in-room-' + data.user_id + ' .text-status').text('Offline');
        },
        userSignup : function(data) {
            var template = $('#user-container #user-template').clone().prop('id', 'user-' + data.user.id).attr('data-user-id', data.user.id);
            var avatarUrl = data.user.avatar ? data.user.avatar : chat_module_bundle + '/img/no-avatar.png';
            template.find('.username').text(data.user.username);
            template.find('img').attr('src', avatarUrl);
            $('#user-container').append(template);
            template.show();
        },
        newMessage : function(data) {
            var dialogElem = $('#dialog-' + data.message.dialog_id);
            if (!isEmpty(dialogElem) && currentUser != data.message.author.id) {
                $('.no-messages').remove();
                dialogElem.find('#message-container').append(renderMessage(data.message));
                $('#message-container').animate({
                    scrollTop: $('#message-container').find('.direct-chat-msg:last-child').offset().top + 'px'
                }, 'fast');
            } else {
                $('#user-' + data.message.author.id).find('#income-message-user-' + data.message.author.id).css({'display':'inline-block'});
                playAudio();
            }
        },
        addRoom : function(data) {
            data.room.members.forEach(function(el) {
                if (el == currentUser) {
                    var template = $('#group-container #group-template').clone().prop('id', 'room-' + data.room.id).attr('data-room-id', data.room.id);
                    template.find('.username').text(data.room.title);
                    template.find('.members-count').text(data.room.countMembers);
                    template.find('.income-message').attr('id', 'income-message-room-' + data.room.id);
                    if (el == data.room.owner_user_id) {
                        template.find('.room-delete').attr('data-room-id', data.room.id);
                        template.find('.room-leave').attr('data-room-id', data.room.id).hide();
                    } else {
                        template.find('.room-delete').attr('data-room-id', data.room.id).hide();
                        template.find('.room-leave').attr('data-room-id', data.room.id);
                    }
                    $('#group-container').append(template);
                    template.show();
                }
            });
        },
        addToRoom : function(data) {
            var template = $('#group-container #group-template').clone().prop('id', 'room-' + data.room.id).attr('data-room-id', data.room.id);
            template.find('.username').text(data.room.title);
            template.find('.members-count').text(data.room.countMembers);
            template.find('.income-message').attr('id', 'income-message-room-' + data.room.id);
            template.find('.room-delete').attr('data-room-id', data.room.id).hide();
            template.find('.room-leave').attr('data-room-id', data.room.id);
            $('#group-container').append(template);
            template.show();
        },
        newRoomMember : function(data) {
            var roomInList = $('#room-' + data.roomId);
            var roomEl = $('#chatsTabContent');
            if (!isEmpty(roomInList)) {
                roomInList.find('.members-count').text(data.countMembers);
            }
            if (!isEmpty(roomEl)) {
                data.members.forEach(function(el) {
                    var memberTemplate = $('#member-template').clone().prop('id', 'user-in-room-' + el.id).attr('data-user-id', el.id);
                    if (el.avatar) {
                        memberTemplate.find('img').attr('src', el.avatar);
                    }
                    memberTemplate.find('.username').text(el.username);
                    if (!el.isOwner) {
                        memberTemplate.find('.room-member-remove').remove();
                    } else {
                        memberTemplate.find('.room-member-remove').attr('data-user-id', el.id).attr('data-room-id', data.roomId);
                    }
                    var uElem = $('#user-' + el.id + ' .user-status.online');
                    if (!isEmpty(uElem)) {
                        memberTemplate.find('.user-status.offline').removeClass('offline').addClass('online');
                        memberTemplate.find('.text-status').text('Online');
                    }
                    $('#room-members-' + data.roomId + ' .list-group').append(memberTemplate);
                    memberTemplate.show();
                });
            }
        },
        newRoomMessage : function(data) {
            var roomElem = $('#room-content-' + data.message.room_id);
            if (!isEmpty(roomElem) && currentUser != data.message.author.id) {
                $('.no-messages').remove();
                roomElem.find('#message-container').append(renderMessage(data.message));
                $('#message-container').animate({
                    scrollTop: $('#message-container').find('.direct-chat-msg:last-child').offset().top + 'px'
                }, 'fast');
            } else {
                $('#room-' + data.message.room_id).find('#income-message-room-' + data.message.room_id).css({'display':'inline-block'});
                playAudio();
            }
        },
        banRoomMember : function(data) {
            $('#group-container #room-' + data.roomId).remove();
            var roomElem = $('#room-content-' + data.roomId);
            if (!isEmpty(roomElem)) {
                $('#dialog-container').empty();
                alert('Вас исключили из комнаты');
            }

        },
        changeRoomMemberCount : function(data) {
            $('#group-container #room-' + data.roomId).find('.members-count').text(data.countMembers);
        },
        leaveRoom : function(data) {
            $('#user-in-room-' + data.memberId).remove();
            $('#group-container #room-' + data.roomId).find('.members-count').text(data.countMembers);
        },
        deleteRoom : function(data) {
            $('#group-container #room-' + data.roomId).remove();
            var roomElem = $('#room-content-' + data.roomId);
            if (!isEmpty(roomElem)) {
                $('#dialog-container').empty();
                alert('Комната была удалена');
            }
        }
    };
    var ws = new WebSocket(wshost);
    ws.onmessage = function(evt) {
        var data = JSON.parse(evt.data);
        if (handler[data.event]) {
            handler[data.event](data);
        }
    };

    function playAudio() {
        var audio = new Audio();
        audio.src = chat_module_bundle + '/notif.mp3';
        audio.load();
        audio.play().then(function(){}).catch(function(error){});

    }

    function renderMessage(data) {
        var avatarUrl = data.author.avatar ? data.author.avatar : chat_module_bundle + '/img/no-avatar.png';
        return '<div class="direct-chat-msg right">' +
            '    <div class="direct-chat-info clearfix">' +
            '        <span class="direct-chat-name pull-right">' + data.author.username + '</span>' +
            '        <span class="direct-chat-timestamp pull-left"> ' + data.created_at + ' </span>' +
            '    </div>' +
            '    <img class="direct-chat-img" src="' + avatarUrl + '">' +
            '    <div class="direct-chat-text">' + data.message +
            '    </div>' +
            '</div>';
    }

    function isEmpty(obj) {
        for(var key in obj) {
            if(obj.hasOwnProperty(key))
                return false;
        }
        return true;
    }
})();