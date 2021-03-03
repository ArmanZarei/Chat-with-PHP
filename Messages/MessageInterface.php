<?php

namespace App\Messages;

interface MessageInterface {
    public function isValid();

    public function getData();

    public function getJsonData();

    public function get($key);
}
