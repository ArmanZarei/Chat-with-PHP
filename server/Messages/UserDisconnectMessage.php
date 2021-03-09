<?php

namespace App\Messages;


class UserDisconnectMessage extends AbstractMessage {
    protected $name = 'user_disconnect';

    protected $keys = [
        'name',
    ];
}
