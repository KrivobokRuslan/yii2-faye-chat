var http = require('http'),
    faye = require('faye'),
    url = require('url');

var server = http.createServer(),
    bayeux = new faye.NodeAdapter({mount: '/'}),
    users = {};
bayeux.addExtension({
    incoming: function (message, request, callback) {
        var url_parts = url.parse(request.url, true);
        if (url_parts.query.user_id && message.channel === '/meta/connect') {
            users[url_parts.query.user_id] = {
                user_id: url_parts.query.user_id,
                client_ids: [
                    message.clientId
                ]
            };
        }
        callback(message);
    }
});

bayeux.on('subscribe', function (clientId, channel) {
    var client = new faye.Client('http://localhost:8000');
    if (channel === '/connect') {
        var userId = null;
        for (user_id in users) {
            if (users[user_id].client_ids && users[user_id].client_ids.indexOf(clientId) !== -1) {
                userId = users[user_id].user_id;
            }
        }
        if (userId) {
            client.publish('/get-online-users/' + userId, {
                users: users
            });
            client.publish('/connect', {
                client_id: clientId,
                user_id: userId
            });
        }
    }
});

bayeux.on('unsubscribe', function (clientId, channel) {
    if (channel === '/connect') {
        var userId = null;
        var client = new faye.Client('http://localhost:8000');
        for (user_id in users) {
            var idx = users[user_id].client_ids.indexOf(clientId);
            if (idx !== -1) {
                users[user_id].client_ids.splice(idx, 1);
                if (users[user_id].client_ids.length === 0) {
                    userId = users[user_id].user_id;
                    delete users[user_id];
                }
            }
        }
        if (userId) {
            client.publish('/disconnect', {
                client_id: clientId,
                user_id: userId
            });
        }
    }
});

bayeux.attach(server);
server.listen(8000);