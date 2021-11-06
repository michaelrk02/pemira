<?php

namespace App\Libraries;

class Status {

    public $severity;
    public $message;

    public function __construct($severity = '', $message = '') {
        $this->severity = $severity;
        $this->message = $message;
    }

}

