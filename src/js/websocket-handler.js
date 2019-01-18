(function(){
    var handler = {
        userConnect : function(data) {
            $('#user-' + data.user_id + ' .user-status').removeClass('offline').addClass('online');
            $('#user-' + data.user_id + ' .text-status').text('Online');
        },
        usersOnline : function(data) {
            data.users.forEach(function(item, i) {
                $('#user-' + item + ' .user-status').removeClass('offline').addClass('online');
                $('#user-' + item + ' .text-status').text('Online');
            });
        },
        userDisconnect : function(data) {
            $('#user-' + data.user_id + ' .user-status').removeClass('inline').addClass('offline');
            $('#user-' + data.user_id + ' .text-status').text('Offline');
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
            if (!isEmpty(dialogElem)) {
                dialogElem.find('#message-container').append(renderMessage(data.message));
            } else {
                console.log(data.message.author.id);
                console.log($('#user-' + data.message.author.id).find('.income-message'));
                $('#user-' + data.message.author.id).find('.income-message').show();
                playAudio();
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
        audio.play().then(function(){}).catch (function(error){});
    }

    function renderMessage(data) {
        var avatarUrl = data.author.avatar ? data.author.avatar : chat_module_bundle + '/img/no-avatar.png';
        return '<div class="direct-chat-msg right">' +
            '    <div class="direct-chat-info clearfix">' +
            '        <span class="direct-chat-name pull-right">' + data.author.username + '</span>' +
            '        <span class="direct-chat-timestamp pull-left">23 Jan 2:00 pm</span>' +
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