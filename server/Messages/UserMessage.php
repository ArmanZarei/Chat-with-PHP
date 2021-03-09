<?php

namespace App\Messages;


class UserMessage extends AbstractMessage {
    protected $name = 'user_message';

    protected $keys = [
        'name',
        'color',
        'message',
    ];
}
