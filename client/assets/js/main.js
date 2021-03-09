let currentHref = window.location.href + "server/";
currentHref = currentHref.replace(/(http|https)/, 'ws');
const webSocketUrl = currentHref;

const swalWrapper = {
    _swalAlert: function (title, text, icon) {
        swal({
            title,
            text,
            icon,
            button: "OK",
        });
    },
    successAlert: function (title, text) {
        this._swalAlert(title, text, "success");
    },
    errorAlert: function (title, text) {
        this._swalAlert(title, text, "error");
    }
};

$(document).ready(function () {
    const chatManager = {
        _container: $("#chat-container"),
        createMessage: function (color, name, msg) {
            this._container.append(`<p class="mb-0"><span class="font-weight-bold" style=\"color: ${color}\">${name}: </span> ${msg}</p>`);
            this.scrollDown();
        },
        createConnectMessage: function () {
            this._container.append('<p class="mb-0 text-success">Somebody joined the chat</p>')
            this.scrollDown();
        },
        createDisconnetMessage: function (name) {
            name = name ? name : "Somebody";
            this._container.append(`<p class="mb-0 text-danger">${name} left the chat</p>`)
            this.scrollDown();
        },
        scrollDown: function () {
            this._container.scrollTop(this._container.prop('scrollHeight'))
        }
    };

    let webSocket = new WebSocket(webSocketUrl);
    webSocket.onopen = function(e) {
        swalWrapper.successAlert("Success", "Connection established");
        $("#loader-wrapper").trigger("loading:stop");
    };
    webSocket.onclose = function(e) {
        swalWrapper.errorAlert("Error", "Connection closed");
        $("#loader-wrapper").trigger("loading:stop");
    };
    webSocket.onmessage = function(e) {
        const data = JSON.parse(e.data);

        switch (data.type) {
            case 'user_message':
                chatManager.createMessage(data.color, data.name, data.message);
                break;
            case 'user_connect':
                chatManager.createConnectMessage();
                break;
            case 'user_disconnect':
                chatManager.createDisconnetMessage(data.name);
                break;
            default:
                console.log(data);
        }
    };
    webSocket.onerror = function(e) {
        swalWrapper.errorAlert("Error", "An error occurred");
        $("#loader-wrapper").trigger("loading:stop");
    };

    $("#chat-form").on('submit', function (e) {
        e.preventDefault();
        const $this = $(this);
        const data = {
            color: $this.find("#color").val(),
            name: $this.find("#name").val(),
            message: $this.find("#message").val(),
        };
        webSocket.send(JSON.stringify(data));
        $this.find("#message").val("");
    });



    // Spinner Loader
    $("#loader-wrapper").on("loading:stop", function () {
        $(this).css({display: "none"});
    }).on("loading:start", function () {
        $(this).css({display: "block"});
    });
});
