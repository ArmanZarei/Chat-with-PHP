<?php

namespace App\Messages;


class AbstractMessage implements MessageInterface {
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $keys;

    /**
     * @var array
     */
    protected $data = [];

    public function __construct($message) {
        if (is_array($message)) {
            $this->initData($message);
        }
    }

    public function isValid()
    {
        foreach ($this->keys as $key) {
            if (isset($this->data[$key]) && $this->data[$key])
                continue;

            return false;
        }

        return true;
    }

    public function getData()
    {
        return array_merge($this->data, ['type' => $this->name]);
    }

    private function initData($arr) {
        foreach ($this->keys as $key) {
            $this->data[$key] = $arr[$key];
        }
    }

    public function getJsonData()
    {
        return json_encode($this->getData());
    }

    public function get($key) {
        return $this->data[$key];
    }
}
