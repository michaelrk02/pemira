<?php

namespace App\Libraries;

class WebToken {

    public $header = [];
    public $payload = [];

    public function isExpired() {
        if (isset($this->header['expired'])) {
            return time() >= $this->header['expired'];
        }
        return FALSE;
    }

    public function getSignature() {
        $buffer = '';
        $buffer .= base64_encode(json_encode($this->header));
        $buffer .= base64_encode(json_encode($this->payload));
        return sha1($_ENV['pemira.server.secret'].$buffer);
    }

    public function toString() {
        $parts = [
            base64_encode(json_encode($this->header)),
            base64_encode(json_encode($this->payload)),
            $this->getSignature()
        ];
        return implode('.', $parts);
    }

    public static function fromString($str, $signatureCheck = TRUE) {
        $data = explode('.', $str);

        $header = @json_decode(@base64_decode(@$data[0]), TRUE);
        $payload = @json_decode(@base64_decode(@$data[1]), TRUE);
        $token = WebToken::fromData($header, $payload);

        $signature = @$data[2];
        if (($signatureCheck && ($signature === $token->getSignature())) || !$signatureCheck) {
            if (!$token->isExpired()) {
                return $token;
            }
        }

        return NULL;
    }

    public static function fromData($header, $payload) {
        $token = new WebToken();
        $token->header = $header;
        $token->payload = $payload;
        return $token;
    }

}

