<?php

namespace App\Libraries;

class ActivationMessage {

    public $nim;
    public $nama;
    public $sso;
    public $token;

    public function __construct($nim, $nama, $sso, $token) {
        $this->nim = $nim;
        $this->nama = $nama;
        $this->sso = $sso;
        $this->token = $token;
    }

    public function getLink() {
        return site_url('user/auth/activate').'?token='.urlencode($this->token);
    }

    public function getFullHTML() {
        $text = '';

        $expirationHours = ceil($_ENV['pemira.activation.expire'] / 3600);

        $text .= '<p>NIM: <b>'.esc($this->nim).'</b></p>';
        $text .= '<p>Nama: <b>'.esc($this->nama).'</b></p>';
        $text .= '<p>SSO: <b>'.esc($this->sso).'@'.$_ENV['pemira.mail.host'].'</b></p>';
        $text .= '<p>Jika keterangan di atas benar-benar merupakan identitas anda, silakan klik link di bawah untuk melakukan aktivasi akun dan mendapatkan kartu akses. Namun jika tidak, abaikan saja pesan ini untuk menghindari tindakan penyalahgunaan. <b>Dilarang membagikan link aktivasi kepada siapapun bahkan kepada pihak yang mengaku sebagai panitia</b></p>';
        $text .= '<p>Link aktivasi (akan kadaluarsa dalam '.$expirationHours.' jam): <a href="'.$this->getLink().'">'.$this->getLink().'</a></p>';
        $text .= '<p>Copyright &copy; '.$_ENV['pemira.info.copyright'].'</p>';

        return $text;
    }

}

