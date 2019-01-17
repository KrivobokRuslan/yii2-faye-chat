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
    }
};

ws.onmessage = function(evt) {
    var data = JSON.parse(evt.data);
    handler[data.event](data);
};