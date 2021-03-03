<?php

namespace App\Messages;


class UserConnectedMessage extends AbstractMessage {
    protected $name = 'user_connect';

    protected $keys = [];
}
